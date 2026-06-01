<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutorix - Student Fee Details</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background: #f0f2f5; }
        
        .app-container { display: flex; min-height: 100vh; }
        
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 100;
        }
        
        .sidebar-header { padding: 25px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); text-align: center; }
        .sidebar-header h2 { font-size: 22px; margin-bottom: 5px; }
        
        .nav-menu { padding: 20px 0; }
        .nav-item {
            padding: 12px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.3s;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
        }
        .nav-item:hover { background: rgba(255,255,255,0.1); color: white; }
        .nav-item.active { background: #4361ee; color: white; border-left: 4px solid #fff; }
        .nav-item .icon { font-size: 20px; }
        
        .main-content { flex: 1; margin-left: 280px; padding: 20px; }
        .container { max-width: 1400px; margin: 0 auto; }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 15px;
        }
        h1 { font-size: 26px; color: #1a1a2e; }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-primary { background: #4361ee; color: white; }
        .btn-primary:hover { background: #3a56d4; transform: translateY(-1px); }
        .btn-secondary { background: #6c757d; color: white; padding: 6px 12px; font-size: 12px; margin: 2px; border-radius: 6px; }
        .btn-secondary:hover { background: #5a6268; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; }
        .btn-success { background: #28a745; color: white; }
        .btn-success:hover { background: #218838; }
        .btn-warning { background: #ffc107; color: #333; }
        .btn-warning:hover { background: #e0a800; }
        
        table { 
            width: 100%; 
            background: white; 
            border-collapse: collapse; 
            border-radius: 12px; 
            overflow: hidden; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); 
        }
        th, td { 
            padding: 14px 12px; 
            text-align: left; 
            border-bottom: 1px solid #e9ecef; 
        }
        th { 
            background: #f8f9fa; 
            font-weight: 600; 
            color: #495057;
            font-size: 13px;
        }
        tr:hover { background: #f8f9fa; }
        
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 13px; }
        .form-group input, .form-group select {
            width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none; border-color: #4361ee;
        }
        .form-row { display: flex; gap: 15px; flex-wrap: wrap; }
        .form-row .form-group { flex: 1; min-width: 150px; }
        
        .loading { text-align: center; padding: 50px; }
        .spinner { border: 3px solid #f3f3f3; border-top: 3px solid #4361ee; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 15px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        .pagination-container { margin-top: 25px; display: flex; justify-content: center; align-items: center; gap: 8px; flex-wrap: wrap; }
        .pagination-btn { padding: 8px 14px; border: 1px solid #ddd; background: white; border-radius: 6px; cursor: pointer; font-size: 14px; }
        .pagination-btn.active { background: #4361ee; color: white; border-color: #4361ee; }
        .pagination-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .per-page-select { margin-left: 15px; padding: 6px 10px; border-radius: 6px; border: 1px solid #ddd; cursor: pointer; }
        .pagination-info { text-align: center; margin-top: 15px; color: #666; font-size: 13px; }
        
        .info-message {
            background: #e3f2fd;
            border: 1px solid #90caf9;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
            color: #1565c0;
        }
        
        .status-paid { background: #d4edda; color: #155724; padding: 4px 12px; border-radius: 20px; font-size: 12px; display: inline-block; }
        .status-pending { background: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 20px; font-size: 12px; display: inline-block; }
        .status-partial { background: #d1ecf1; color: #0c5460; padding: 4px 12px; border-radius: 20px; font-size: 12px; display: inline-block; }
        
        .term-badge {
            background: #e9ecef;
            color: #495057;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }
        
        .action-btn {
            background: #17a2b8;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 6px 12px;
            cursor: pointer;
            font-size: 12px;
            margin: 0 3px;
        }
        .action-btn:hover { background: #138496; }
        .action-btn.delete { background: #dc3545; }
        .action-btn.delete:hover { background: #c82333; }
        
        .view-toggle {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 10px;
        }
        .view-toggle-btn {
            padding: 8px 20px;
            background: #f0f0f0;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }
        .view-toggle-btn.active {
            background: #4361ee;
            color: white;
        }
        .view-toggle-btn:hover:not(.active) {
            background: #e0e0e0;
        }
        
        .term-details-container {
            max-height: 70vh;
            overflow-y: auto;
            padding: 10px;
        }
        .term-detail-card {
            background: #f8f9fa;
            border-radius: 12px;
            margin-bottom: 20px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
        }
        .term-detail-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        .term-name {
            font-size: 18px;
            font-weight: 600;
        }
        .term-due {
            font-size: 13px;
            opacity: 0.9;
        }
        .term-stats-row {
            display: flex;
            gap: 30px;
            background: white;
            padding: 12px 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        .stat-box {
            text-align: center;
        }
        .stat-label {
            font-size: 11px;
            color: #6c757d;
            margin-bottom: 4px;
        }
        .stat-value {
            font-size: 18px;
            font-weight: 700;
        }
        .stat-value.total { color: #4361ee; }
        .stat-value.paid { color: #28a745; }
        .stat-value.pending { color: #dc3545; }
        .fee-group-section {
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
        }
        .fee-group-title {
            font-weight: 600;
            color: #495057;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e0e0e0;
        }
        .fee-row-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }
        .fee-name-item { font-size: 14px; color: #555; }
        .fee-amount-item { font-size: 14px; font-weight: 500; color: #333; }
        .fee-paid-item { font-size: 12px; color: #28a745; }
        .fee-pending-item { font-size: 12px; color: #dc3545; }
        
        .edit-term-card {
            background: white;
            border-radius: 12px;
            margin-bottom: 20px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
        }
        .edit-term-header {
            background: #f0f7ff;
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
            flex-wrap: wrap;
            gap: 10px;
        }
        .edit-term-title {
            font-weight: 600;
            color: #4361ee;
            font-size: 16px;
        }
        .edit-term-due-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .edit-term-due-label {
            font-size: 12px;
            color: #666;
        }
        .edit-term-due {
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 13px;
            width: 120px;
            cursor: pointer;
        }
        .edit-delete-term {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 6px 12px;
            cursor: pointer;
            font-size: 12px;
        }
        .edit-fee-group {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
        }
        .edit-group-title {
            font-weight: 600;
            color: #495057;
            margin-bottom: 10px;
        }
        .edit-fee-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed #f0f0f0;
        }
        .edit-fee-name {
            font-size: 14px;
            color: #555;
        }
        .edit-fee-amount {
            width: 120px;
            padding: 6px 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 13px;
        }
        .edit-delete-fee {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 26px;
            height: 26px;
            cursor: pointer;
            font-size: 12px;
            margin-left: 10px;
        }
        
        .new-term-form {
            background: #e8f5e9;
            border: 2px solid #28a745;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }
        .new-term-title {
            font-size: 16px;
            font-weight: 600;
            color: #28a745;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #28a745;
        }
        .new-term-row {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .new-term-row select {
            flex: 2;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .new-term-row input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .new-term-due {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            width: 150px;
        }
        .remove-fee-cross {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .rightpanel-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); z-index: 9998; display: none;
        }
        .rightpanel-drawer {
            position: fixed; top: 0; right: 0; height: 100%; background: white;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1); z-index: 9999;
            transform: translateX(100%); transition: transform 0.3s ease;
        }
        
        .swal2-container { z-index: 20000 !important; }
        
        .amount-due { color: #dc3545; font-weight: 600; }
        
        .term-box {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }
        .term-header-simple {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e0e0e0;
            flex-wrap: wrap;
            gap: 10px;
        }
        .term-name-display {
            font-size: 16px;
            font-weight: 600;
            color: #4361ee;
            background: transparent;
            border: none;
            padding: 0;
        }
        .term-total {
            background: #e8f5e9;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: #2e7d32;
        }
        .remove-term-cross {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            cursor: pointer;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .term-due-date-input {
            padding: 6px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            background: #fff;
        }
        .fee-select-row {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        .fee-select-row select {
            flex: 2;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .fee-select-row input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .add-fee-btn {
            background: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            width: 40px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }
        .fee-items-list {
            margin-top: 12px;
        }
        .fee-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 8px;
        }
        
        .term-group-section {
            margin-top: 15px;
            border-top: 1px solid #e0e0e0;
            padding-top: 12px;
        }
        .term-group-title {
            font-weight: 600;
            color: #4361ee;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .term-fee-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px dashed #f0f0f0;
        }
        .term-fee-name {
            font-size: 13px;
            color: #555;
        }
        .term-fee-amount-input {
            width: 100px;
            padding: 4px 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 13px;
            text-align: right;
        }
        
        .amount-small-box {
            background: #f0f7ff;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .school-total-text {
            font-size: 14px;
            font-weight: 500;
            color: #4361ee;
        }
        .school-total-value {
            font-size: 20px;
            font-weight: 700;
            color: #4361ee;
        }
        
        .draft-info {
            background: #fff3e0;
            border-left: 4px solid #ff9800;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .btn-save {
            background: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            margin-top: 20px;
            width: 100%;
        }
        .btn-save:hover {
            background: #218838;
        }
        
        .term-summary {
            background: #e8f5e9;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }
        .term-summary span {
            font-weight: 700;
            color: #2e7d32;
        }
        
        .btn-submit {
            background: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }
        .btn-submit:hover {
            background: #218838;
        }
        
        .create-term-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        
        .create-term-btn-right {
            background: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 6px 16px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }
        
        .create-term-btn-right:hover:not(:disabled) {
            background: #218838;
        }
        
        .create-term-btn-right:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .term-summary-right {
            background: #e8f5e9;
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .term-summary-right span {
            font-weight: 700;
            color: #2e7d32;
        }
        
        .summary-stats {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .summary-stat {
            font-size: 14px;
        }
        
        .summary-stat strong {
            color: #4361ee;
        }
        
        .simplified-term-form {
            background: #e8f5e9;
            border: 2px solid #28a745;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }
        
        .simplified-term-title {
            font-size: 15px;
            font-weight: 600;
            color: #28a745;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 1px solid #28a745;
        }
        
        .create-term-section {
            display: none;
        }
        
        .create-term-section.show {
            display: block;
        }
        
        .pay-btn {
            background: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            margin-top: 10px;
            transition: all 0.2s ease;
        }
        
        .pay-btn:hover {
            background: #218838;
            transform: translateY(-1px);
        }
        
        .term-paid-badge {
            background: #28a745;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
            margin-left: 10px;
        }
        
        .term-paid {
            opacity: 0.7;
        }
        
        .edit-term-card.term-paid {
            background: #f8f9fa;
            border-left: 4px solid #28a745;
        }
        
        .edit-term-card.term-paid .edit-fee-amount,
        .edit-term-card.term-paid .edit-term-due,
        .edit-term-card.term-paid .edit-delete-term,
        .edit-term-card.term-paid .edit-delete-fee {
            opacity: 0.6;
            pointer-events: none;
            background: #e9ecef;
        }
        
        .payment-form-container {
            padding: 20px;
        }
        .payment-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .payment-detail-label {
            font-weight: 600;
            color: #555;
        }
        .payment-detail-value {
            color: #333;
            font-weight: 500;
        }
        .payment-amount {
            font-size: 18px;
            color: #28a745;
        }
        .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            margin-top: 5px;
        }
        .form-input:focus {
            outline: none;
            border-color: #4361ee;
        }
        .online-payment-note {
            background: #fff3cd;
            border: 1px solid #ffecb5;
            border-radius: 8px;
            padding: 10px;
            margin-top: 15px;
            font-size: 12px;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="sidebar">
            <div class="sidebar-header"><h2>Tutorix</h2></div>
            <div class="nav-menu">
                <a href="school-management.php" class="nav-item"><span class="icon">🏫</span><span>School Management</span></a>
                <a href="holidays.php" class="nav-item"><span class="icon">📅</span><span>School Holidays</span></a>
                <a href="school_fee_structure.php" class="nav-item"><span class="icon">💰</span><span>School Fee Structure</span></a>
                <a href="student_fee_details.php" class="nav-item active"><span class="icon">👨‍🎓</span><span>Student Fee Details</span></a>
            </div>
        </div>
        
        <div class="main-content">
            <div class="container">
                <div class="header">
                    <h1>👨‍🎓 Student Fee Details</h1>
                    <button class="btn btn-primary" id="assignFeeBtn">+ Assign Fees</button>
                </div>
                
                <div class="filter-section">
                    <h3 style="margin-bottom: 15px;">🔍 Filter Student Fees</h3>
                    <div class="form-row">
                        <div class="form-group"><label>School Name / Code</label><select id="filter_school_id"><option value="">All Schools</option></select></div>
                        <div class="form-group"><label>Academic Year</label><select id="filter_academic_year"><option value="">All Years</option></select></div>
                        <div class="form-group"><label>Class</label><select id="filter_class_id"><option value="">All Classes</option></select></div>
                        <div class="form-group" style="display: flex; align-items: flex-end;">
                            <button id="applyFilterBtn" class="btn btn-primary" style="padding: 10px 25px;">Apply Filter</button>
                            <button id="resetFilterBtn" class="btn btn-primary" style="padding: 10px 25px; margin-left: 10px; background: #6c757d;">Reset</button>
                        </div>
                    </div>
                </div>
                
                <div id="infoMessage" class="info-message" style="display: block;">ℹ️ Please apply filters to view student fee details</div>
                <div id="loadingDiv" class="loading" style="display: none;"><div class="spinner"></div><p>Loading student fee details...</p></div>
                
                <div id="tableContainer" style="display: none;">
                    <div style="overflow-x: auto;">
                        <table style="min-width: 1000px;">
                            <thead>
                                <tr>
                                    <th>S.N.</th>
                                    <th>Student Name</th>
                                    <th>Mobile No.</th>
                                    <th>Total Fee (₹)</th>
                                    <th>Paid (₹)</th>
                                    <th>Pending (₹)</th>
                                    <th>Terms</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="studentFeeList"></tbody>
                        </table>
                    </div>
                    <div class="pagination-info" id="paginationInfo"></div>
                    <div class="pagination-container" id="paginationContainer"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/rightpanel.js"></script>
    <script>
        toastr.options = { closeButton: true, progressBar: true, positionClass: "toast-top-right", timeOut: "4000" };
        const API_BASE = 'http://localhost/school_management/api/';
        
        let currentPage = 1;
        let totalPages = 1;
        let perPage = 10;
        let totalRows = 0;
        
        let schoolFees = [];
        let terms = [];
        let schoolTotalAmount = 0;
        let currentDraftSchoolId = null;
        
        const STORAGE_KEYS = {
            TERMS: 'fee_assignment_terms'
        };
        
        function escapeHtml(str) { if (!str) return ''; return String(str).replace(/[&<>]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[m])); }
        
        function formatNumber(num) {
            return parseFloat(num).toLocaleString('en-IN');
        }
        
        function roundToTwo(num) {
            return Math.round((num + Number.EPSILON) * 100) / 100;
        }
        
        function formatDateForDisplay(dateStr) {
            if (!dateStr || dateStr === '0000-00-00' || dateStr === 'Not Set' || dateStr === '') return '';
            const parts = dateStr.split('-');
            if (parts.length === 3) {
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
            return dateStr;
        }
        
        function formatDateForDatabase(dateStr) {
            if (!dateStr) return null;
            const parts = dateStr.split('-');
            if (parts.length === 3) {
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
            return dateStr;
        }
        
        function openPanel(title, contentHtml, onClose, width = '750px', icon = '📋') {
            return RightPanel.open({ title, icon, width, html: contentHtml, onClose });
        }
        function closePanel() { RightPanel.closeTop(); }
        
        function saveTermsToStorage() {
            if (!currentDraftSchoolId) return;
            const draftData = {
                school_id: currentDraftSchoolId,
                terms: terms,
                total_amount: schoolTotalAmount,
                updated_at: new Date().toISOString()
            };
            let drafts = JSON.parse(localStorage.getItem(STORAGE_KEYS.TERMS) || '{}');
            drafts[currentDraftSchoolId] = draftData;
            localStorage.setItem(STORAGE_KEYS.TERMS, JSON.stringify(drafts));
        }
        
        function loadTermsFromStorage(schoolId) {
            const drafts = JSON.parse(localStorage.getItem(STORAGE_KEYS.TERMS) || '{}');
            const draft = drafts[schoolId];
            if (draft && draft.terms && draft.terms.length > 0) {
                terms = draft.terms;
                schoolTotalAmount = draft.total_amount;
                return true;
            }
            return false;
        }
        
        function clearTermsFromStorage(schoolId) {
            const drafts = JSON.parse(localStorage.getItem(STORAGE_KEYS.TERMS) || '{}');
            delete drafts[schoolId];
            localStorage.setItem(STORAGE_KEYS.TERMS, JSON.stringify(drafts));
        }
        
        function checkForExistingDraft(schoolId) {
            const drafts = JSON.parse(localStorage.getItem(STORAGE_KEYS.TERMS) || '{}');
            const draft = drafts[schoolId];
            return draft && draft.terms && draft.terms.length > 0;
        }
        
        function getDraftInfo(schoolId) {
            const drafts = JSON.parse(localStorage.getItem(STORAGE_KEYS.TERMS) || '{}');
            const draft = drafts[schoolId];
            if (draft && draft.terms) {
                const termCount = draft.terms.length;
                const updatedAt = new Date(draft.updated_at);
                return { termCount, updatedAt: updatedAt.toLocaleString() };
            }
            return null;
        }
        
        let autoSaveInterval = null;
        
        function startAutoSave() {
            if (autoSaveInterval) clearInterval(autoSaveInterval);
            autoSaveInterval = setInterval(() => {
                if (terms.length > 0 && currentDraftSchoolId) {
                    saveTermsToStorage();
                }
            }, 10000);
        }
        
        function stopAutoSave() {
            if (autoSaveInterval) {
                clearInterval(autoSaveInterval);
                autoSaveInterval = null;
            }
        }
        
        function generateAcademicYearOptions() {
            const currentYear = new Date().getFullYear();
            let options = '<option value="">All Years</option>';
            for (let i = currentYear - 2; i <= currentYear + 5; i++) {
                options += `<option value="${i}">${i}</option>`;
            }
            return options;
        }
        
        async function loadSchools() {
            try {
                const res = await fetch(API_BASE + 'school_list.php');
                const data = await res.json();
                if (data.success) {
                    const select = document.getElementById('filter_school_id');
                    select.innerHTML = '<option value="">All Schools</option>';
                    data.data.forEach(school => {
                        select.innerHTML += `<option value="${school.school_id}">${escapeHtml(school.school_name)} (${school.school_code})</option>`;
                    });
                }
            } catch(e) { console.error('Error loading schools:', e); }
        }
        
        async function loadSchoolFees(schoolId) {
            if (!schoolId) return [];
            try {
                const res = await fetch(API_BASE + `fee_structure_grouped.php?school_id=${schoolId}`);
                const data = await res.json();
                if (data.success && data.data) {
                    schoolFees = [];
                    data.data.forEach(group => {
                        group.fees.forEach(fee => {
                            schoolFees.push({
                                fee_name: fee.fee_name,
                                amount: roundToTwo(fee.amount),
                                group_id: group.group_id,
                                group_name: group.group_name,
                                allocated: 0
                            });
                        });
                    });
                    schoolTotalAmount = roundToTwo(schoolFees.reduce((sum, fee) => sum + fee.amount, 0));
                    return schoolFees;
                }
                return [];
            } catch(e) {
                console.error('Error loading fee groups:', e);
                toastr.error('Error loading fee structures for this school');
                return [];
            }
        }
        
        function getRemainingFeeAmount(feeName) {
            const fee = schoolFees.find(f => f.fee_name === feeName);
            if (!fee) return 0;
            let allocated = 0;
            terms.forEach(term => {
                const termFee = term.fees.find(f => f.fee_name === feeName);
                if (termFee) allocated += termFee.amount;
            });
            return roundToTwo(fee.amount - allocated);
        }
        
        function getTotalAllocatedAmount() {
            let total = 0;
            terms.forEach(term => {
                term.fees.forEach(fee => {
                    total += fee.amount;
                });
            });
            return roundToTwo(total);
        }
        
        function updateFeeAmount(termIndex, feeIndex, newAmount) {
            const term = terms[termIndex];
            const fee = term.fees[feeIndex];
            const feeData = schoolFees.find(f => f.fee_name === fee.fee_name);
            
            if (!feeData) return;
            
            let totalAllocatedForFee = 0;
            terms.forEach(t => {
                const existingFee = t.fees.find(f => f.fee_name === fee.fee_name);
                if (existingFee) {
                    totalAllocatedForFee += existingFee.amount;
                }
            });
            
            totalAllocatedForFee = totalAllocatedForFee - fee.amount + newAmount;
            
            if (totalAllocatedForFee > feeData.amount + 0.01) {
                toastr.error(`Total allocated for ${fee.fee_name} (₹${totalAllocatedForFee.toLocaleString('en-IN')}) exceeds school total (₹${feeData.amount.toLocaleString('en-IN')})`);
                return;
            }
            
            fee.amount = roundToTwo(newAmount);
            term.term_amount = roundToTwo(term.fees.reduce((sum, f) => sum + f.amount, 0));
            renderTermsList();
            saveTermsToStorage();
        }
        
        function addTerm() {
            terms.push({
                term_name: `Term ${terms.length + 1}`,
                term_amount: 0,
                due_date: '',
                fees: []
            });
            renderTermsList();
            saveTermsToStorage();
        }
        
        function removeTerm(index) {
            terms.splice(index, 1);
            terms.forEach((term, idx) => {
                term.term_name = `Term ${idx + 1}`;
            });
            renderTermsList();
            saveTermsToStorage();
        }
        
        function updateTermDueDate(index, value) {
            terms[index].due_date = value;
            saveTermsToStorage();
        }
        
        function addFeeToTerm(termIndex) {
            const feeSelect = document.getElementById(`term_fee_select_${termIndex}`);
            const amountInput = document.getElementById(`term_fee_amount_${termIndex}`);
            
            if (!feeSelect || !feeSelect.value) {
                toastr.warning('Please select a fee');
                return;
            }
            
            let amount = roundToTwo(parseFloat(amountInput.value));
            if (isNaN(amount) || amount <= 0) {
                toastr.warning('Please enter a valid amount');
                return;
            }
            
            const feeName = feeSelect.value;
            const remaining = getRemainingFeeAmount(feeName);
            
            if (amount > remaining + 0.01) {
                toastr.error(`Amount cannot exceed remaining amount of ₹${remaining.toLocaleString('en-IN')} for this fee`);
                return;
            }
            
            const feeData = schoolFees.find(f => f.fee_name === feeName);
            if (!feeData) {
                toastr.error('Fee not found');
                return;
            }
            
            const existingFee = terms[termIndex].fees.find(f => f.fee_name === feeName);
            if (existingFee) {
                toastr.warning('This fee is already added to this term');
                return;
            }
            
            terms[termIndex].fees.push({
                fee_name: feeName,
                amount: amount,
                group_id: feeData.group_id,
                group_name: feeData.group_name
            });
            
            terms[termIndex].term_amount = roundToTwo(terms[termIndex].fees.reduce((sum, f) => sum + f.amount, 0));
            feeSelect.value = '';
            amountInput.value = '';
            
            renderTermsList();
            saveTermsToStorage();
            toastr.success('Fee added to term');
        }
        
        function removeFeeFromTerm(termIndex, feeIndex) {
            terms[termIndex].fees.splice(feeIndex, 1);
            terms[termIndex].term_amount = roundToTwo(terms[termIndex].fees.reduce((sum, f) => sum + f.amount, 0));
            renderTermsList();
            saveTermsToStorage();
            toastr.info('Fee removed from term');
        }
        
        function renderTermsList() {
            const container = document.getElementById('termsListContainer');
            if (!container) return;
            
            if (terms.length === 0) {
                container.innerHTML = '<div style="text-align:center; padding:40px; color:#999; border:2px dashed #ddd; border-radius:12px;">No terms added. Click "Create Term" to add fee terms.</div>';
                return;
            }
            
            if (schoolFees.length === 0) {
                container.innerHTML = '<div style="text-align:center; padding:40px; color:#999; border:2px dashed #ddd; border-radius:12px;">⚠️ No fee structures found. Please create fee structures first.</div>';
                return;
            }
            
            let html = '';
            terms.forEach((term, idx) => {
                let feeOptionsHtml = '<option value="">-- Select Fee --</option>';
                schoolFees.forEach(fee => {
                    const remaining = getRemainingFeeAmount(fee.fee_name);
                    const isInThisTerm = term.fees.some(f => f.fee_name === fee.fee_name);
                    if (!isInThisTerm && remaining > 0) {
                        feeOptionsHtml += `<option value="${fee.fee_name}">${escapeHtml(fee.fee_name)} (${escapeHtml(fee.group_name)}) - Remaining: ₹${remaining.toLocaleString('en-IN')}</option>`;
                    }
                });
                
                const groupedFees = {};
                term.fees.forEach(fee => {
                    if (!groupedFees[fee.group_name]) {
                        groupedFees[fee.group_name] = [];
                    }
                    groupedFees[fee.group_name].push(fee);
                });
                
                let groupedFeesHtml = '';
                for (const groupName in groupedFees) {
                    const groupFees = groupedFees[groupName];
                    const groupTotal = roundToTwo(groupFees.reduce((sum, f) => sum + f.amount, 0));
                    groupedFeesHtml += `
                        <div class="term-group-section">
                            <div class="term-group-title">📁 ${escapeHtml(groupName)} (Total: ₹${groupTotal.toLocaleString('en-IN')})</div>
                            ${groupFees.map((fee, feeIdx) => `
                                <div class="term-fee-row">
                                    <span class="term-fee-name">${escapeHtml(fee.fee_name)}</span>
                                    <div>
                                        <input type="number" class="term-fee-amount-input" value="${fee.amount}" step="0.01" 
                                               onchange="updateFeeAmount(${idx}, ${feeIdx}, parseFloat(this.value))"
                                               style="width: 100px; text-align: right;">
                                        <button type="button" class="remove-fee-cross" onclick="removeFeeFromTerm(${idx}, ${feeIdx})" style="margin-left: 8px;">✕</button>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    `;
                }
                
                let feesHtml = '';
                if (term.fees.length > 0) {
                    feesHtml = `
                        <div class="fee-items-list">
                            ${groupedFeesHtml}
                        </div>
                    `;
                }
                
                const displayDueDate = formatDateForDisplay(term.due_date);
                
                html += `
                    <div class="term-box">
                        <div class="term-header-simple">
                            <div class="term-name-display">${escapeHtml(term.term_name)}</div>
                            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                                <input type="text" class="term-due-date-input" id="due_date_${idx}" placeholder="Due Date (DD-MM-YYYY)" value="${displayDueDate}" style="width: 150px;">
                                <span class="term-total">Total: ₹${(term.term_amount || 0).toLocaleString('en-IN')}</span>
                                <button type="button" class="remove-term-cross" onclick="removeTerm(${idx})">✕</button>
                            </div>
                        </div>
                        
                        <div class="fee-select-row">
                            <select id="term_fee_select_${idx}">
                                ${feeOptionsHtml}
                            </select>
                            <input type="number" id="term_fee_amount_${idx}" placeholder="Amount" step="0.01">
                            <button type="button" class="add-fee-btn" onclick="addFeeToTerm(${idx})">+</button>
                        </div>
                        ${feesHtml}
                    </div>
                `;
            });
            container.innerHTML = html;
            
            const totalAllocated = getTotalAllocatedAmount();
            const schoolTotalSpan = document.getElementById('schoolTotalAmountSpan');
            if (schoolTotalSpan) {
                schoolTotalSpan.innerHTML = `₹${schoolTotalAmount.toLocaleString('en-IN')} (Allocated: ₹${totalAllocated.toLocaleString('en-IN')})`;
                if (Math.abs(totalAllocated - schoolTotalAmount) > 0.01) {
                    schoolTotalSpan.style.color = '#dc3545';
                } else {
                    schoolTotalSpan.style.color = '#28a745';
                }
            }
            
            terms.forEach((term, idx) => {
                const dateInput = document.getElementById(`due_date_${idx}`);
                if (dateInput) {
                    flatpickr(dateInput, {
                        dateFormat: "d-m-Y",
                        allowInput: true,
                        onChange: function(selectedDates, dateStr, instance) {
                            if (dateStr) {
                                const formattedDate = formatDateForDatabase(dateStr);
                                updateTermDueDate(idx, formattedDate);
                                toastr.success(`Due date set to ${dateStr}`);
                            } else {
                                updateTermDueDate(idx, null);
                            }
                        }
                    });
                    if (term.due_date && term.due_date !== '0000-00-00') {
                        dateInput.value = formatDateForDisplay(term.due_date);
                    }
                }
            });
        }
        
        async function loadStudentFeeDetails(page = 1) {
            const schoolId = document.getElementById('filter_school_id')?.value || '';
            const academicYear = document.getElementById('filter_academic_year')?.value || '';
            const classId = document.getElementById('filter_class_id')?.value || '';
            
            if (!schoolId && !academicYear && !classId) {
                document.getElementById('infoMessage').style.display = 'block';
                document.getElementById('loadingDiv').style.display = 'none';
                document.getElementById('tableContainer').style.display = 'none';
                return;
            }
            
            document.getElementById('infoMessage').style.display = 'none';
            document.getElementById('loadingDiv').style.display = 'block';
            document.getElementById('tableContainer').style.display = 'none';
            
            currentPage = page;
            let url = `${API_BASE}student_fee_summary_grouped.php?page=${page}&limit=${perPage}`;
            if (schoolId) url += `&school_id=${schoolId}`;
            if (academicYear) url += `&academic_year=${academicYear}`;
            if (classId) url += `&class_id=${classId}`;
            
            try {
                const res = await fetch(url);
                const data = await res.json();
                const tbody = document.getElementById('studentFeeList');
                
                if (data.success && data.data && data.data.length > 0) {
                    totalPages = data.total_pages;
                    totalRows = data.total_rows;
                    
                    tbody.innerHTML = data.data.map((student, index) => {
                        const startIndex = ((currentPage - 1) * perPage) + 1;
                        const sn = startIndex + index;
                        
                        let statusClass = '';
                        let statusText = '';
                        const pending = parseFloat(student.pending_amount);
                        const paid = parseFloat(student.paid_amount);
                        const total = parseFloat(student.total_amount);
                        
                        if (pending <= 0 && total > 0) {
                            statusClass = 'status-paid';
                            statusText = 'Paid';
                        } else if (paid > 0 && pending > 0) {
                            statusClass = 'status-partial';
                            statusText = 'Partial';
                        } else {
                            statusClass = 'status-pending';
                            statusText = 'Pending';
                        }
                        
                        const termsPaid = student.terms_paid || 0;
                        const termsTotal = student.terms_total || 1;
                        const termsDisplay = `${termsPaid}/${termsTotal}`;
                        
                        return `
                            <tr>
                                <td>${sn}</td>
                                <td><strong>${escapeHtml(student.student_name)}</strong><br><small style="color:#666;">ID: ${student.student_id}</small></small></td>
                                <td>${student.mobile_number || '-'}</small></td>
                                <td>₹ ${formatNumber(total)}</small></td>
                                <td>₹ ${formatNumber(paid)}</small></td>
                                <td class="${pending > 0 ? 'amount-due' : ''}">₹ ${formatNumber(pending)}</small></td>
                                <td><span class="term-badge">${termsDisplay}</span></small></td>
                                <td><span class="${statusClass}">${statusText}</span></small></td>
                                <td>
                                    <button class="action-btn" onclick="viewStudentAllTerms(${student.student_id}, '${escapeHtml(student.student_name)}')">👁️ View</button>
                                    <button class="action-btn delete" onclick="deleteStudentFee(${student.student_id}, '${escapeHtml(student.student_name)}')">🗑️ Delete</button>
                                </small></td>
                            </tr>
                        `;
                    }).join('');
                    
                    const start = (data.current_page - 1) * perPage + 1;
                    const end = Math.min(data.current_page * perPage, totalRows);
                    document.getElementById('paginationInfo').innerHTML = `Showing ${start} to ${end} of ${totalRows} students`;
                    renderPagination(data.current_page, data.total_pages);
                } else {
                    tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;">No fee records found. Click "Assign Fees" to assign fees to students.懈</tr>';
                    totalPages = 0;
                    renderPagination(1, 0);
                }
                document.getElementById('loadingDiv').style.display = 'none';
                document.getElementById('tableContainer').style.display = 'block';
            } catch(e) {
                console.error('Error loading student fees:', e);
                toastr.error('Error loading student fee details');
                document.getElementById('loadingDiv').style.display = 'none';
            }
        }
        
        async function viewStudentAllTerms(studentId, studentName) {
            let termsData = null;
            let studentSchoolId = null;
            let originalSchoolFees = [];
            let schoolTotalFeeForStudent = 0;
            let editedAmounts = {};
            let newTermFees = [];
            let currentRemainingBalance = 0;
            let currentStudentId = studentId;
            let currentStudentName = studentName;
            let termPaymentStatus = {};
            
            async function loadTermsData() {
                const res = await fetch(`${API_BASE}student_all_terms_details.php?student_id=${studentId}`);
                const data = await res.json();
                if (data.success && data.data) {
                    termsData = data.data;
                    schoolTotalFeeForStudent = 0;
                    termsData.forEach(term => {
                        schoolTotalFeeForStudent += roundToTwo(parseFloat(term.total_amount));
                    });
                }
                return termsData;
            }
            
            async function loadTermPaymentStatus() {
                if (!termsData) return;
                for (const term of termsData) {
                    const res = await fetch(`${API_BASE}student_term_payment_status.php?student_id=${studentId}&term_name=${encodeURIComponent(term.term_name)}`);
                    const data = await res.json();
                    if (data.success) {
                        termPaymentStatus[term.term_name] = data.is_paid;
                    }
                }
            }
            
            async function loadStudentInfo() {
                const res = await fetch(`${API_BASE}user_details.php?user_id=${studentId}`);
                const data = await res.json();
                if (data.success) {
                    studentSchoolId = data.data.school_id;
                }
                return studentSchoolId;
            }
            
            async function loadOriginalSchoolFees() {
                if (!studentSchoolId) await loadStudentInfo();
                if (!studentSchoolId) return [];
                try {
                    const res = await fetch(API_BASE + `fee_structure_grouped.php?school_id=${studentSchoolId}`);
                    const data = await res.json();
                    if (data.success && data.data) {
                        let fees = [];
                        data.data.forEach(group => {
                            group.fees.forEach(fee => {
                                fees.push({
                                    fee_name: fee.fee_name,
                                    amount: roundToTwo(fee.amount),
                                    group_id: group.group_id,
                                    group_name: group.group_name
                                });
                            });
                        });
                        originalSchoolFees = fees;
                        return fees;
                    }
                    return [];
                } catch(e) {
                    console.error('Error loading fees:', e);
                    return [];
                }
            }
            
            function getCurrentTotalAllocated() {
                let total = 0;
                if (termsData) {
                    termsData.forEach(term => {
                        let termTotal = 0;
                        if (term.fees) {
                            term.fees.forEach(fee => {
                                const key = `${term.term_name}_${fee.fee_name}`;
                                const editedAmount = editedAmounts[key];
                                termTotal += editedAmount !== undefined ? editedAmount : parseFloat(fee.amount);
                            });
                        }
                        total += termTotal;
                    });
                }
                return roundToTwo(total);
            }
            
            function getRemainingBalance() {
                const currentTotal = getCurrentTotalAllocated();
                currentRemainingBalance = roundToTwo(schoolTotalFeeForStudent - currentTotal);
                return Math.max(0, currentRemainingBalance);
            }
            
            function getRemainingAmountForFee(feeName, includeNewTerm = true) {
                const fee = originalSchoolFees.find(f => f.fee_name === feeName);
                if (!fee) return 0;
                
                let allocated = 0;
                if (termsData) {
                    termsData.forEach(term => {
                        term.fees.forEach(existingFee => {
                            if (existingFee.fee_name === feeName) {
                                const key = `${term.term_name}_${feeName}`;
                                const editedAmount = editedAmounts[key];
                                allocated += editedAmount !== undefined ? editedAmount : parseFloat(existingFee.amount);
                            }
                        });
                    });
                }
                if (includeNewTerm) {
                    newTermFees.forEach(f => {
                        if (f.fee_name === feeName) allocated += f.amount;
                    });
                }
                
                return Math.max(0, roundToTwo(fee.amount - allocated));
            }
            
            function updateRemainingBalanceDisplay() {
                const remaining = getRemainingBalance();
                const currentTotalSpan = document.getElementById('currentTotalSpan');
                const remainingDisplaySpan = document.getElementById('remainingBalanceDisplay');
                const createTermBtn = document.querySelector('.create-term-btn-right');
                
                if (currentTotalSpan) currentTotalSpan.textContent = `₹${getCurrentTotalAllocated().toLocaleString('en-IN')}`;
                if (remainingDisplaySpan) remainingDisplaySpan.textContent = `₹${remaining.toLocaleString('en-IN')}`;
                
                if (createTermBtn) {
                    if (remaining > 0) {
                        createTermBtn.disabled = false;
                        createTermBtn.style.opacity = '1';
                        createTermBtn.style.cursor = 'pointer';
                    } else {
                        createTermBtn.disabled = true;
                        createTermBtn.style.opacity = '0.5';
                        createTermBtn.style.cursor = 'not-allowed';
                    }
                }
            }
            
            window.updateFeeAmountEdit = function(input, key, originalAmount) {
                let newAmount = roundToTwo(parseFloat(input.value));
                if (isNaN(newAmount) || newAmount < 0) {
                    newAmount = 0;
                }
                editedAmounts[key] = newAmount;
                updateRemainingBalanceDisplay();
                input.value = newAmount;
            };
            
            window.saveAllChanges = async function(sId) {
                const updates = [];
                
                document.querySelectorAll('.edit-term-due').forEach(input => {
                    const termCard = input.closest('.edit-term-card');
                    if (termCard && !termCard.classList.contains('term-paid')) {
                        const termName = termCard.querySelector('.edit-term-title').textContent.replace('📖 ', '');
                        let dueDate = input.value;
                        if (dueDate) {
                            dueDate = formatDateForDatabase(dueDate);
                            updates.push({ type: 'due_date', term_name: termName, due_date: dueDate });
                        }
                    }
                });
                
                document.querySelectorAll('.edit-fee-amount').forEach(input => {
                    const termCard = input.closest('.edit-term-card');
                    if (termCard && !termCard.classList.contains('term-paid')) {
                        const newAmount = roundToTwo(parseFloat(input.value));
                        const originalAmount = roundToTwo(parseFloat(input.getAttribute('data-original-amount')));
                        const feeId = parseInt(input.getAttribute('data-fee-id'));
                        if (Math.abs(newAmount - originalAmount) > 0.01) {
                            updates.push({ type: 'amount', fee_id: feeId, amount: newAmount });
                        }
                    }
                });
                
                const dueDateInput = document.getElementById('new_term_due_date_create');
                const newTermDueDate = dueDateInput ? dueDateInput.value : '';
                let newTermData = null;
                
                if (newTermFees.length > 0) {
                    if (!newTermDueDate || newTermDueDate === '') {
                        toastr.error('Please set due date for the new term');
                        return;
                    }
                    const currentTermsCount = termsData?.length || 0;
                    const termName = `Term ${currentTermsCount + 1}`;
                    const formattedDueDate = formatDateForDatabase(newTermDueDate);
                    newTermData = {
                        term_name: termName,
                        due_date: formattedDueDate,
                        fees: newTermFees
                    };
                }
                
                if (updates.length === 0 && !newTermData) {
                    toastr.info('No changes to save');
                    return;
                }
                
                Swal.fire({ title: 'Saving Changes...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                
                const requestData = {
                    student_id: sId,
                    updates: updates,
                    new_term: newTermData
                };
                
                const res = await fetch(API_BASE + 'student_fee_bulk_update_with_term.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(requestData)
                });
                const response = await res.json();
                
                if (response.success) {
                    Swal.fire({ icon: 'success', title: 'Saved!', text: response.message });
                    await loadTermsData();
                    await loadOriginalSchoolFees();
                    await loadTermPaymentStatus();
                    editedAmounts = {};
                    newTermFees = [];
                    
                    const viewContent = await loadViewMode();
                    const editContent = await loadEditMode();
                    
                    document.getElementById('termViewContent').innerHTML = editContent;
                    
                    document.querySelectorAll('.edit-term-due').forEach(input => {
                        if (!input.disabled) {
                            flatpickr(input, { dateFormat: "d-m-Y", allowInput: true });
                        }
                    });
                    
                    updateRemainingBalanceDisplay();
                    loadStudentFeeDetails(currentPage);
                } else {
                    Swal.fire({ icon: 'error', title: 'Failed', text: response.message });
                }
            };
            
            window.deleteTermFromStudent = async function(sId, termName) {
                if (termPaymentStatus[termName]) {
                    toastr.error('Cannot delete a paid term');
                    return;
                }
                
                const result = await Swal.fire({ title: 'Delete Term?', text: `Delete "${termName}" and all its fees?`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete!' });
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    const res = await fetch(API_BASE + 'student_term_delete.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ student_id: sId, term_name: termName }) });
                    const response = await res.json();
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Deleted!', text: response.message });
                        await loadTermsData();
                        await loadOriginalSchoolFees();
                        await loadTermPaymentStatus();
                        editedAmounts = {};
                        newTermFees = [];
                        const editContent = await loadEditMode();
                        document.getElementById('termViewContent').innerHTML = editContent;
                        document.querySelectorAll('.edit-term-due').forEach(input => {
                            if (!input.disabled) {
                                flatpickr(input, { dateFormat: "d-m-Y", allowInput: true });
                            }
                        });
                        loadStudentFeeDetails(currentPage);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Failed', text: response.message });
                    }
                }
            };
            
            window.deleteFeeFromStudent = async function(sId, feeId) {
                const result = await Swal.fire({ title: 'Delete Fee?', text: 'Delete this fee?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete!' });
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    const res = await fetch(API_BASE + 'student_fee_delete_single.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ fee_id: feeId }) });
                    const response = await res.json();
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Deleted!', text: response.message });
                        await loadTermsData();
                        await loadOriginalSchoolFees();
                        await loadTermPaymentStatus();
                        editedAmounts = {};
                        newTermFees = [];
                        const editContent = await loadEditMode();
                        document.getElementById('termViewContent').innerHTML = editContent;
                        document.querySelectorAll('.edit-term-due').forEach(input => {
                            if (!input.disabled) {
                                flatpickr(input, { dateFormat: "d-m-Y", allowInput: true });
                            }
                        });
                        loadStudentFeeDetails(currentPage);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Failed', text: response.message });
                    }
                }
            };
            
            async function processCashPayment(termName, amount) {
                Swal.fire({ 
                    title: 'Recording Payment...', 
                    allowOutsideClick: false, 
                    didOpen: () => Swal.showLoading() 
                });
                
                try {
                    const paymentRes = await fetch(API_BASE + 'record_term_payment.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            student_id: currentStudentId,
                            term_name: termName,
                            amount: amount,
                            payment_method: 'cash',
                            transaction_id: 'CASH_' + Date.now()
                        })
                    });
                    const paymentData = await paymentRes.json();
                    
                    if (paymentData.success) {
                        Swal.fire({ 
                            icon: 'success', 
                            title: 'Payment Successful!', 
                            text: `Cash payment of ₹${amount.toLocaleString('en-IN')} has been recorded successfully.` 
                        });
                        
                        closePanel();
                        
                        await loadTermsData();
                        await loadTermPaymentStatus();
                        const viewContent = await loadViewMode();
                        const editContent = await loadEditMode();
                        document.getElementById('termViewContent').innerHTML = editContent;
                        loadStudentFeeDetails(currentPage);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Payment Failed', text: paymentData.message });
                    }
                } catch(error) {
                    console.error('Payment error:', error);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.' });
                }
            }
            
            async function processRazorpayPayment(termName, amount) {
                Swal.fire({
                    title: 'Creating Order...',
                    text: 'Please wait while we prepare your payment.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                try {
                    const orderRes = await fetch(API_BASE + 'create_razorpay_order.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            student_id: currentStudentId,
                            term_name: termName,
                            amount: amount
                        })
                    });
                    const orderData = await orderRes.json();
                    
                    if (!orderData.success) {
                        Swal.fire({ icon: 'error', title: 'Error', text: orderData.message });
                        return;
                    }
                    
                    Swal.close();
                    
                    // Your Razorpay Test Keys
                    const RAZORPAY_KEY_ID = 'rzp_test_SwJ0242iPOlpXS';
                    
                    const options = {
                        key: RAZORPAY_KEY_ID,
                        amount: orderData.amount,
                        currency: orderData.currency,
                        name: 'Tutorix',
                        description: `Payment for ${termName}`,
                        order_id: orderData.order_id,
                        handler: async function(response) {
                            Swal.fire({ title: 'Verifying Payment...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                            
                            const verifyRes = await fetch(API_BASE + 'verify_razorpay_payment.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({
                                    order_id: response.razorpay_order_id,
                                    payment_id: response.razorpay_payment_id,
                                    signature: response.razorpay_signature,
                                    student_id: currentStudentId,
                                    term_name: termName,
                                    amount: amount,
                                    payment_method: 'online'
                                })
                            });
                            const verifyData = await verifyRes.json();
                            
                            if (verifyData.success) {
                                Swal.fire({ icon: 'success', title: 'Payment Successful!', text: 'Your online payment has been recorded.' });
                                closePanel();
                                await loadTermsData();
                                await loadTermPaymentStatus();
                                const viewContent = await loadViewMode();
                                const editContent = await loadEditMode();
                                document.getElementById('termViewContent').innerHTML = editContent;
                                loadStudentFeeDetails(currentPage);
                            } else {
                                Swal.fire({ icon: 'error', title: 'Payment Failed', text: verifyData.message });
                            }
                        },
                        prefill: {
                            name: orderData.student_name,
                            email: orderData.student_email,
                            contact: orderData.student_mobile
                        },
                        theme: {
                            color: '#4361ee'
                        },
                        modal: {
                            ondismiss: function() {
                                Swal.fire('Payment cancelled', 'You cancelled the payment process', 'info');
                            }
                        }
                    };
                    
                    const razorpay = new Razorpay(options);
                    razorpay.open();
                    
                } catch (error) {
                    console.error('Payment error:', error);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.' });
                }
            }
            
            window.showPaymentForm = function(termName, totalAmount, pendingAmount) {
                const paymentFormHtml = `
                    <div class="payment-form-container">
                        <h3 style="margin-bottom: 20px; color: #4361ee;">💰 Payment Details</h3>
                        <div class="payment-detail-row">
                            <span class="payment-detail-label">Student ID:</span>
                            <span class="payment-detail-value">${currentStudentId}</span>
                        </div>
                        <div class="payment-detail-row">
                            <span class="payment-detail-label">Student Name:</span>
                            <span class="payment-detail-value">${escapeHtml(currentStudentName)}</span>
                        </div>
                        <div class="payment-detail-row">
                            <span class="payment-detail-label">Term Name:</span>
                            <span class="payment-detail-value">${escapeHtml(termName)}</span>
                        </div>
                        <div class="payment-detail-row">
                            <span class="payment-detail-label">Total Amount:</span>
                            <span class="payment-detail-value">₹ ${formatNumber(totalAmount)}</span>
                        </div>
                        <div class="payment-detail-row">
                            <span class="payment-detail-label">Pending Amount:</span>
                            <span class="payment-detail-value payment-amount">₹ ${formatNumber(pendingAmount)}</span>
                        </div>
                        <div class="payment-detail-row">
                            <span class="payment-detail-label">Amount to Pay:</span>
                            <span class="payment-detail-value payment-amount">₹ ${formatNumber(pendingAmount)}</span>
                        </div>
                        <div style="margin-top: 20px;">
                            <label style="display: block; font-weight: 600; margin-bottom: 5px;">Payment Method:</label>
                            <select id="payment_method_select" class="form-input">
                                <option value="cash">💵 Cash</option>
                                <option value="bank_transfer">🏦 Bank Transfer</option>
                                <option value="card">💳 Card</option>
                                <option value="online">🌐 Online Payment (Razorpay)</option>
                            </select>
                        </div>
                        <div id="transaction_div" style="margin-top: 15px; display: none;">
                            <label style="display: block; font-weight: 600; margin-bottom: 5px;">Transaction ID / Reference Number:</label>
                            <input type="text" id="transaction_id_input" class="form-input" placeholder="Enter transaction ID or reference number">
                        </div>
                        <div id="online_note" class="online-payment-note" style="display: none;">
                            🔒 You will be redirected to Razorpay secure payment gateway for online payment.
                        </div>
                        <div style="margin-top: 25px; display: flex; gap: 10px;">
                            <button onclick="closePanel()" class="btn-secondary" style="flex: 1; padding: 12px;">Cancel</button>
                            <button onclick="processPayment('${termName}', ${pendingAmount})" class="btn-success" style="flex: 1; padding: 12px;">💳 Make Payment</button>
                        </div>
                    </div>
                `;
                
                openPanel(`Payment - ${escapeHtml(termName)}`, paymentFormHtml, null, '550px', '💰');
                
                setTimeout(() => {
                    const paymentMethodSelect = document.getElementById('payment_method_select');
                    const transactionDiv = document.getElementById('transaction_div');
                    const onlineNote = document.getElementById('online_note');
                    
                    if (paymentMethodSelect) {
                        paymentMethodSelect.addEventListener('change', function() {
                            const selectedMethod = this.value;
                            if (selectedMethod === 'cash') {
                                if (transactionDiv) transactionDiv.style.display = 'none';
                                if (onlineNote) onlineNote.style.display = 'none';
                            } else if (selectedMethod === 'bank_transfer' || selectedMethod === 'card') {
                                if (transactionDiv) transactionDiv.style.display = 'block';
                                if (onlineNote) onlineNote.style.display = 'none';
                            } else if (selectedMethod === 'online') {
                                if (transactionDiv) transactionDiv.style.display = 'block';
                                if (onlineNote) onlineNote.style.display = 'block';
                            }
                        });
                    }
                }, 100);
            };
            
            window.processPayment = async function(termName, amount) {
                const paymentMethod = document.getElementById('payment_method_select')?.value || 'cash';
                const transactionId = document.getElementById('transaction_id_input')?.value || null;
                
                const confirmResult = await Swal.fire({
                    title: 'Confirm Payment',
                    html: `
                        <div style="text-align: left;">
                            <p><strong>Term:</strong> ${escapeHtml(termName)}</p>
                            <p><strong>Amount:</strong> ₹${amount.toLocaleString('en-IN')}</p>
                            <p><strong>Payment Method:</strong> ${paymentMethod.toUpperCase()}</p>
                            ${transactionId ? `<p><strong>Transaction ID:</strong> ${escapeHtml(transactionId)}</p>` : ''}
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Yes, Confirm Payment',
                    cancelButtonText: 'Cancel'
                });
                
                if (!confirmResult.isConfirmed) {
                    return;
                }
                
                if (paymentMethod === 'cash') {
                    await processCashPayment(termName, amount);
                } else if (paymentMethod === 'online') {
                    await processRazorpayPayment(termName, amount);
                } else {
                    Swal.fire({ 
                        title: 'Recording Payment...', 
                        allowOutsideClick: false, 
                        didOpen: () => Swal.showLoading() 
                    });
                    
                    try {
                        const paymentRes = await fetch(API_BASE + 'record_term_payment.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                student_id: currentStudentId,
                                term_name: termName,
                                amount: amount,
                                payment_method: paymentMethod,
                                transaction_id: transactionId || 'TXN_' + Date.now()
                            })
                        });
                        const paymentData = await paymentRes.json();
                        
                        if (paymentData.success) {
                            Swal.fire({ 
                                icon: 'success', 
                                title: 'Payment Successful!', 
                                text: `Payment of ₹${amount.toLocaleString('en-IN')} has been recorded successfully.` 
                            });
                            
                            closePanel();
                            
                            await loadTermsData();
                            await loadTermPaymentStatus();
                            const viewContent = await loadViewMode();
                            const editContent = await loadEditMode();
                            document.getElementById('termViewContent').innerHTML = editContent;
                            loadStudentFeeDetails(currentPage);
                        } else {
                            Swal.fire({ icon: 'error', title: 'Payment Failed', text: paymentData.message });
                        }
                    } catch(error) {
                        console.error('Payment error:', error);
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.' });
                    }
                }
            };
            
            window.showCreateTermFormHandler = function() {
                const remainingBalance = getRemainingBalance();
                
                if (remainingBalance <= 0) {
                    toastr.warning('No remaining balance available to create a new term. Please reduce amounts in existing terms first.');
                    return;
                }
                
                const createTermDiv = document.getElementById('createTermDiv');
                if (createTermDiv) {
                    createTermDiv.classList.add('show');
                    
                    const dueDateInput = document.getElementById('new_term_due_date_create');
                    if (dueDateInput) {
                        if (dueDateInput._flatpickr) {
                            dueDateInput._flatpickr.destroy();
                        }
                        flatpickr(dueDateInput, { dateFormat: "d-m-Y", allowInput: true });
                    }
                    
                    const feeSelect = document.getElementById('new_term_fee_select_create');
                    if (feeSelect && originalSchoolFees.length > 0) {
                        let newOptions = '<option value="">-- Select Fee --</option>';
                        originalSchoolFees.forEach(fee => {
                            const remainingAmount = getRemainingAmountForFee(fee.fee_name, true);
                            if (remainingAmount > 0 && remainingBalance > 0) {
                                newOptions += `<option value="${fee.fee_name}" data-group-id="${fee.group_id}" data-group-name="${fee.group_name}" data-amount="${fee.amount}">${escapeHtml(fee.fee_name)} (${escapeHtml(fee.group_name)}) - Remaining: ₹${remainingAmount}</option>`;
                            }
                        });
                        feeSelect.innerHTML = newOptions;
                    }
                    
                    newTermFees = [];
                    const feesListContainer = document.getElementById('new_term_fees_list_create');
                    if (feesListContainer) {
                        feesListContainer.innerHTML = '<div style="color:#999; text-align:center; padding:10px;">No fees added yet</div>';
                    }
                    
                    const amountInput = document.getElementById('new_term_fee_amount_create');
                    if (amountInput) amountInput.value = '';
                }
            };
            
            window.cancelCreateTerm = function() {
                const createTermDiv = document.getElementById('createTermDiv');
                if (createTermDiv) {
                    createTermDiv.classList.remove('show');
                }
                newTermFees = [];
                const feesListContainer = document.getElementById('new_term_fees_list_create');
                if (feesListContainer) {
                    feesListContainer.innerHTML = '<div style="color:#999; text-align:center; padding:10px;">No fees added yet</div>';
                }
                const amountInput = document.getElementById('new_term_fee_amount_create');
                if (amountInput) amountInput.value = '';
                const feeSelect = document.getElementById('new_term_fee_select_create');
                if (feeSelect) feeSelect.value = '';
            };
            
            window.addFeeToNewTermCreate = function() {
                const remainingBalance = getRemainingBalance();
                
                if (remainingBalance <= 0) {
                    toastr.warning('No remaining balance available. Please reduce amounts in existing terms first.');
                    return;
                }
                
                const feeSelect = document.getElementById('new_term_fee_select_create');
                const amountInput = document.getElementById('new_term_fee_amount_create');
                const selectedOption = feeSelect ? feeSelect.options[feeSelect.selectedIndex] : null;
                
                if (!feeSelect || !feeSelect.value) {
                    toastr.warning('Please select a fee');
                    return;
                }
                
                let amount = roundToTwo(parseFloat(amountInput.value));
                if (isNaN(amount) || amount <= 0) {
                    toastr.warning('Please enter a valid amount');
                    return;
                }
                
                const feeName = feeSelect.value;
                const groupId = selectedOption ? selectedOption.getAttribute('data-group-id') : null;
                const groupName = selectedOption ? selectedOption.getAttribute('data-group-name') : '';
                const maxAmount = getRemainingAmountForFee(feeName, true);
                
                if (amount > maxAmount + 0.01) {
                    toastr.error(`Amount cannot exceed remaining amount of ₹${maxAmount.toLocaleString('en-IN')} for this fee`);
                    return;
                }
                
                const currentNewTermTotal = newTermFees.reduce((sum, f) => sum + f.amount, 0);
                if (currentNewTermTotal + amount > remainingBalance + 0.01) {
                    toastr.error(`Adding this fee would exceed the remaining balance of ₹${remainingBalance.toLocaleString('en-IN')}`);
                    return;
                }
                
                const existingFee = newTermFees.find(f => f.fee_name === feeName);
                if (existingFee) {
                    toastr.warning('This fee is already added');
                    return;
                }
                
                newTermFees.push({
                    fee_name: feeName,
                    amount: amount,
                    group_id: groupId,
                    group_name: groupName
                });
                
                const container = document.getElementById('new_term_fees_list_create');
                if (container) {
                    if (newTermFees.length === 0) {
                        container.innerHTML = '<div style="color:#999; text-align:center; padding:10px;">No fees added yet</div>';
                    } else {
                        let html = '';
                        newTermFees.forEach((fee, idx) => {
                            html += `
                                <div class="fee-item">
                                    <div>
                                        <div style="font-weight: 600;">${escapeHtml(fee.fee_name)}</div>
                                        <div style="font-size: 12px; color: #666;">${escapeHtml(fee.group_name)}</div>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        <span style="font-weight: 700; color: #4361ee;">₹${fee.amount.toLocaleString('en-IN')}</span>
                                        <button type="button" class="remove-fee-cross" onclick="removeNewTermFeeCreate(${idx})">✕</button>
                                    </div>
                                </div>
                            `;
                        });
                        container.innerHTML = html;
                    }
                }
                
                if (feeSelect) feeSelect.value = '';
                if (amountInput) amountInput.value = '';
                toastr.success('Fee added to new term');
            };
            
            window.removeNewTermFeeCreate = function(index) {
                newTermFees.splice(index, 1);
                const container = document.getElementById('new_term_fees_list_create');
                if (container) {
                    if (newTermFees.length === 0) {
                        container.innerHTML = '<div style="color:#999; text-align:center; padding:10px;">No fees added yet</div>';
                    } else {
                        let html = '';
                        newTermFees.forEach((fee, idx) => {
                            html += `
                                <div class="fee-item">
                                    <div>
                                        <div style="font-weight: 600;">${escapeHtml(fee.fee_name)}</div>
                                        <div style="font-size: 12px; color: #666;">${escapeHtml(fee.group_name)}</div>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        <span style="font-weight: 700; color: #4361ee;">₹${fee.amount.toLocaleString('en-IN')}</span>
                                        <button type="button" class="remove-fee-cross" onclick="removeNewTermFeeCreate(${idx})">✕</button>
                                    </div>
                                </div>
                            `;
                        });
                        container.innerHTML = html;
                    }
                }
                toastr.info('Fee removed');
            };
            
            async function loadViewMode() {
                if (!termsData) await loadTermsData();
                await loadTermPaymentStatus();
                
                if (!termsData || termsData.length === 0) {
                    return '<div style="text-align:center; padding:40px;">No fee details found for this student.</div>';
                }
                
                let termsHtml = '<div class="term-details-container">';
                for (const term of termsData) {
                    const totalAmount = roundToTwo(parseFloat(term.total_amount));
                    const paidAmount = roundToTwo(parseFloat(term.paid_amount));
                    const pendingAmount = roundToTwo(totalAmount - paidAmount);
                    const isPaid = termPaymentStatus[term.term_name] || (pendingAmount <= 0 && totalAmount > 0);
                    
                    let dueDateDisplay = 'Not Set';
                    if (term.due_date && term.due_date !== '0000-00-00' && term.due_date !== 'Not Set') {
                        dueDateDisplay = formatDateForDisplay(term.due_date);
                    }
                    
                    termsHtml += `
                        <div class="term-detail-card ${isPaid ? 'term-paid' : ''}">
                            <div class="term-detail-header">
                                <div>
                                    <div class="term-name">📖 ${escapeHtml(term.term_name)}</div>
                                    <div class="term-due">Due: ${dueDateDisplay}</div>
                                </div>
                                ${isPaid ? '<span class="term-paid-badge">✓ PAID</span>' : ''}
                            </div>
                            <div class="term-stats-row">
                                <div class="stat-box"><div class="stat-label">Total</div><div class="stat-value total">₹ ${formatNumber(totalAmount)}</div></div>
                                <div class="stat-box"><div class="stat-label">Paid</div><div class="stat-value paid">₹ ${formatNumber(paidAmount)}</div></div>
                                <div class="stat-box"><div class="stat-label">Pending</div><div class="stat-value pending">₹ ${formatNumber(pendingAmount)}</div></div>
                            </div>
                    `;
                    
                    const feesByGroup = {};
                    if (term.fees && term.fees.length > 0) {
                        term.fees.forEach(fee => {
                            const groupName = fee.group_name || `Group ${fee.group_id || 1}`;
                            if (!feesByGroup[groupName]) feesByGroup[groupName] = [];
                            feesByGroup[groupName].push(fee);
                        });
                        
                        for (const groupName in feesByGroup) {
                            const groupFees = feesByGroup[groupName];
                            const groupTotal = roundToTwo(groupFees.reduce((sum, f) => sum + parseFloat(f.amount), 0));
                            const groupPaid = roundToTwo(groupFees.reduce((sum, f) => sum + parseFloat(f.paid_amount || 0), 0));
                            
                            termsHtml += `<div class="fee-group-section"><div class="fee-group-title">📁 ${escapeHtml(groupName)} (Total: ₹${formatNumber(groupTotal)} | Paid: ₹${formatNumber(groupPaid)})</div>`;
                            groupFees.forEach(fee => {
                                const feeAmount = roundToTwo(parseFloat(fee.amount));
                                const feePaid = roundToTwo(parseFloat(fee.paid_amount || 0));
                                const feePending = roundToTwo(feeAmount - feePaid);
                                termsHtml += `<div class="fee-row-item"><span class="fee-name-item">${escapeHtml(fee.fee_name)}</span><div><span class="fee-amount-item">₹ ${formatNumber(feeAmount)}</span>${feePaid > 0 ? `<span class="fee-paid-item"> (Paid: ₹${formatNumber(feePaid)})</span>` : ''}${feePending > 0 ? `<span class="fee-pending-item"> (Pending: ₹${formatNumber(feePending)})</span>` : ''}</div></div>`;
                            });
                            termsHtml += `</div>`;
                        }
                    }
                    
                    if (!isPaid && pendingAmount > 0) {
                        termsHtml += `<div style="padding: 15px 20px; text-align: right; border-top: 1px solid #e0e0e0;">
                                        <button class="pay-btn" onclick="showPaymentForm('${escapeHtml(term.term_name)}', ${totalAmount}, ${pendingAmount})">💰 Pay Now - ₹${formatNumber(pendingAmount)}</button>
                                      </div>`;
                    }
                    
                    termsHtml += `</div>`;
                }
                termsHtml += '</div>';
                return termsHtml;
            }
            
            async function loadEditMode() {
                if (!termsData) await loadTermsData();
                if (!originalSchoolFees.length) await loadOriginalSchoolFees();
                await loadTermPaymentStatus();
                
                if (!termsData || termsData.length === 0) {
                    return '<div style="text-align:center; padding:40px;">No fee details found for this student.</div>';
                }
                
                editedAmounts = {};
                newTermFees = [];
                const remainingBalance = getRemainingBalance();
                
                let editHtml = `
                    <div class="term-summary-right">
                        <div class="summary-stats">
                            <div class="summary-stat">📊 School Total Fee: <strong>₹${formatNumber(schoolTotalFeeForStudent)}</strong></div>
                            <div class="summary-stat">💰 Current Total: <strong id="currentTotalSpan">₹${formatNumber(getCurrentTotalAllocated())}</strong></div>
                            <div class="summary-stat">📋 Remaining: <strong id="remainingBalanceDisplay">₹${formatNumber(remainingBalance)}</strong></div>
                        </div>
                        <div class="create-term-wrapper">
                            <button class="create-term-btn-right" onclick="showCreateTermFormHandler()" ${remainingBalance <= 0 ? 'disabled' : ''}>
                                ➕ Create New Term
                            </button>
                        </div>
                    </div>
                    <div id="createTermDiv" class="create-term-section">
                        <div class="simplified-term-form">
                            <div class="simplified-term-title">Create New Term</div>
                            <div class="new-term-row">
                                <input type="text" id="new_term_due_date_create" class="new-term-due" placeholder="Due Date (DD-MM-YYYY)" style="width: 150px;">
                            </div>
                            <div class="new-term-row">
                                <select id="new_term_fee_select_create" style="flex: 2;">
                                    <option value="">-- Select Fee --</option>
                                </select>
                                <input type="number" id="new_term_fee_amount_create" placeholder="Amount" step="0.01" style="flex: 1;">
                                <button type="button" class="add-fee-btn" onclick="addFeeToNewTermCreate()">+</button>
                            </div>
                            <div id="new_term_fees_list_create" class="fee-items-list" style="margin-top: 15px;"></div>
                            <div style="display: flex; gap: 10px; margin-top: 15px;">
                                <button type="button" class="btn-secondary" onclick="cancelCreateTerm()" style="flex:1;">Cancel</button>
                            </div>
                        </div>
                    </div>
                    <div class="term-details-container" id="editTermsContainer">
                `;
                
                for (const term of termsData) {
                    let dueDateDisplay = '';
                    if (term.due_date && term.due_date !== '0000-00-00' && term.due_date !== 'Not Set') {
                        dueDateDisplay = formatDateForDisplay(term.due_date);
                    }
                    const isPaid = termPaymentStatus[term.term_name];
                    const paidClass = isPaid ? 'term-paid' : '';
                    
                    editHtml += `
                        <div class="edit-term-card ${paidClass}" data-term-name="${escapeHtml(term.term_name)}">
                            <div class="edit-term-header">
                                <span class="edit-term-title">📖 ${escapeHtml(term.term_name)}${isPaid ? ' <span class="term-paid-badge">PAID</span>' : ''}</span>
                                <div class="edit-term-due-container">
                                    <span class="edit-term-due-label">Due Date:</span>
                                    <input type="text" class="edit-term-due" id="edit_due_${term.term_name.replace(/ /g, '_')}" value="${dueDateDisplay}" placeholder="DD-MM-YYYY" style="width: 130px;" ${isPaid ? 'disabled' : ''}>
                                    <button class="edit-delete-term" onclick="deleteTermFromStudent(${studentId}, '${escapeHtml(term.term_name)}')" ${isPaid ? 'disabled' : ''}>🗑️ Delete Term</button>
                                </div>
                            </div>
                    `;
                    
                    const feesByGroup = {};
                    if (term.fees && term.fees.length > 0) {
                        term.fees.forEach(fee => {
                            const groupName = fee.group_name || `Group ${fee.group_id || 1}`;
                            if (!feesByGroup[groupName]) feesByGroup[groupName] = [];
                            feesByGroup[groupName].push(fee);
                        });
                        
                        for (const groupName in feesByGroup) {
                            editHtml += `<div class="edit-fee-group"><div class="edit-group-title">📁 ${escapeHtml(groupName)}</div>`;
                            feesByGroup[groupName].forEach(fee => {
                                const feeId = fee.id;
                                const originalAmount = fee.amount;
                                const key = `${term.term_name}_${fee.fee_name}`;
                                const currentValue = editedAmounts[key] !== undefined ? editedAmounts[key] : originalAmount;
                                
                                editHtml += `
                                    <div class="edit-fee-row" data-fee-id="${feeId}" data-fee-name="${escapeHtml(fee.fee_name)}" data-term-name="${escapeHtml(term.term_name)}">
                                        <span class="edit-fee-name">${escapeHtml(fee.fee_name)}</span>
                                        <div>
                                            <input type="number" class="edit-fee-amount" value="${currentValue}" step="0.01" data-fee-id="${feeId}" data-original-amount="${originalAmount}" data-key="${key}" style="width: 100px;" onchange="updateFeeAmountEdit(this, '${key}', ${originalAmount})" ${isPaid ? 'disabled' : ''}>
                                            <button class="edit-delete-fee" onclick="deleteFeeFromStudent(${studentId}, ${feeId})" ${isPaid ? 'disabled' : ''}>✕</button>
                                        </div>
                                    </div>
                                `;
                            });
                            editHtml += `</div>`;
                        }
                    }
                    editHtml += `</div>`;
                }
                
                editHtml += `</div><button class="btn-save" onclick="saveAllChanges(${studentId})">💾 Save All Changes</button>`;
                return editHtml;
            }
            
            const toggleHtml = `
                <div class="view-toggle">
                    <button class="view-toggle-btn active" onclick="switchTermViewMode('view')">👁️ View Terms</button>
                    <button class="view-toggle-btn" onclick="switchTermViewMode('edit')">✏️ Edit Terms</button>
                </div>
                <div id="termViewContent"></div>
            `;
            
            openPanel(`Fee Details - ${escapeHtml(studentName)}`, toggleHtml, null, '750px', '📋');
            
            const viewContent = await loadViewMode();
            document.getElementById('termViewContent').innerHTML = viewContent;
            
            window.switchTermViewMode = async function(mode) {
                const contentDiv = document.getElementById('termViewContent');
                if (mode === 'view') {
                    const viewContent = await loadViewMode();
                    contentDiv.innerHTML = viewContent;
                } else if (mode === 'edit') {
                    const editContent = await loadEditMode();
                    contentDiv.innerHTML = editContent;
                    document.querySelectorAll('.edit-term-due').forEach(input => {
                        if (!input.disabled) {
                            flatpickr(input, { dateFormat: "d-m-Y", allowInput: true });
                        }
                    });
                }
                document.querySelectorAll('.view-toggle-btn').forEach(btn => btn.classList.remove('active'));
                if (mode === 'view') {
                    document.querySelectorAll('.view-toggle-btn')[0].classList.add('active');
                } else if (mode === 'edit') {
                    document.querySelectorAll('.view-toggle-btn')[1].classList.add('active');
                }
            };
        }
        
        async function deleteStudentFee(studentId, studentName) {
            const result = await Swal.fire({ title: 'Delete Fee Records?', text: `Delete all fee records for ${studentName}? This cannot be undone!`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete!' });
            if (result.isConfirmed) {
                Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const res = await fetch(API_BASE + 'student_fee_delete.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ student_id: studentId }) });
                const response = await res.json();
                if (response.success) {
                    Swal.fire({ icon: 'success', title: 'Deleted!', text: response.message });
                    loadStudentFeeDetails(currentPage);
                } else {
                    Swal.fire({ icon: 'error', title: 'Failed', text: response.message });
                }
            }
        }
        
        function renderPagination(currentPageNum, totalPagesNum) {
            const container = document.getElementById('paginationContainer');
            if (!container) return;
            if (!totalPagesNum || totalPagesNum <= 1) { container.innerHTML = ''; return; }
            let html = `<button class="pagination-btn" onclick="changePage(${currentPageNum - 1})" ${currentPageNum === 1 ? 'disabled' : ''}>← Prev</button>`;
            for (let i = 1; i <= totalPagesNum; i++) html += `<button class="pagination-btn ${i === currentPageNum ? 'active' : ''}" onclick="changePage(${i})">${i}</button>`;
            html += `<button class="pagination-btn" onclick="changePage(${currentPageNum + 1})" ${currentPageNum === totalPagesNum ? 'disabled' : ''}>Next →</button>`;
            html += `<select class="per-page-select" onchange="changePerPage(this.value)"><option value="10" ${perPage === 10 ? 'selected' : ''}>10 per page</option><option value="25" ${perPage === 25 ? 'selected' : ''}>25 per page</option><option value="50" ${perPage === 50 ? 'selected' : ''}>50 per page</option></select>`;
            container.innerHTML = html;
        }
        
        function changePage(page) { if (page >= 1 && page <= totalPages) loadStudentFeeDetails(page); }
        function changePerPage(value) { perPage = parseInt(value); loadStudentFeeDetails(1); }
        
        window.openAssignFees = async function() {
            terms = [];
            schoolFees = [];
            schoolTotalAmount = 0;
            currentDraftSchoolId = null;
            stopAutoSave();
            
            let schoolsHtml = '<option value="">Select School</option>';
            let schoolsData = [];
            try {
                const res = await fetch(API_BASE + 'school_list.php');
                const data = await res.json();
                if (data.success) {
                    schoolsData = data.data;
                    data.data.forEach(school => { schoolsHtml += `<option value="${school.school_id}">${escapeHtml(school.school_name)} (${school.school_code})</option>`; });
                }
            } catch(e) {}
            
            const currentYear = new Date().getFullYear();
            let academicYearOptions = '';
            for (let i = currentYear - 2; i <= currentYear + 5; i++) academicYearOptions += `<option value="${i}" ${i === currentYear ? 'selected' : ''}>${i}</option>`;
            
            const formHtml = `<form id="assignFeesForm" novalidate><div class="form-row"><div class="form-group"><label>School <span class="required">*</span></label><select id="assign_school_id" required>${schoolsHtml}</select></div><div class="form-group"><label>Academic Year <span class="required">*</span></label><select id="assign_academic_year" required>${academicYearOptions}</select></div></div><div class="form-row"><div class="form-group"><label>Class <span class="required">*</span></label><select id="assign_class_id" required><option value="">Select School first</option></select></div></div><div id="draftInfoContainer"></div><div id="totalAmountContainer" style="display:none;"><div class="amount-small-box"><span class="school-total-text">🏫 School Total Fee Amount:</span><span class="school-total-value" id="schoolTotalAmountSpan">₹0</span></div></div><div style="margin: 24px 0 16px 0;"><button type="button" class="btn btn-primary" onclick="addTerm()" style="width: auto;">+ Create Term</button><button type="button" class="btn btn-warning" onclick="clearDraft()" style="width: auto; margin-left: 10px; background: #ff9800;">🗑️ Clear Draft</button></div><div id="termsListContainer"></div><div style="display: flex; gap: 15px; margin-top: 20px;"><button type="button" onclick="closePanel()" class="btn-secondary" style="padding: 12px; flex:1;">Cancel</button><button type="submit" class="btn-submit" style="flex:1;">💰 Assign Fees to Students</button></div></form>`;
            openPanel('Assign Fees to Students', formHtml, () => { stopAutoSave(); loadStudentFeeDetails(currentPage); }, '750px', '💰');
            
            setTimeout(() => {
                const schoolSelect = document.getElementById('assign_school_id');
                const classSelect = document.getElementById('assign_class_id');
                const totalAmountContainer = document.getElementById('totalAmountContainer');
                const draftInfoContainer = document.getElementById('draftInfoContainer');
                
                window.clearDraft = function() {
                    if (currentDraftSchoolId) {
                        Swal.fire({ title: 'Clear Draft?', text: 'Delete all saved terms for this school?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, clear it!' }).then((result) => {
                            if (result.isConfirmed) {
                                clearTermsFromStorage(currentDraftSchoolId);
                                terms = []; schoolFees = []; schoolTotalAmount = 0;
                                renderTermsList();
                                if (totalAmountContainer) totalAmountContainer.style.display = 'none';
                                draftInfoContainer.innerHTML = '';
                                toastr.success('Draft cleared successfully');
                            }
                        });
                    } else { toastr.info('No draft to clear'); }
                };
                
                window.updateFeeAmount = updateFeeAmount;
                window.removeFeeFromTerm = removeFeeFromTerm;
                window.addTerm = addTerm;
                window.removeTerm = removeTerm;
                window.addFeeToTerm = addFeeToTerm;
                
                if (schoolSelect) {
                    schoolSelect.addEventListener('change', async function() {
                        const schoolId = this.value;
                        if (schoolId) {
                            await loadSchoolFees(schoolId);
                            currentDraftSchoolId = schoolId;
                            const hasDraft = checkForExistingDraft(schoolId);
                            const draftInfo = getDraftInfo(schoolId);
                            if (hasDraft && draftInfo) {
                                draftInfoContainer.innerHTML = `<div class="draft-info"><span>📝 Saved draft with ${draftInfo.termCount} term(s) from ${draftInfo.updatedAt}</span><div><button type="button" class="btn btn-success" onclick="resumeDraft()" style="padding: 6px 12px; margin-right: 8px;">📂 Resume Draft</button><button type="button" class="btn btn-danger" onclick="clearDraft()" style="padding: 6px 12px;">🗑️ Clear</button></div></div>`;
                            } else { draftInfoContainer.innerHTML = ''; }
                            if (totalAmountContainer) { 
                                totalAmountContainer.style.display = 'block'; 
                                document.getElementById('schoolTotalAmountSpan').innerHTML = `₹${schoolTotalAmount.toLocaleString('en-IN')}`;
                            }
                            classSelect.innerHTML = '<option value="">Loading classes...</option>';
                            classSelect.disabled = true;
                            try {
                                const res = await fetch(API_BASE + `classes_list.php?school_id=${schoolId}`);
                                const data = await res.json();
                                if (data.success && data.data) {
                                    const uniqueClasses = [...new Set(data.data.map(c => parseInt(c.class_id)))];
                                    uniqueClasses.sort((a, b) => a - b);
                                    classSelect.innerHTML = '<option value="">Select Class</option>';
                                    uniqueClasses.forEach(cls => { classSelect.innerHTML += `<option value="${cls}">Class ${cls}</option>`; });
                                    classSelect.disabled = false;
                                } else { classSelect.innerHTML = '<option value="">No classes found</option>'; classSelect.disabled = false; }
                            } catch(e) { classSelect.innerHTML = '<option value="">Error loading classes</option>'; classSelect.disabled = false; }
                            if (!hasDraft) { terms = []; renderTermsList(); } else { 
                                loadTermsFromStorage(schoolId);
                                renderTermsList(); 
                            }
                        } else {
                            classSelect.innerHTML = '<option value="">Select School first</option>';
                            if (totalAmountContainer) totalAmountContainer.style.display = 'none';
                            draftInfoContainer.innerHTML = '';
                            currentDraftSchoolId = null;
                            terms = [];
                            renderTermsList();
                        }
                    });
                }
                
                window.resumeDraft = function() {
                    if (currentDraftSchoolId && loadTermsFromStorage(currentDraftSchoolId)) {
                        renderTermsList();
                        if (totalAmountContainer) { 
                            totalAmountContainer.style.display = 'block'; 
                            document.getElementById('schoolTotalAmountSpan').innerHTML = `₹${schoolTotalAmount.toLocaleString('en-IN')}`;
                        }
                        draftInfoContainer.innerHTML = '';
                    } else { toastr.error('No draft found to resume'); }
                };
                
                const form = document.getElementById('assignFeesForm');
                if (form) {
                    form.onsubmit = async (e) => {
                        e.preventDefault();
                        const schoolId = document.getElementById('assign_school_id')?.value;
                        const academicYear = document.getElementById('assign_academic_year')?.value;
                        const classId = document.getElementById('assign_class_id')?.value;
                        if (!schoolId) { toastr.error('Please select a School'); return; }
                        if (!academicYear) { toastr.error('Please select an Academic Year'); return; }
                        if (!classId) { toastr.error('Please select a Class'); return; }
                        if (terms.length === 0) { toastr.error('Please create at least one term'); return; }
                        
                        let missingDueDates = [];
                        terms.forEach(term => {
                            if (!term.due_date || term.due_date === '') {
                                missingDueDates.push(term.term_name);
                            }
                        });
                        if (missingDueDates.length > 0) {
                            toastr.error(`Please set due date for: ${missingDueDates.join(', ')}`);
                            return;
                        }
                        
                        const totalAllocated = getTotalAllocatedAmount();
                        if (Math.abs(totalAllocated - schoolTotalAmount) > 0.01) {
                            toastr.error(`Total allocated (₹${totalAllocated.toLocaleString('en-IN')}) does not match school total (₹${schoolTotalAmount.toLocaleString('en-IN')}). Please allocate all fees.`);
                            return;
                        }
                        
                        const termsData = [];
                        for (const term of terms) {
                            if (term.fees.length === 0) { toastr.warning(`${term.term_name} has no fees assigned. Please add fees to each term.`); return; }
                            termsData.push({ 
                                term_name: term.term_name, 
                                term_amount: term.term_amount, 
                                due_date: term.due_date, 
                                fees: term.fees.map(f => ({ fee_name: f.fee_name, amount: f.amount, group_id: f.group_id })) 
                            });
                        }
                        
                        Swal.fire({ title: 'Assigning Fees...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                        const requestData = { school_id: schoolId, academic_year: academicYear, class_id: classId, total_amount: schoolTotalAmount, terms: termsData };
                        const res = await fetch(API_BASE + 'student_fee_assign_terms.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(requestData) });
                        const result = await res.json();
                        if (result.success) {
                            clearTermsFromStorage(schoolId);
                            stopAutoSave();
                            Swal.fire({ icon: 'success', title: 'Fees Assigned!', html: `Assigned to ${result.assigned_count} student(s)<br>Failed: ${result.failed_count || 0}` });
                            closePanel();
                            loadStudentFeeDetails(1);
                        } else {
                            Swal.fire({ icon: 'error', title: 'Failed', text: result.message });
                        }
                    };
                }
                startAutoSave();
            }, 100);
        };
        
        document.getElementById('filter_academic_year').innerHTML = generateAcademicYearOptions();
        document.getElementById('assignFeeBtn').onclick = () => window.openAssignFees();
        
        document.getElementById('filter_school_id').addEventListener('change', async function() {
            const schoolId = this.value;
            const classSelect = document.getElementById('filter_class_id');
            if (schoolId) {
                classSelect.innerHTML = '<option value="">Loading classes...</option>';
                try {
                    const res = await fetch(API_BASE + `classes_list.php?school_id=${schoolId}`);
                    const data = await res.json();
                    if (data.success && data.data) {
                        const uniqueClasses = [...new Set(data.data.map(c => parseInt(c.class_id)))];
                        uniqueClasses.sort((a, b) => a - b);
                        classSelect.innerHTML = '<option value="">All Classes</option>';
                        uniqueClasses.forEach(cls => { classSelect.innerHTML += `<option value="${cls}">Class ${cls}</option>`; });
                    } else { classSelect.innerHTML = '<option value="">All Classes</option>'; }
                } catch(e) { classSelect.innerHTML = '<option value="">All Classes</option>'; }
            } else {
                classSelect.innerHTML = '<option value="">All Classes</option>';
            }
        });
        
        document.getElementById('applyFilterBtn').onclick = () => loadStudentFeeDetails(1);
        document.getElementById('resetFilterBtn').onclick = () => {
            document.getElementById('filter_school_id').value = '';
            document.getElementById('filter_academic_year').value = '';
            document.getElementById('filter_class_id').innerHTML = '<option value="">All Classes</option>';
            document.getElementById('infoMessage').style.display = 'block';
            document.getElementById('loadingDiv').style.display = 'none';
            document.getElementById('tableContainer').style.display = 'none';
        };
        
        loadSchools();
    </script>
</body>
</html>