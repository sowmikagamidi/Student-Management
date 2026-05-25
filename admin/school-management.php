<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutorix - School Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background: #f0f2f5; }
        
        /* Sidebar Styles */
        .app-container {
            display: flex;
            min-height: 200vh;
        }
        
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
        
        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        
        .sidebar-header h2 {
            font-size: 22px;
            margin-bottom: 5px;
        }
        
        .sidebar-header p {
            font-size: 12px;
            opacity: 0.7;
        }
        
        .nav-menu {
            padding: 20px 0;
        }
        
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
        
        .nav-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .nav-item.active {
            background: #4361ee;
            color: white;
            border-left: 4px solid #fff;
        }
        
        .nav-item .icon {
            font-size: 20px;
        }
        
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 15px;
        }
        h1 { font-size: 26px; color: #1a1a2e; }
        
        .btn { padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.2s; }
        .btn-primary { background: #4361ee; color: white; }
        .btn-primary:hover { background: #3a56d4; transform: translateY(-1px); }
        .btn-secondary { background: #6c757d; color: white; padding: 6px 12px; font-size: 12px; margin: 2px; border-radius: 6px; }
        .btn-secondary:hover { background: #5a6268; }
        .btn-success { background: #28a745; color: white; }
        .btn-success:hover { background: #218838; }
        .btn-warning { background: #ffc107; color: #333; }
        .btn-warning:hover { background: #e0a800; }
        .btn-info { background: #17a2b8; color: white; }
        .btn-info:hover { background: #138496; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; }
        
        table { width: 100%; background: white; border-collapse: collapse; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e9ecef; }
        th { background: #f8f9fa; font-weight: 600; color: #495057; }
        tr:hover { background: #f8f9fa; }
        
        .status-active { background: #d4edda; color: #155724; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; display: inline-block; }
        .status-inactive { background: #f8d7da; color: #721c24; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; display: inline-block; }
        
        .loading { text-align: center; padding: 50px; }
        .spinner { border: 3px solid #f3f3f3; border-top: 3px solid #4361ee; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 15px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 13px; }
        .form-group label .required { color: #dc3545; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; transition: all 0.2s; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #4361ee; box-shadow: 0 0 0 3px rgba(67,97,238,0.1); }
        .form-group input.error, .form-group select.error { border: 2px solid #dc3545; background: #fff0f0; }
        .error-message { color: #dc3545; font-size: 12px; margin-top: 5px; display: none; font-weight: 500; }
        .form-row { display: flex; gap: 15px; flex-wrap: wrap; }
        .form-row .form-group { flex: 1; min-width: 150px; }
        .btn-submit { width: 100%; padding: 12px; background: #4361ee; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 500; transition: all 0.2s; }
        .btn-submit:hover { background: #3a56d4; transform: translateY(-1px); }
        
        .readonly-field { background: #e9ecef; cursor: not-allowed; }
        fieldset { border: 1px solid #e9ecef; border-radius: 10px; padding: 18px; margin-bottom: 20px; }
        legend { font-weight: 600; padding: 0 12px; width: auto; color: #4361ee; }
        
        .action-buttons { display: flex; flex-wrap: wrap; gap: 6px; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 500; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 4px; font-size: 12px; }
        
        .used-yes { background: #d4edda; color: #155724; padding: 3px 8px; border-radius: 4px; }
        .used-no { background: #f8d7da; color: #721c24; padding: 3px 8px; border-radius: 4px; }
        
        .toggle-container { display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid #e9ecef; padding-bottom: 10px; flex-wrap: wrap; }
        .toggle-btn { padding: 8px 20px; border: none; background: #f8f9fa; cursor: pointer; border-radius: 8px; font-size: 14px; transition: all 0.3s; }
        .toggle-btn.active { background: #4361ee; color: white; }
        .toggle-content { display: none; }
        .toggle-content.active-content { display: block; }
        
        .reset-btn { background: #fd7e14; color: white; padding: 4px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; }
        .reset-btn:hover { background: #e96a0a; }
        
        .csv-example { background: #f8f9fa; padding: 15px; border-radius: 8px; font-size: 12px; margin-top: 10px; }
        .csv-example code { background: #e9ecef; display: block; padding: 10px; margin-top: 8px; white-space: pre-wrap; }
        
        .btn-download { background: #28a745; color: white; padding: 8px 16px; font-size: 12px; margin-left: 10px; }
        .btn-download:hover { background: #218838; }
        
        .preview-table { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 15px; }
        .preview-table th, .preview-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .preview-table th { background: #f0f7ff; }
        .preview-container { max-height: 400px; overflow-y: auto; margin-top: 20px; }
        
        .rightpanel-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .rightpanel-drawer {
            position: fixed;
            top: 0;
            right: 0;
            height: 100%;
            background: white;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }

        .drawer-body,
        .drawer-body *,
        .rightpanel-body,
        .rightpanel-body * {
            pointer-events: auto !important;
        }
        
        .user-detail-text {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 13px;
            line-height: 1.8;
            white-space: pre-wrap;
        }

        .swal2-container {
            z-index: 10000 !important;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2> Tutorix</h2>
                
            </div>
            <div class="nav-menu">
                <a href="school-management.php" class="nav-item active">
                    <span class="icon">🏫</span>
                    <span>School Management</span>
                </a>
                <a href="holidays.php" class="nav-item">
                    <span class="icon">📅</span>
                    <span>School Holidays</span>
                </a>
                <a href="school_fee_structure.php" class="nav-item">
                    <span class="icon">💰</span>
                    <span>School Fee Structure</span>
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="container">
                <div class="header">
                    <h1>🏫 School Management System</h1>
                    <button class="btn btn-primary" id="createBtn">+ Create School</button>
                </div>

                <!-- Search Section -->
                <div class="search-container" style="background: white; padding: 15px 20px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px;">
                    <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
                        <div style="flex: 1; min-width: 200px;">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 5px;">School Name / Code</label>
                            <input type="text" id="searchSchool" placeholder="Enter school name or code" style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 13px; outline: none;">
                        </div>
                        <div style="flex: 1; min-width: 150px;">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 5px;">Academic Year</label>
                            <select id="searchAcademicYear" style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 13px; background: white;">
                                <option value="">All Years</option>
                            </select>
                        </div>
                        <div style="flex: 1; min-width: 120px;">
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 5px;">Board</label>
                            <select id="searchBoard" style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 13px; background: white;">
                                <option value="">All Boards</option>
                                <option value="CBSE">CBSE</option>
                                <option value="ICSE">ICSE</option>
                                <option value="WBBSE">WBBSE</option>
                            </select>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <button id="applySearchBtn" style="padding: 8px 20px; background: #4361ee; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500;">Apply Filter</button>
                            <button id="resetSearchBtn" style="padding: 8px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500;">Reset</button>
                        </div>
                    </div>
                </div>
                
                <div id="loadingDiv" class="loading">
                    <div class="spinner"></div>
                    <p>Loading schools...</p>
                </div>
                
                <div id="tableContainer" style="display: none;">
                    <table>
                        <thead>
                            <tr><th>ID</th><th>School Code</th><th>School Name</th><th>Board</th><th>Academic Year</th><th>Status</th><th>Actions</th></tr>
                        </thead>
                        <tbody id="schoolsList"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/rightpanel.js"></script>
    <script>
        toastr.options = { closeButton: true, progressBar: true, positionClass: "toast-top-right", timeOut: "4000", preventDuplicates: true };
        const API_BASE = 'http://localhost/school_management/api/';
        
        function escapeHtml(str) { if (!str) return ''; return String(str).replace(/[&<>]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[m])); }
        
        function showFieldError(inputId, message) {
            const input = document.getElementById(inputId);
            if (input) { 
                input.classList.add('error'); 
                let errorDiv = input.parentElement.querySelector('.error-message');
                if (!errorDiv) { 
                    errorDiv = document.createElement('div'); 
                    errorDiv.className = 'error-message'; 
                    input.parentElement.appendChild(errorDiv); 
                } 
                errorDiv.style.display = 'block'; 
                errorDiv.innerHTML = message;
                input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                toastr.error(message);
            }
        }
        
        function clearFieldError(inputId) {
            const input = document.getElementById(inputId);
            if (input) { 
                input.classList.remove('error'); 
                const errorDiv = input.parentElement.querySelector('.error-message'); 
                if (errorDiv) { 
                    errorDiv.style.display = 'none'; 
                    errorDiv.innerHTML = ''; 
                } 
            }
        }
        
        function clearAllErrors(formId) {
            const form = document.getElementById(formId);
            if (form) { 
                form.querySelectorAll('input, select, textarea').forEach(input => { 
                    input.classList.remove('error'); 
                    const errorDiv = input.parentElement.querySelector('.error-message'); 
                    if (errorDiv) { 
                        errorDiv.style.display = 'none'; 
                        errorDiv.innerHTML = ''; 
                    } 
                }); 
            }
        }
        
        function validateRequired(inputId, fieldName) {
            const input = document.getElementById(inputId);
            if (input && !input.value.trim()) { 
                showFieldError(inputId, `${fieldName} is required`); 
                return false; 
            }
            clearFieldError(inputId); 
            return true;
        }
        
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        
        function validatePhone(phone) {
            const re = /^\d{10}$/;
            return re.test(phone);
        }
        
        const PANEL_WIDTHS = { school: '500px', classes: '550px', licence: '600px', tv: '700px', api: '600px', lms: '700px', user: '750px' };
        let activeTVContext = null, activeLMSContext = null, activeAPIContext = null, activeClassesContext = null, activeUserContext = null;
        
        function openPanel(title, contentHtml, onClose, width = '720px', icon = '📄') {
            return RightPanel.open({ title, icon, width, html: contentHtml, onClose });
        }
        
        function closePanel() {
            RightPanel.closeTop();
        }

        function refreshActiveTVPanel() {
            if (!activeTVContext) return;
            closePanel();
            setTimeout(() => openTVSection(activeTVContext.schoolId, activeTVContext.schoolName), 350);
        }
        
        function refreshActiveLMSPanel() {
            if (!activeLMSContext) return;
            closePanel();
            setTimeout(() => openLMSSection(activeLMSContext.schoolId, activeLMSContext.schoolName), 350);
        }
        
        function refreshActiveClassesPanel() {
            if (!activeClassesContext) return;
            closePanel();
            setTimeout(() => manageClasses(activeClassesContext.schoolId, activeClassesContext.schoolName), 350);
        }
        
        async function createSchool(data) {
            const res = await fetch(API_BASE + 'school_create.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
            return await res.json();
        }
        
        async function updateSchool(data) {
            const res = await fetch(API_BASE + 'school_update.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
            return await res.json();
        }

        function getBoardName(boardId) {
            return ({ C: 'CBSE', I: 'ICSE', W: 'WBBSE' }[boardId] || boardId || '-');
        }

        // ========== CHECK LMS LICENSE VALIDATION ==========
        async function checkLMSLicense(schoolId) {
            try {
                const res = await fetch(API_BASE + `licence_list.php?school_id=${schoolId}&_=${Date.now()}`);
                const data = await res.json();
                if (data.success && data.data && data.data.lms) {
                    const activeLicenses = data.data.lms.filter(l => l.expiry_date && new Date(l.expiry_date) > new Date() && l.is_deleted != 1 && l.available_qty > 0);
                    return activeLicenses.length > 0 ? activeLicenses[0] : null;
                }
                return null;
            } catch(e) {
                console.error('Error checking LMS license:', e);
                return null;
            }
        }

        // ========== RENDER USER HISTORY TABLE ==========
        function renderUserHistoryTable(users) {
            if (!users || users.length === 0) return '<p style="padding:15px; text-align:center;">No users found.</p>';
            return `<div style="overflow-x: auto;">
                <table style="width:100%; font-size:13px; border-collapse: collapse;">
                    <thead>
                        <tr style="background:#f8f9fa;">
                            <th style="padding:10px;">ID</th>
                            <th style="padding:10px;">Full Name</th>
                            <th style="padding:10px;">Username</th>
                            <th style="padding:10px;">Email</th>
                            <th style="padding:10px;">Mobile</th>
                            <th style="padding:10px;">Role</th>
                            <th style="padding:10px;">Status</th>
                            <th style="padding:10px;">Actions</th>
                          </tr>
                    </thead>
                    <tbody>
                        ${users.map(u => `
                            <tr>
                                <td style="padding:8px; border-bottom:1px solid #e9ecef;">${u.user_id || '-'}</td>
                                <td style="padding:8px; border-bottom:1px solid #e9ecef;">${escapeHtml(u.full_name || '-')}</td>
                                <td style="padding:8px; border-bottom:1px solid #e9ecef;">${escapeHtml(u.user_name || '-')}</td>
                                <td style="padding:8px; border-bottom:1px solid #e9ecef;">${escapeHtml(u.email_id || '-')}</td>
                                <td style="padding:8px; border-bottom:1px solid #e9ecef;">${u.mobile_number || '-'}</td>
                                <td style="padding:8px; border-bottom:1px solid #e9ecef;">${u.role || '-'}</td>
                                <td style="padding:8px; border-bottom:1px solid #e9ecef;">${u.display_status === 'A' ? '<span class="status-active">Active</span>' : '<span class="status-inactive">Inactive</span>'}</td>
                                <td style="padding:8px; border-bottom:1px solid #e9ecef;">
                                    <button class="btn btn-secondary" onclick="viewUserDetails(${u.user_id})" style="background:#17a2b8;">👁️ View</button>
                                    <button class="btn btn-secondary" onclick="editUserDetails(${u.user_id})" style="background:#ffc107; color:#333;">✏️ Edit</button>
                                    <button class="btn btn-danger" onclick="deleteUserAccount(${u.user_id})">🗑️ Delete</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>`;
        }

        // ========== VIEW USER DETAILS (TEXT FORMAT) ==========
        window.viewUserDetails = async function(userId) {
            try {
                const res = await fetch(API_BASE + `user_list.php?school_id=${activeUserContext?.schoolId}&_=${Date.now()}`);
                const data = await res.json();
                
                if (!data.success || !data.data) {
                    toastr.error('Could not fetch user details');
                    return;
                }
                
                const user = data.data.find(u => u.user_id == userId);
                if (!user) {
                    toastr.error('User not found');
                    return;
                }
                
                const genderText = user.gender === 'M' ? 'Male' : user.gender === 'F' ? 'Female' : user.gender === 'O' ? 'Other' : '-';
                const statusText = user.display_status === 'A' ? 'Active' : user.display_status === 'I' ? 'Inactive' : user.display_status === 'B' ? 'Blocked' : 'Disabled';
                const boardText = user.board_id === 'C' ? 'CBSE' : user.board_id === 'I' ? 'ICSE' : user.board_id === 'W' ? 'WBBSE' : '-';
                const subTypeText = user.subscription_type === 'D' ? 'Demo' : user.subscription_type === 'B' ? 'Basic' : user.subscription_type === 'P' ? 'Premium' : '-';
                
                let detailsText = `========================================
📋 USER DETAILS
========================================

👤 PERSONAL INFORMATION
────────────────────────────────────────
User ID         : ${user.user_id}
Full Name       : ${user.full_name || '-'}
Username        : ${user.user_name || '-'}
Email           : ${user.email_id || '-'}
Mobile          : ${user.mobile_number || '-'}
Country Code    : ${user.country_code || '+91'}
Gender          : ${genderText}
Status          : ${statusText}
Role            : ${user.role || '-'}
Created Date    : ${user.created_at || '-'}

📚 ACADEMIC INFORMATION
────────────────────────────────────────
Class           : ${user.class_id || user.current_class || '-'}
Batch IDs       : ${user.batch_ids || '-'}
Batch Names     : ${user.batch_names || '-'}
Board           : ${boardText}
Academic Years  : ${user.academic_years || '-'}
Subscription    : ${subTypeText}
Joining Date    : ${user.joining_date || '-'}
Expiry Date     : ${user.expiry_date || '-'}

========================================`;

                const viewHtml = `
                    <div class="user-detail-text">${detailsText.replace(/\n/g, '<br>')}</div>
                    <div style="margin-top: 20px;">
                        <button type="button" onclick="closePanel()" class="btn-submit" style="background:#6c757d;">Close</button>
                    </div>
                `;
                
                openPanel(`User Details: ${escapeHtml(user.full_name)}`, viewHtml, null, '600px', '👁️');
                
            } catch(e) {
                console.error('Error loading user:', e);
                toastr.error('Error loading user details');
            }
        };

        // ========== EDIT USER DETAILS ==========
        window.editUserDetails = async function(userId) {
            try {
                const res = await fetch(API_BASE + `user_list.php?school_id=${activeUserContext?.schoolId}&_=${Date.now()}`);
                const data = await res.json();
                
                if (!data.success || !data.data) {
                    toastr.error('Could not fetch user details');
                    return;
                }
                
                const user = data.data.find(u => u.user_id == userId);
                if (!user) {
                    toastr.error('User not found');
                    return;
                }
                
                const editHtml = `
                    <form id="editUserFormModal" novalidate>
                        <input type="hidden" id="edit_user_id" value="${user.user_id}">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Full Name <span class="required">*</span></label>
                                <input type="text" id="edit_full_name" value="${escapeHtml(user.full_name)}" required>
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" value="${escapeHtml(user.user_name)}" readonly class="readonly-field">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Email <span class="required">*</span></label>
                                <input type="email" id="edit_email" value="${escapeHtml(user.email_id)}" required>
                            </div>
                            <div class="form-group">
                                <label>Mobile Number</label>
                                <input type="tel" id="edit_mobile" value="${user.mobile_number || ''}">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Country Code</label>
                                <select id="edit_country_code">
                                    <option value="+91" ${user.country_code === '+91' ? 'selected' : ''}>+91 (India)</option>
                                    <option value="+1" ${user.country_code === '+1' ? 'selected' : ''}>+1 (USA)</option>
                                    <option value="+44" ${user.country_code === '+44' ? 'selected' : ''}>+44 (UK)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Gender</label>
                                <select id="edit_gender">
                                    <option value="">Select Gender</option>
                                    <option value="M" ${user.gender === 'M' ? 'selected' : ''}>Male</option>
                                    <option value="F" ${user.gender === 'F' ? 'selected' : ''}>Female</option>
                                    <option value="O" ${user.gender === 'O' ? 'selected' : ''}>Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Status <span class="required">*</span></label>
                                <select id="edit_status" required>
                                    <option value="A" ${user.display_status === 'A' ? 'selected' : ''}>Active</option>
                                    <option value="B" ${user.display_status === 'B' ? 'selected' : ''}>Blocked</option>
                                    <option value="D" ${user.display_status === 'D' ? 'selected' : ''}>Disabled</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Role <span class="required">*</span></label>
                                <select id="edit_role" required>
                                    <option value="student" ${user.role === 'student' ? 'selected' : ''}>Student</option>
                                    <option value="teacher" ${user.role === 'teacher' ? 'selected' : ''}>Teacher</option>
                                    <option value="mentor" ${user.role === 'mentor' ? 'selected' : ''}>Mentor</option>
                                    <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Subscription Type</label>
                                <select id="edit_subscription">
                                    <option value="D" ${user.subscription_type === 'D' ? 'selected' : ''}>Demo</option>
                                    <option value="B" ${user.subscription_type === 'B' ? 'selected' : ''}>Basic</option>
                                    <option value="P" ${user.subscription_type === 'P' ? 'selected' : ''}>Premium</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Class</label>
                                <input type="text" id="edit_class" value="${user.class_id || user.current_class || ''}" placeholder="Class">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Joining Date</label>
                                <input type="date" id="edit_joining_date" value="${user.joining_date !== '-' ? user.joining_date : ''}">
                            </div>
                            <div class="form-group">
                                <label>Expiry Date</label>
                                <input type="date" id="edit_expiry_date" value="${user.expiry_date !== '-' ? user.expiry_date : ''}">
                            </div>
                        </div>
                        
                        <div style="display:flex; gap:10px; margin-top:20px;">
                            <button type="submit" class="btn-submit" style="background:#28a745;">💾 Save Changes</button>
                            <button type="button" onclick="closePanel()" class="btn-secondary" style="background:#6c757d;">Cancel</button>
                        </div>
                    </form>
                `;
                
                openPanel(`Edit User: ${escapeHtml(user.full_name)}`, editHtml, null, '600px', '✏️');
                
                setTimeout(() => {
                    document.getElementById('editUserFormModal').onsubmit = async (e) => {
                        e.preventDefault();
                        
                        const email = document.getElementById('edit_email').value;
                        if (!email || !validateEmail(email)) {
                            toastr.error('Valid email is required');
                            return;
                        }
                        
                        const updateData = {
                            user_id: parseInt(document.getElementById('edit_user_id').value),
                            email: email,
                            full_name: document.getElementById('edit_full_name').value,
                            mobile_number: document.getElementById('edit_mobile').value,
                            country_code: document.getElementById('edit_country_code').value,
                            gender: document.getElementById('edit_gender').value,
                            user_status: document.getElementById('edit_status').value,
                            role: document.getElementById('edit_role').value,
                            subscription_type: document.getElementById('edit_subscription').value,
                            current_class: document.getElementById('edit_class').value,
                            joining_date: document.getElementById('edit_joining_date').value,
                            expiry_date: document.getElementById('edit_expiry_date').value
                        };
                        
                        Swal.fire({ title: 'Updating...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                        
                        const res = await fetch(API_BASE + 'user_update.php', {
                            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(updateData)
                        });
                        const result = await res.json();
                        
                        if (result.success) {
                            Swal.fire({ icon: 'success', title: 'Updated!', text: result.message, timer: 1500, showConfirmButton: false });
                            closePanel();
                            await refreshUserHistory(activeUserContext?.schoolId);
                            if (activeUserContext) showUserToggleContent('userHistory');
                        } else {
                            Swal.fire({ icon: 'error', title: 'Update Failed', text: result.message });
                        }
                    };
                }, 100);
                
            } catch(e) {
                console.error('Error loading user:', e);
                toastr.error('Error loading user details');
            }
        };

        // ========== DELETE USER ACCOUNT ==========
        window.deleteUserAccount = async function(userId) {
            const result = await Swal.fire({
                title: 'Delete User?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete!',
                cancelButtonText: 'Cancel'
            });
            
            if (result.isConfirmed) {
                Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                
                const res = await fetch(API_BASE + 'user_delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: userId })
                });
                const data = await res.json();
                
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message });
                    await refreshUserHistory(activeUserContext?.schoolId);
                    if (activeUserContext) showUserToggleContent('userHistory');
                } else {
                    Swal.fire({ icon: 'error', title: 'Delete Failed', text: data.message });
                }
            }
        };

        // ========== REFRESH USER HISTORY ==========
        async function refreshUserHistory(schoolId) {
            const historyDiv = document.getElementById('userHistory');
            if (historyDiv) {
                historyDiv.innerHTML = '<div style="text-align:center; padding:20px;"><div class="spinner" style="margin:0 auto;"></div><p>Loading users...</p></div>';
            }
            
            try {
                const res = await fetch(API_BASE + `user_list.php?school_id=${schoolId}&_=${Date.now()}`);
                const data = await res.json();
                
                if (data.success && data.data) {
                    const html = renderUserHistoryTable(data.data);
                    if (historyDiv) historyDiv.innerHTML = html;
                    return data.data;
                } else {
                    if (historyDiv) historyDiv.innerHTML = `<p>${escapeHtml(data.message || 'Unable to load users')}</p>`;
                    return [];
                }
            } catch(e) {
                console.error('Error loading users:', e);
                if (historyDiv) historyDiv.innerHTML = '<p>Unable to load users. Please try again.</p>';
                return [];
            }
        }

        window.openUserManagement = async function(schoolId, schoolName) {
            activeUserContext = { schoolId, schoolName };
            const activeLicense = await checkLMSLicense(schoolId);
            let batches = [];
            try {
                const res = await fetch(API_BASE + `batches_list.php?school_id=${schoolId}`);
                const data = await res.json();
                if (data.success) batches = data.data;
            } catch(e) { console.error('Error loading batches:', e); }
            let batchOptions = '<option value="">Select Batch</option>';
            batches.forEach(b => { batchOptions += `<option value="${b.batch_id}">${escapeHtml(b.display_name)}</option>`; });
            let classOptions = '<option value="">Select Class</option>';
            const uniqueClasses = [...new Map(batches.map(b => [b.class_id, b])).values()];
            uniqueClasses.forEach(c => { classOptions += `<option value="${c.class_id}">Class ${c.class_id}</option>`; });
            const licenseWarning = !activeLicense ? '<div style="background: #fff3cd; border: 1px solid #ffecb5; padding: 12px; border-radius: 8px; margin-bottom: 15px; color: #856404;"><strong>⚠️ Warning:</strong> No active LMS license with available slots found. You cannot create student users until an LMS license is added.</div>' : `<div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 12px; border-radius: 8px; margin-bottom: 15px; color: #155724;"><strong>✅ Active LMS License Found</strong></div>`;
            const users = await refreshUserHistory(schoolId);
            const usersHtml = renderUserHistoryTable(users);
            const createFormHtml = `<form id="addUserForm" novalidate><input type="hidden" id="user_school_id" value="${schoolId}">${licenseWarning}<fieldset><legend>👤 Personal Information</legend><div class="form-row"><div class="form-group"><label>Full Name <span class="required">*</span></label><input type="text" id="user_full_name" placeholder="Enter full name" required><div class="error-message"></div></div><div class="form-group"><label>Username</label><input type="text" id="user_name" placeholder="Leave empty to auto-generate"></div></div><div class="form-row"><div class="form-group"><label>Country Code</label><select id="user_country_code"><option value="+91">+91 (India)</option><option value="+1">+1 (USA)</option><option value="+44">+44 (UK)</option><option value="+61">+61 (Australia)</option><option value="+971">+971 (UAE)</option><option value="+966">+966 (Saudi)</option></select></div><div class="form-group"><label>Mobile Number <span class="required">*</span></label><input type="tel" id="user_mobile" placeholder="9876543210" required><div class="error-message"></div></div></div><div class="form-row"><div class="form-group"><label>Email ID <span class="required">*</span></label><input type="email" id="user_email_id" placeholder="email@school.com" required><div class="error-message"></div></div><div class="form-group"><label>Gender</label><select id="user_gender"><option value="">Select Gender</option><option value="M">Male</option><option value="F">Female</option><option value="O">Other</option></select></div></div><div class="form-row"><div class="form-group"><label>User Status <span class="required">*</span></label><select id="user_status" required><option value="A">Active</option><option value="B">Blocked</option><option value="D">Disabled</option></select></div><div class="form-group"><label>Role <span class="required">*</span></label><select id="user_role" required><option value="">Select Role</option><option value="student">Student</option><option value="teacher">Teacher</option><option value="mentor">Mentor</option><option value="admin">Admin</option></select></div></div></fieldset><div id="studentSection" style="display:none;"><fieldset><legend>📚 Academic Information</legend><div class="form-row"><div class="form-group"><label>Class <span class="required">*</span></label><select id="user_class_id">${classOptions}</select></div><div class="form-group"><label>Batch / Section</label><select id="user_batch_id">${batchOptions}</select></div></div><div class="form-row"><div class="form-group"><label>Subscription Type</label><select id="user_subscription"><option value="D">Demo</option><option value="B">Basic</option><option value="P">Premium</option></select></div></div></fieldset></div><div id="teacherSection" style="display:none;"><fieldset><legend>👨‍🏫 Teacher/Mentor Information</legend><div class="form-row"><div class="form-group"><label>Batch</label><select id="teacher_batch_id">${batchOptions}</select></div><div class="form-group"><label>Subject ID</label><input type="text" id="user_subject" placeholder="e.g., MAT, SCI, ENG"></div></div></fieldset></div><button type="submit" class="btn-submit" ${!activeLicense ? 'disabled style="opacity:0.6; cursor:not-allowed;"' : ''}>👤 Create User</button>${!activeLicense ? '<p style="color:#dc3545; font-size:12px; margin-top:10px;">* Please add an LMS license with available slots before creating student users.</p>' : ''}</form>`;
            const csvFormHtml = `<form id="csvUploadForm" enctype="multipart/form-data"><input type="hidden" name="school_id" value="${schoolId}"><div class="form-group"><label>CSV File *</label><input type="file" name="csv_file" accept=".csv" id="csvFileInput"><div class="error-message" id="csvFileError"></div></div><button type="button" class="btn btn-secondary" onclick="previewCSV()" style="margin-bottom:10px;">👁️ Preview CSV Data</button><div id="csvPreviewContainer" class="preview-container" style="display:none;"></div><button type="submit" class="btn-submit" ${!activeLicense ? 'disabled style="opacity:0.6; cursor:not-allowed;"' : ''}>📤 Upload CSV</button>${!activeLicense ? '<p style="color:#dc3545; font-size:12px; margin-top:10px;">* Please add an LMS license with available slots before uploading students.</p>' : ''}</form>`;
            const formHtml = `<div class="toggle-container"><button class="toggle-btn active" onclick="showUserToggleContent('userCreate')">➕ Create Single User</button><button class="toggle-btn" onclick="showUserToggleContent('userCSV')">📁 Bulk  Upload</button><button class="toggle-btn" onclick="showUserToggleContent('userHistory')">📜 User History</button></div><div id="userCreate" class="toggle-content active-content">${createFormHtml}</div><div id="userCSV" class="toggle-content"><div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;"><h4 style="margin:0;">Bulk  Upload</h4><button type="button" class="btn btn-download" onclick="downloadSampleCSV()">📥 Download Sample CSV</button></div>${csvFormHtml}</div><div id="userHistory" class="toggle-content">${usersHtml}</div>`;
            openPanel(`User Management - ${escapeHtml(schoolName)}`, formHtml, null, PANEL_WIDTHS.user, '👥');
            
            window.previewCSV = function() {
                const fileInput = document.getElementById('csvFileInput');
                const previewContainer = document.getElementById('csvPreviewContainer');
                const fileError = document.getElementById('csvFileError');
                if (!fileInput.files || !fileInput.files[0]) { fileError.style.display = 'block'; fileError.innerHTML = 'Please select a CSV file to preview'; toastr.error('Please select a CSV file'); return; }
                const file = fileInput.files[0];
                if (!file.name.endsWith('.csv')) { fileError.style.display = 'block'; fileError.innerHTML = 'Please upload a valid CSV file (.csv)'; toastr.error('Please upload a valid CSV file'); return; }
                // Check if file is a downloaded template
                const downloadCheck = validateFileNotDownloaded(file);
                if (!downloadCheck.valid) { fileError.style.display = 'block'; fileError.innerHTML = downloadCheck.message; toastr.error(downloadCheck.message); return; }
                fileError.style.display = 'none';
                const reader = new FileReader();
                reader.onload = function(e) {
                    let content = e.target.result;
                    // Remove double quotes from CSV content for preview
                    let cleanContent = content.replace(/"(?![^"]*")/g, '').replace(/""/g, '"');
                    const rows = cleanContent.split(/\r?\n/);
                    const headers = rows[0] ? rows[0].split(',').map(h => h.trim()) : [];
                    if (headers.length < 10) { toastr.error('CSV must have at least 10 columns'); return; }
                    let previewHtml = '<h4>📊 CSV Data Preview (First 10 rows) - Quotes Removed</h4>';
                    previewHtml += '<table class="preview-table"><thead><tr>';
                    headers.forEach(h => previewHtml += `<th>${escapeHtml(h)}</th>`);
                    previewHtml += '</tr></thead><tbody>';
                    for (let i = 1; i < Math.min(rows.length, 11); i++) {
                        if (rows[i].trim()) {
                            const cols = rows[i].split(',').map(c => c.trim());
                            previewHtml += '<tr>';
                            for (let j = 0; j < headers.length; j++) { previewHtml += `<td>${escapeHtml(cols[j] || '-')}</td>`; }
                            previewHtml += '</tr>';
                        }
                    }
                    previewHtml += '</tbody></table>';
                    previewHtml += `<p style="margin-top:10px;"><strong>Total rows:</strong> ${rows.length - 1}</p>`;
                    previewContainer.innerHTML = previewHtml;
                    previewContainer.style.display = 'block';
                };
                reader.readAsText(file);
            };
            
            window.downloadSampleCSV = function() {
                const sampleData = [['full_name', 'email', 'mobile', 'academic_year', 'board', 'class_id', 'batch_id', 'subscription_type', 'joining_date', 'expiry_date'], ['John Doe', 'john@school.com', '9876543210', '2025-2026', 'C', '6', '1', 'D', '2026-05-14', '2027-05-14'], ['Jane Smith', 'jane@school.com', '9876543211', '2025-2026', 'I', '7', '2', 'B', '2026-05-14', '2027-05-14']];
                const csvContent = sampleData.map(row => row.map(cell => `"${cell}"`).join(',')).join('\r\n');
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'sample_users.csv';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
                toastr.success('Sample CSV downloaded successfully');
            };
            
            setTimeout(() => {
                const classSelect = document.getElementById('user_class_id');
                const batchSelect = document.getElementById('user_batch_id');
                if (classSelect && batchSelect) {
                    classSelect.onchange = function() {
                        const selectedClass = this.value;
                        const filteredBatches = batches.filter(b => b.class_id == selectedClass);
                        batchSelect.innerHTML = '<option value="">Select Batch</option>';
                        filteredBatches.forEach(b => { batchSelect.innerHTML += `<option value="${b.batch_id}">${escapeHtml(b.display_name)}</option>`; });
                    };
                }
                const roleSelect = document.getElementById('user_role');
                const studentSection = document.getElementById('studentSection');
                const teacherSection = document.getElementById('teacherSection');
                if (roleSelect) {
                    roleSelect.onchange = () => {
                        const role = roleSelect.value;
                        if (studentSection) studentSection.style.display = role === 'student' ? 'block' : 'none';
                        if (teacherSection) teacherSection.style.display = (role === 'teacher' || role === 'mentor') ? 'block' : 'none';
                    };
                }
                const addForm = document.getElementById('addUserForm');
                if (addForm) {
                    addForm.onsubmit = async (e) => {
                        e.preventDefault();
                        clearAllErrors('addUserForm');
                        let isValid = true;
                        if (!validateRequired('user_full_name', 'Full Name')) isValid = false;
                        if (!validateRequired('user_email_id', 'Email ID')) isValid = false;
                        if (!validateRequired('user_mobile', 'Mobile Number')) isValid = false;
                        if (!validateRequired('user_status', 'User Status')) isValid = false;
                        if (!validateRequired('user_role', 'Role')) isValid = false;
                        const email = document.getElementById('user_email_id').value;
                        if (email && !validateEmail(email)) { showFieldError('user_email_id', 'Valid email required (alphanumeric@domain.com)'); isValid = false; }
                        const mobile = document.getElementById('user_mobile').value;
                        const countryCode = document.getElementById('user_country_code').value;
                        if (mobile) {
                            const phoneValid = validatePhoneByCountry(mobile, countryCode);
                            if (!phoneValid.valid) { showFieldError('user_mobile', phoneValid.message); isValid = false; }
                        }
                        const role = document.getElementById('user_role').value;
                        if (role === 'student' && !validateRequired('user_class_id', 'Class')) isValid = false;
                        if (!isValid) return;
                        const formData = { school_id: schoolId, full_name: document.getElementById('user_full_name').value, user_name: document.getElementById('user_name').value || null, email_id: document.getElementById('user_email_id').value, country_code: document.getElementById('user_country_code').value, mobile_number: document.getElementById('user_mobile').value, user_status: document.getElementById('user_status').value, role: role, gender: document.getElementById('user_gender').value || null };
                        if (role === 'student') { formData.class_id = document.getElementById('user_class_id').value; formData.batch_id = document.getElementById('user_batch_id').value; formData.subscription_type = document.getElementById('user_subscription').value; formData.current_class = document.getElementById('user_class_id').value; }
                        if (role === 'teacher' || role === 'mentor') { formData.batch_id = document.getElementById('teacher_batch_id').value; formData.subject_id = document.getElementById('user_subject').value; }
                        Swal.fire({ title: 'Creating...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                        const res = await fetch(API_BASE + 'user_add.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(formData) });
                        const result = await res.json();
                        if (result.success) { Swal.fire({ icon: 'success', title: 'User Added!', html: `Username: <strong>${result.user_name}</strong>` }); addForm.reset(); if (studentSection) studentSection.style.display = 'none'; if (teacherSection) teacherSection.style.display = 'none'; await refreshUserHistory(schoolId); showUserToggleContent('userHistory'); }
                        else { Swal.fire({ icon: 'error', title: 'Failed', text: result.message }); }
                    };
                }
                const csvForm = document.getElementById('csvUploadForm');
                if (csvForm) {
                    csvForm.onsubmit = async (e) => {
                        e.preventDefault();
                        const fileInput = document.getElementById('csvFileInput');
                        const fileError = document.getElementById('csvFileError');
                        if (!fileInput.files || !fileInput.files[0]) { fileError.style.display = 'block'; fileError.innerHTML = 'Please select a CSV file to upload'; toastr.error('Please select a CSV file to upload'); return; }
                        const file = fileInput.files[0];
                        if (!file.name.endsWith('.csv')) { fileError.style.display = 'block'; fileError.innerHTML = 'Please upload a valid CSV file (.csv)'; toastr.error('Please upload a valid CSV file'); return; }
                        // Check if file is a downloaded template
                        const downloadCheck = validateFileNotDownloaded(file);
                        if (!downloadCheck.valid) { fileError.style.display = 'block'; fileError.innerHTML = downloadCheck.message; toastr.error(downloadCheck.message); return; }
                        fileError.style.display = 'none';
                        const confirmResult = await Swal.fire({ title: 'Confirm Upload', text: 'Are you sure you want to upload this CSV file?', icon: 'question', showCancelButton: true, confirmButtonColor: '#28a745', confirmButtonText: 'Yes, upload!' });
                        if (confirmResult.isConfirmed) {
                            Swal.fire({ title: 'Uploading...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                            const formData = new FormData(csvForm);
                            const res = await fetch(API_BASE + 'user_csv_upload.php', { method: 'POST', body: formData });
                            const result = await res.json();
                            if (result.success) {
                                if (result.success_count > 0) { Swal.fire({ icon: 'success', title: 'Upload Complete!', html: `✅ ${result.success_count} users added<br>❌ ${result.failed_count} failed` }); csvForm.reset(); document.getElementById('csvPreviewContainer').style.display = 'none'; await refreshUserHistory(schoolId); showUserToggleContent('userHistory'); }
                                else { Swal.fire({ icon: 'warning', title: 'Upload Completed with Errors', html: `❌ ${result.failed_count} failed<br>${result.errors ? result.errors.slice(0, 5).join('<br>') : ''}` }); }
                            } else { Swal.fire({ icon: 'error', title: 'Upload Failed', text: result.message }); }
                        }
                    };
                }
            }, 100);
        };
        window.showUserToggleContent = function(activeId) {
            document.querySelectorAll('#userCreate, #userCSV, #userHistory').forEach(content => content.classList.remove('active-content'));
            document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById(activeId).classList.add('active-content');
            const btns = document.querySelectorAll('.toggle-btn');
            for (let btn of btns) { if (btn.textContent.includes(activeId === 'userCreate' ? 'Create Single' : activeId === 'userCSV' ? 'Bulk CSV' : 'User History')) { btn.classList.add('active'); break; } }
            if (activeId === 'userHistory') refreshUserHistory(activeUserContext?.schoolId);
        };
        window.generateAPIKey = function(type = 'tv', classId = null) {
            const currentYear = new Date().getFullYear();
            const nextYear = currentYear + 1;
            const prefix = type === 'tv' ? 'TPTX' : 'RATX';

            if (type === 'tv' && classId) {
                return `${prefix}-${currentYear}-${nextYear}-CL${String(classId).padStart(2, '0')}`;
            } else if (type === 'lms') {
                const randomNum = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
                return `${prefix}-${currentYear}-${nextYear}-${randomNum}`;
            }

            return `${prefix}-${currentYear}-${nextYear}-0000`;
        };

        window.showToggleContent = function(contentId) {
            const contentEl = document.getElementById(contentId);
            if (!contentEl) return;

            const container = contentEl.closest('.toggle-container')?.parentElement || contentEl.parentElement;
            const allContents = container?.querySelectorAll('[id$="Create"], [id$="History"], [id$="CSV"]') || [];
            const allBtns = container?.querySelectorAll('.toggle-btn') || [];

            allContents.forEach(el => el.classList.remove('active-content'));
            allBtns.forEach(btn => btn.classList.remove('active'));

            contentEl.classList.add('active-content');

            for (let btn of allBtns) {
                if (btn.getAttribute('onclick')?.includes(contentId)) {
                    btn.classList.add('active');
                    break;
                }
            }
        };

        window.showUserToggleContent = function(activeId) {
            document.querySelectorAll('#userCreate, #userCSV, #userHistory').forEach(content => {
                content.classList.remove('active-content');
            });
            document.querySelectorAll('.toggle-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.getElementById(activeId).classList.add('active-content');

            const btns = document.querySelectorAll('.toggle-btn');
            for (let btn of btns) {
                if (btn.textContent.includes(activeId === 'userCreate' ? 'Create Single' : activeId === 'userCSV' ? 'Bulk CSV' : 'User History')) {
                    btn.classList.add('active');
                    break;
                }
            }

            if (activeId === 'userHistory') {
                refreshUserHistory(activeUserContext?.schoolId);
            }
        };
        
        // ========== TV SECTION ==========
window.openTVSection = async function(schoolId, schoolName) {
    activeTVContext = { schoolId, schoolName };
    let classes = [];
    
    try {
        const res = await fetch(API_BASE + `classes_list.php?school_id=${schoolId}`);
        const data = await res.json();
        if (data.success && data.data) {
            const seenIds = new Set();
            for (const c of data.data) { 
                if (!seenIds.has(c.class_id)) { 
                    seenIds.add(c.class_id); 
                    classes.push(c); 
                } 
            }
        }
    } catch(e) {}
    
    if (classes.length === 0) { 
        classes = [1,2,3,4,5,6,7,8,9,10,11,12].map(i => ({ class_id: i, class_name: 'Class ' + i })); 
    }
    
    const classOptions = classes.map(c => `<option value="${c.class_id}">Class ${c.class_id}</option>`).join('');

    // Function to generate 4-set API key (Format: TPTX-XXXX-XXXX-CLXX)
    function generateTVApiKey(classId) {
        const randomSet1 = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
        const randomSet2 = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
        return `TPTX-${randomSet1}-${randomSet2}-CL${String(classId).padStart(2, '0')}`;
    }

    const createFormHtml = `<form id="createTVForm" novalidate>
        <div class="form-group">
            <label>API Key <span class="required">*</span></label>
            <input type="text" id="tv_api_key" readonly style="background:#e9ecef; cursor:not-allowed; font-family:monospace; font-size:14px;">
            <small style="display:block; margin-top:5px; color:#666;">Auto-generated 4-set unique API key (Format: TPTX-XXXX-XXXX-CLXX)</small>
        </div>
        <div class="form-group">
            <label>Class <span class="required">*</span></label>
            <select id="tv_class_id" required>${classOptions}</select>
            <div class="error-message"></div>
        </div>
        <div class="form-group">
            <label>Used Status <span class="required">*</span></label>
            <select id="tv_used_status" required>
                <option value="N">Not Used</option>
                <option value="Y">Used</option>
            </select>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Joining Date <span class="required">*</span></label>
                <input type="date" id="tv_joining_date" required>
            </div>
            <div class="form-group">
                <label>Expiry Date <span class="required">*</span></label>
                <input type="date" id="tv_expiry_date" required>
            </div>
        </div>
        <button type="submit" class="btn-submit">✅ Create TV Licence</button>
    </form>`;
    
    // ========== VIEW TV LICENCE FUNCTION ==========
    window.viewTVLicence = async function(licenceId) {
        try {
            const res = await fetch(API_BASE + `licence_list.php?school_id=${activeTVContext?.schoolId}&_=${Date.now()}`);
            const data = await res.json();

            if (!data.success || !data.data || !data.data.tv) {
                toastr.error('Could not fetch licence details');
                return;
            }

            const licence = data.data.tv.find(l => l.licence_id == licenceId);
            if (!licence) {
                toastr.error('Licence not found');
                return;
            }

            const status = licence.expiry_date && new Date(licence.expiry_date) > new Date() ? 'Active' : 'Expired';
            const apiKeyValue = licence.tv_api_key || 'Not Generated Yet';
            
            const detailsHtml = `
                <div style="background:#f8f9fa; padding:20px; border-radius:8px; font-family:monospace; font-size:14px; line-height:1.8;">
                    <p><strong>🔑 API Key:</strong> ${licence.tv_api_key}</p>
                    <p><code style="background:#fff; padding:10px; border-radius:6px; font-size:14px; display:block; margin:10px 0; word-break:break-all;">${apiKeyValue}</code></p>
                    <p><strong>🆔 Licence ID:</strong> ${licence.licence_id}</p>
                    <p><strong>📚 Class:</strong> ${licence.class_id || '-'}</p>
                    <p><strong>✅ Used Status:</strong> ${licence.used_status === 'Y' ? '<span class="used-yes">✓ Used</span>' : '<span class="used-no">✗ Not Used</span>'}</p>
                    <p><strong>📅 Joining Date:</strong> ${licence.joining_date || '-'}</p>
                    <p><strong>⏰ Expiry Date:</strong> ${licence.expiry_date || '-'}</p>
                    <p><strong>⚡ Status:</strong> <span class="${status === 'Active' ? 'status-active' : 'status-inactive'}">${status}</span></p>
                </div>
                <div style="margin-top: 20px;">
                    <button type="button" onclick="closePanel()" class="btn-submit" style="background:#6c757d;">Close</button>
                </div>
            `;

            openPanel(`TV Licence Details - Class ${licence.class_id}`, detailsHtml, null, '550px', '👁️');

        } catch(e) {
            console.error('Error loading licence:', e);
            toastr.error('Error loading licence details');
        }
    };
    
    // ========== EDIT TV LICENCE FUNCTION ==========
    window.editTVLicence = async function(licenceId) {
        try {
            const res = await fetch(API_BASE + `licence_list.php?school_id=${activeTVContext?.schoolId}&_=${Date.now()}`);
            const data = await res.json();

            if (!data.success || !data.data || !data.data.tv) {
                toastr.error('Could not fetch licence details');
                return;
            }

            const licence = data.data.tv.find(l => l.licence_id == licenceId);
            if (!licence) {
                toastr.error('Licence not found');
                return;
            }

            let classList = [];
            try {
                const classRes = await fetch(API_BASE + `classes_list.php?school_id=${activeTVContext?.schoolId}`);
                const classData = await classRes.json();
                if (classData.success && classData.data) {
                    const seenIds = new Set();
                    for (const c of classData.data) {
                        if (!seenIds.has(c.class_id)) {
                            seenIds.add(c.class_id);
                            classList.push({ class_id: c.class_id, class_name: 'Class ' + c.class_id });
                        }
                    }
                }
            } catch(e) {}

            if (classList.length === 0) {
                classList = [1,2,3,4,5,6,7,8,9,10,11,12].map(i => ({ class_id: i, class_name: 'Class ' + i }));
            }

            const classOptions = classList.map(c => `<option value="${c.class_id}" ${c.class_id == licence.class_id ? 'selected' : ''}>Class ${c.class_id}</option>`).join('');

            const formatDate = (date) => {
                if (!date || date === '0000-00-00') return '';
                return date;
            };
            
            const apiKeyValue = licence.api_key && licence.api_key !== 'N/A' && licence.api_key !== null ? licence.api_key : 'Not Generated Yet';

            const editFormHtml = `
                <form id="editTVFormModal" novalidate>
                    <input type="hidden" id="edit_licence_id" value="${licence.licence_id}">

                    <div class="form-group">
                        <label>API Key</label>
                        <input type="text" id="edit_tv_api_key" value="${apiKeyValue}" readonly style="background:#e9ecef; cursor:not-allowed; font-family:monospace;">
                        <small style="display:block; margin-top:5px; color:#666;">API Key cannot be modified</small>
                    </div>

                    <div class="form-group">
                        <label>Class <span class="required">*</span></label>
                        <select id="edit_tv_class_id" required class="form-control">${classOptions}</select>
                    </div>

                    <div class="form-group">
                        <label>Used Status <span class="required">*</span></label>
                        <select id="edit_tv_used_status" required class="form-control">
                            <option value="N" ${licence.used_status === 'N' ? 'selected' : ''}>Not Used</option>
                            <option value="Y" ${licence.used_status === 'Y' ? 'selected' : ''}>Used</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Joining Date <span class="required">*</span></label>
                            <input type="date" id="edit_tv_joining_date" value="${formatDate(licence.joining_date)}" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Expiry Date <span class="required">*</span></label>
                            <input type="date" id="edit_tv_expiry_date" value="${formatDate(licence.expiry_date)}" required class="form-control">
                        </div>
                    </div>

                    <div style="display:flex; gap:10px; margin-top:20px;">
                        <button type="submit" class="btn-submit" style="background:#28a745;">💾 Save Changes</button>
                        <button type="button" onclick="closePanel()" class="btn-secondary" style="background:#6c757d;">Cancel</button>
                    </div>
                </form>
            `;

            openPanel(`Edit TV Licence #${licenceId}`, editFormHtml, null, '550px', '✏️');

            setTimeout(() => {
                const form = document.getElementById('editTVFormModal');
                if (form) {
                    form.onsubmit = async (e) => {
                        e.preventDefault();

                        const updateData = {
                            licence_id: licenceId,
                            class_id: document.getElementById('edit_tv_class_id').value,
                            used_status: document.getElementById('edit_tv_used_status').value,
                            joining_date: document.getElementById('edit_tv_joining_date').value,
                            expiry_date: document.getElementById('edit_tv_expiry_date').value
                        };

                        Swal.fire({ title: 'Updating...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                        const res = await fetch(API_BASE + 'licence_update.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(updateData)
                        });
                        const result = await res.json();

                        if (result.success) {
                            Swal.fire({ icon: 'success', title: 'Updated!', text: result.message, timer: 1500, showConfirmButton: false });
                            closePanel();
                            refreshActiveTVPanel();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Update Failed', text: result.message });
                        }
                    };
                }
            }, 100);

        } catch(e) {
            console.error('Error loading licence:', e);
            toastr.error('Error loading licence details');
        }
    };
    
    // ========== DELETE TV LICENCE FUNCTION ==========
    window.deleteTVLicence = async function(licenceId) {
        const result = await Swal.fire({ 
            title: 'Delete TV Licence?', 
            text: 'This action cannot be undone!', 
            icon: 'warning', 
            showCancelButton: true, 
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });
        
        if (result.isConfirmed) {
            Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            const res = await fetch(API_BASE + 'licence_delete.php', { 
                method: 'POST', 
                headers: { 'Content-Type': 'application/json' }, 
                body: JSON.stringify({ licence_id: licenceId }) 
            });
            const data = await res.json();
            if (data.success) { 
                Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message, timer: 1500, showConfirmButton: false }); 
                refreshActiveTVPanel(); 
            } else { 
                Swal.fire({ icon: 'error', title: 'Delete Failed', text: data.message }); 
            }
        }
    };
    
    async function loadTVHistory() {
        const historyContainer = document.getElementById('tvHistory');
        if (!historyContainer) return;
        
        historyContainer.innerHTML = '<div style="text-align:center; padding:20px;"><div class="spinner" style="margin:0 auto;"></div><p>Loading licences...</p></div>';
        
        try {
            const res = await fetch(API_BASE + `licence_list.php?school_id=${schoolId}&_=${Date.now()}`);
            const data = await res.json();
            
            console.log('TV Licences from API:', data); // Debug log
            
            let tvLicences = [];
            if (data && data.success && data.data && data.data.tv) {
                tvLicences = data.data.tv;
            }
            
            if (tvLicences.length > 0) {
                historyContainer.innerHTML = `
                    <div style="overflow-x: auto;">
                        <table style="width:100%; border-collapse: collapse; font-size:13px;">
                            <thead>
                                <tr style="background:#f8f9fa;">
                                    <th style="padding:10px;">ID</th>
                                    <th style="padding:10px;">Class</th>
                                    <th style="padding:10px;">API Key</th>
                                    <th style="padding:10px;">Used Status</th>
                                    <th style="padding:10px;">Joining Date</th>
                                    <th style="padding:10px;">Expiry Date</th>
                                    <th style="padding:10px;">Status</th>
                                    <th style="padding:10px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${tvLicences.map(l => {
                                    const isDeleted = l.is_deleted == 1;
                                    const apiKeyDisplay = l.api_key && l.api_key !== 'N/A' && l.api_key !== null ? l.api_key : 'Not Set';
                                    return `
                                        <tr style="${isDeleted ? 'background:#fff5f5; opacity:0.7;' : ''}">
                                            <td style="padding:8px;">${l.licence_id}</td>
                                            <td style="padding:8px;">Class ${l.class_id} ${isDeleted ? '<span style="color:#dc3545;">(Deleted)</span>' : ''}</td>
                                            <td style="padding:8px;"><code style="font-size:11px; background:#f0f0f0; padding:4px 8px; border-radius:4px;">${apiKeyDisplay}</code></td>
                                            <td style="padding:8px;">${l.used_status === 'Y' ? '<span class="used-yes">✓ Used</span>' : '<span class="used-no">✗ Not Used</span>'}</td>
                                            <td style="padding:8px;">${l.joining_date || '-'}</td>
                                            <td style="padding:8px;">${l.expiry_date || '-'}</td>
                                            <td style="padding:8px;">${l.expiry_date && new Date(l.expiry_date) > new Date() ? '<span class="status-active">Active</span>' : '<span class="status-inactive">Expired</span>'}</td>
                                            <td style="padding:8px;">
                                                ${!isDeleted ? `<button class="btn btn-info" onclick="viewTVLicence(${l.licence_id})" style="background:#17a2b8; color:white; padding:4px 8px; margin-right:4px;">👁️ View</button><button class="btn btn-warning" onclick="editTVLicence(${l.licence_id})" style="background:#ffc107; color:#333; padding:4px 8px; margin-right:4px;">✏️ Edit</button><button class="btn btn-danger" onclick="deleteTVLicence(${l.licence_id})" style="padding:4px 8px;">🗑️ Delete</button>` : '<span style="color:#666;">Archived</span>'}
                                            </td>
                                        </tr>
                                    `;
                                }).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } else {
                historyContainer.innerHTML = '<p style="padding:15px; text-align:center;">No TV licences found.</p>';
            }
        } catch(e) {
            console.error('Error loading TV licences:', e);
            historyContainer.innerHTML = '<p style="padding:15px; text-align:center; color:#dc3545;">Error loading licences.</p>';
        }
    }
    
    const formHtml = `<div class="toggle-container">
        <button class="toggle-btn active" onclick="showTVToggleContent('tvCreate')">➕ Create TV Licence</button>
        <button class="toggle-btn" onclick="showTVToggleContent('tvHistory')">📜 TV History</button>
    </div>
    <div id="tvCreate" class="toggle-content active-content">${createFormHtml}</div>
    <div id="tvHistory" class="toggle-content">Loading...</div>`;
    
    openPanel(`TV Management - ${escapeHtml(schoolName)}`, formHtml, null, PANEL_WIDTHS.tv, '📺');
    
    window.showTVToggleContent = function(activeId) {
        document.querySelectorAll('#tvCreate, #tvHistory').forEach(content => {
            content.classList.remove('active-content');
        });
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.getElementById(activeId).classList.add('active-content');
        
        if (activeId === 'tvHistory') {
            loadTVHistory();
        }
    };
    
    setTimeout(() => {
        const today = new Date().toISOString().split('T')[0];
        const nextYear = new Date();
        nextYear.setFullYear(nextYear.getFullYear() + 1);

        const classIdEl = document.getElementById('tv_class_id');
        const apiKeyEl = document.getElementById('tv_api_key');

        if (document.getElementById('tv_joining_date')) {
            document.getElementById('tv_joining_date').value = today;
            document.getElementById('tv_expiry_date').value = nextYear.toISOString().split('T')[0];
        }

        if (apiKeyEl && classIdEl) {
            const generateKeyForClass = () => {
                apiKeyEl.value = generateTVApiKey(classIdEl.value);
                console.log('Generated API Key:', apiKeyEl.value);
            };
            generateKeyForClass();
            classIdEl.addEventListener('change', generateKeyForClass);
        }

        const form = document.getElementById('createTVForm');
        if (form) {
            form.onsubmit = async (e) => {
                e.preventDefault();
                clearAllErrors('createTVForm');
                let isValid = true;

                if (!validateRequired('tv_class_id', 'Class')) isValid = false;
                if (!validateRequired('tv_used_status', 'Used Status')) isValid = false;
                if (!validateRequired('tv_joining_date', 'Joining Date')) isValid = false;
                if (!validateRequired('tv_expiry_date', 'Expiry Date')) isValid = false;

                if (!isValid) { return; }

                const confirmResult = await Swal.fire({
                    title: 'Confirm Creation',
                    text: 'Create TV Licence?',
                    icon: 'question',
                    showCancelButton: true
                });

                if (confirmResult.isConfirmed) {
                    Swal.fire({ title: 'Creating...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                    const requestData = {
                        licence_type: 'tv',
                        school_id: schoolId,
                        class_id: document.getElementById('tv_class_id').value,
                        used_status: document.getElementById('tv_used_status').value,
                        joining_date: document.getElementById('tv_joining_date').value,
                        expiry_date: document.getElementById('tv_expiry_date').value,
                        api_key: document.getElementById('tv_api_key').value
                    };
                    
                    console.log('Sending TV Licence data:', requestData);

                    const tvRes = await fetch(API_BASE + 'licence_add.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(requestData)
                    });
                    const tvResult = await tvRes.json();
                    
                    console.log('TV Licence creation result:', tvResult);

                    if (tvResult.success) {
                        Swal.fire({ icon: 'success', title: 'Created!', text: 'TV Licence created successfully', timer: 1500, showConfirmButton: false });
                        refreshActiveTVPanel();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Failed', text: tvResult.message });
                    }
                }
            };
        }
        
        loadTVHistory();
    }, 100);
};
       
       // ========== LMS SECTION ==========
window.openLMSSection = async function(schoolId, schoolName) {
    activeLMSContext = { schoolId, schoolName };
    let classes = [];
    let batches = [];
    
    try {
        const res = await fetch(API_BASE + `classes_list.php?school_id=${schoolId}`);
        const data = await res.json();
        if (data.success && data.data) {
            const seenIds = new Set();
            for (const c of data.data) {
                if (!seenIds.has(c.class_id)) {
                    seenIds.add(c.class_id);
                    classes.push({ class_id: c.class_id, class_name: 'Class ' + c.class_id });
                }
            }
            batches = data.data;
        }
    } catch(e) {}
    
    if (classes.length === 0) {
        classes = [1,2,3,4,5,6,7,8,9,10,11,12].map(i => ({ class_id: i, class_name: 'Class ' + i }));
    }
    
    const classOptions = classes.map(c => `<option value="${c.class_id}">Class ${c.class_id}</option>`).join('');
    
    const createFormHtml = `
        <form id="createLMSForm" novalidate>
            <div class="form-group">
                <label>Class <span class="required">*</span></label>
                <select id="lms_class_id" required>${classOptions}</select>
            </div>
            <div class="form-group">
                <label>Batch / Section</label>
                <select id="lms_batch_id">
                    <option value="">Select Batch/Section</option>
                </select>
            </div>
            <div class="form-group">
                <label>Subscription Type <span class="required">*</span></label>
                <select id="lms_subscription_type" required>
                    <option value="D">Demo</option>
                    <option value="B">Basic</option>
                    <option value="P">Premium</option>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Subscription Quantity <span class="required">*</span></label>
                    <input type="number" id="lms_subscription_qty" value="1" min="1" required>
                </div>
                <div class="form-group">
                    <label>Available Quantity</label>
                    <input type="number" id="lms_available_qty" min="0" step="1">
                    <small>Default: Subscription Quantity - 1</small>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Joining Date <span class="required">*</span></label>
                    <input type="date" id="lms_joining_date" required>
                </div>
                <div class="form-group">
                    <label>Expiry Date <span class="required">*</span></label>
                    <input type="date" id="lms_expiry_date" required>
                </div>
            </div>
            <button type="submit" class="btn-submit">✅ Create LMS Licence</button>
        </form>
    `;
    
    // ========== VIEW LMS LICENCE FUNCTION (Right Panel) ==========
    window.viewLMSLicence = async function(licenceId) {
        try {
            const res = await fetch(API_BASE + `licence_list.php?school_id=${activeLMSContext?.schoolId}&_=${Date.now()}`);
            const data = await res.json();
            
            if (!data.success || !data.data || !data.data.lms) {
                toastr.error('Could not fetch licence details');
                return;
            }
            
            const licence = data.data.lms.find(l => l.licence_id == licenceId);
            if (!licence) {
                toastr.error('Licence not found');
                return;
            }
            
            const status = licence.expiry_date && new Date(licence.expiry_date) > new Date() ? 'Active' : 'Expired';
            const subscriptionText = licence.subscription_type === 'D' ? 'Demo' : licence.subscription_type === 'B' ? 'Basic' : 'Premium';
            
            const detailsHtml = `
                <div style="background:#f8f9fa; padding:20px; border-radius:8px; font-family:monospace; font-size:14px; line-height:1.8;">
                    <p><strong>🔑 Licence ID:</strong> ${licence.licence_id}</p>
                    <p><strong>📚 Class:</strong> ${licence.class_id || '-'}</p>
                    <p><strong>📦 Subscription Type:</strong> ${subscriptionText}</p>
                    <p><strong>📊 Subscription Quantity:</strong> ${licence.subscription_qty || 1}</p>
                    <p><strong>✅ Available Quantity:</strong> ${licence.available_qty || 0}</p>
                    <p><strong>📅 Joining Date:</strong> ${licence.joining_date || '-'}</p>
                    <p><strong>⏰ Expiry Date:</strong> ${licence.expiry_date || '-'}</p>
                    <p><strong>⚡ Status:</strong> <span class="${status === 'Active' ? 'status-active' : 'status-inactive'}">${status}</span></p>
                </div>
                <div style="margin-top: 20px;">
                    <button type="button" onclick="closePanel()" class="btn-submit" style="background:#6c757d;">Close</button>
                </div>
            `;
            
            openPanel(`LMS Licence Details - Class ${licence.class_id}`, detailsHtml, null, '550px', '👁️');
            
        } catch(e) {
            console.error('Error loading licence:', e);
            toastr.error('Error loading licence details');
        }
    };
    
    // ========== EDIT LMS LICENCE FUNCTION ==========
    window.editLMSLicence = async function(licenceId) {
        try {
            const res = await fetch(API_BASE + `licence_list.php?school_id=${activeLMSContext?.schoolId}&_=${Date.now()}`);
            const data = await res.json();
            
            if (!data.success || !data.data || !data.data.lms) {
                toastr.error('Could not fetch licence details');
                return;
            }
            
            const licence = data.data.lms.find(l => l.licence_id == licenceId);
            if (!licence) {
                toastr.error('Licence not found');
                return;
            }
            
            // Fetch classes for dropdown
            let classList = [];
            try {
                const classRes = await fetch(API_BASE + `classes_list.php?school_id=${activeLMSContext?.schoolId}`);
                const classData = await classRes.json();
                if (classData.success && classData.data) {
                    const seen = new Set();
                    classData.data.forEach(c => { if (!seen.has(c.class_id)) { seen.add(c.class_id); classList.push(c); } });
                }
            } catch(e) {}
            if (classList.length === 0) classList = [1,2,3,4,5,6,7,8,9,10,11,12].map(i => ({ class_id: i }));
            
            const formatDate = (date) => {
                if (!date || date === '0000-00-00') return '';
                return date;
            };
            
            const editFormHtml = `
                <form id="editLMSFormModal" novalidate>
                    <input type="hidden" id="edit_licence_id" value="${licence.licence_id}">
                    
                    <div class="form-group">
                        <label>Class <span class="required">*</span></label>
                        <select id="edit_lms_class_id" required class="form-control">
                            ${classList.map(c => `<option value="${c.class_id}" ${c.class_id == licence.class_id ? 'selected' : ''}>Class ${c.class_id}</option>`).join('')}
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Subscription Type <span class="required">*</span></label>
                        <select id="edit_lms_subscription_type" required class="form-control">
                            <option value="D" ${licence.subscription_type === 'D' ? 'selected' : ''}>Demo</option>
                            <option value="B" ${licence.subscription_type === 'B' ? 'selected' : ''}>Basic</option>
                            <option value="P" ${licence.subscription_type === 'P' ? 'selected' : ''}>Premium</option>
                        </select>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Subscription Quantity <span class="required">*</span></label>
                            <input type="number" id="edit_lms_subscription_qty" value="${licence.subscription_qty || 1}" min="1" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Available Quantity</label>
                            <input type="number" id="edit_lms_available_qty" value="${licence.available_qty || 0}" min="0" class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Joining Date <span class="required">*</span></label>
                            <input type="date" id="edit_lms_joining_date" value="${formatDate(licence.joining_date)}" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Expiry Date <span class="required">*</span></label>
                            <input type="date" id="edit_lms_expiry_date" value="${formatDate(licence.expiry_date)}" required class="form-control">
                        </div>
                    </div>
                    
                    <div style="display:flex; gap:10px; margin-top:20px;">
                        <button type="submit" class="btn-submit" style="background:#28a745;">💾 Save Changes</button>
                        <button type="button" onclick="closePanel()" class="btn-secondary" style="background:#6c757d;">Cancel</button>
                    </div>
                </form>
            `;
            
            openPanel(`Edit LMS Licence #${licenceId}`, editFormHtml, null, '600px', '✏️');
            
            setTimeout(() => {
                const form = document.getElementById('editLMSFormModal');
                if (form) {
                    form.onsubmit = async (e) => {
                        e.preventDefault();
                        
                        const subscriptionQty = parseInt(document.getElementById('edit_lms_subscription_qty').value);
                        const availableQty = parseInt(document.getElementById('edit_lms_available_qty').value);
                        
                        const updateData = {
                            licence_id: licenceId,
                            class_id: parseInt(document.getElementById('edit_lms_class_id').value),
                            subscription_type: document.getElementById('edit_lms_subscription_type').value,
                            subscription_qty: subscriptionQty,
                            available_qty: availableQty,
                            joining_date: document.getElementById('edit_lms_joining_date').value,
                            expiry_date: document.getElementById('edit_lms_expiry_date').value
                        };
                        
                        Swal.fire({ title: 'Updating...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                        
                        const res = await fetch(API_BASE + 'licence_update.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(updateData)
                        });
                        const result = await res.json();
                        
                        if (result.success) {
                            Swal.fire({ icon: 'success', title: 'Updated!', text: result.message, timer: 1500, showConfirmButton: false });
                            closePanel();
                            refreshActiveLMSPanel();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Update Failed', text: result.message });
                        }
                    };
                }
            }, 100);
            
        } catch(e) {
            console.error('Error loading licence:', e);
            toastr.error('Error loading licence details');
        }
    };
    
    // ========== DELETE LMS LICENCE FUNCTION ==========
    window.deleteLMSLicence = async function(licenceId) {
        const result = await Swal.fire({
            title: 'Delete LMS Licence?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });
        
        if (result.isConfirmed) {
            Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            
            const res = await fetch(API_BASE + 'licence_delete.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ licence_id: licenceId })
            });
            const data = await res.json();
            
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message, timer: 1500, showConfirmButton: false });
                refreshActiveLMSPanel();
            } else {
                Swal.fire({ icon: 'error', title: 'Delete Failed', text: data.message });
            }
        }
    };
    
    async function loadLMSHistory() {
        const historyContainer = document.getElementById('lmsHistory');
        if (!historyContainer) return;
        
        historyContainer.innerHTML = '<div style="text-align:center; padding:20px;"><div class="spinner" style="margin:0 auto;"></div><p>Loading licences...</p></div>';
        
        try {
            const res = await fetch(API_BASE + `licence_list.php?school_id=${schoolId}&_=${Date.now()}`);
            const data = await res.json();
            
            let licences = [];
            if (data && data.success && data.data && data.data.lms) {
                licences = data.data.lms;
            }
            
            if (licences.length > 0) {
                historyContainer.innerHTML = `
                    <div style="overflow-x: auto;">
                        <table style="width:100%; border-collapse: collapse; font-size:13px;">
                            <thead>
                                <tr style="background:#f8f9fa;">
                                    <th style="padding:10px;">ID</th>
                                    <th style="padding:10px;">Class</th>
                                    <th style="padding:10px;">Subscription</th>
                                    <th style="padding:10px;">Qty/Avail</th>
                                    <th style="padding:10px;">Joining Date</th>
                                    <th style="padding:10px;">Expiry Date</th>
                                    <th style="padding:10px;">Status</th>
                                    <th style="padding:10px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${licences.map(l => `
                                    <tr>
                                        <td style="padding:8px;">${l.licence_id || '-'}</td>
                                        <td style="padding:8px;">Class ${l.class_id || '-'}</td>
                                        <td style="padding:8px;">${l.subscription_type === 'D' ? 'Demo' : l.subscription_type === 'B' ? 'Basic' : 'Premium'}</td>
                                        <td style="padding:8px;">${l.subscription_qty || 1} / ${l.available_qty || 0}</td>
                                        <td style="padding:8px;">${l.joining_date || '-'}</td>
                                        <td style="padding:8px;">${l.expiry_date || '-'}</td>
                                        <td style="padding:8px;">${l.expiry_date && new Date(l.expiry_date) > new Date() ? '<span class="status-active">Active</span>' : '<span class="status-inactive">Expired</span>'}</td>
                                        <td style="padding:8px;">
                                            <button class="btn btn-info" onclick="viewLMSLicence(${l.licence_id})" style="background:#17a2b8; color:white; padding:4px 8px; margin-right:4px;">👁️ View</button>
                                            <button class="btn btn-warning" onclick="editLMSLicence(${l.licence_id})" style="background:#ffc107; color:#333; padding:4px 8px; margin-right:4px;">✏️ Edit</button>
                                            <button class="btn btn-danger" onclick="deleteLMSLicence(${l.licence_id})" style="padding:4px 8px;">🗑️ Delete</button>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } else {
                historyContainer.innerHTML = '<p style="padding:15px; text-align:center;">No LMS licences found.</p>';
            }
        } catch(e) {
            console.error('Error loading LMS licences:', e);
            historyContainer.innerHTML = '<p style="padding:15px; text-align:center; color:#dc3545;">Error loading licences.</p>';
        }
    }
    
    const formHtml = `
        <div class="toggle-container">
            <button class="toggle-btn active" onclick="showLmsToggleContent('lmsCreate')">➕ Create LMS Licence</button>
            <button class="toggle-btn" onclick="showLmsToggleContent('lmsHistory')">📜 LMS History</button>
        </div>
        <div id="lmsCreate" class="toggle-content active-content">${createFormHtml}</div>
        <div id="lmsHistory" class="toggle-content">Loading...</div>
    `;
    
    openPanel(`LMS Management - ${escapeHtml(schoolName)}`, formHtml, null, PANEL_WIDTHS.lms, '📚');
    
    window.showLmsToggleContent = function(activeId) {
        document.querySelectorAll('#lmsCreate, #lmsHistory').forEach(content => {
            content.classList.remove('active-content');
        });
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.getElementById(activeId).classList.add('active-content');
        
        if (activeId === 'lmsHistory') {
            loadLMSHistory();
        }
    };
    
    setTimeout(() => {
        const today = new Date().toISOString().split('T')[0];
        const nextYear = new Date(); 
        nextYear.setFullYear(nextYear.getFullYear() + 1);
        
        if (document.getElementById('lms_joining_date')) {
            document.getElementById('lms_joining_date').value = today;
            document.getElementById('lms_expiry_date').value = nextYear.toISOString().split('T')[0];
        }
        
        const subscriptionQtyInput = document.getElementById('lms_subscription_qty');
        const availableQtyInput = document.getElementById('lms_available_qty');
        
        if (subscriptionQtyInput && availableQtyInput) {
            subscriptionQtyInput.addEventListener('input', function() {
                const subscriptionQty = parseInt(this.value) || 0;
                availableQtyInput.value = subscriptionQty > 0 ? subscriptionQty - 1 : 0;
            });
            availableQtyInput.value = 0;
        }
        
        const classSelect = document.getElementById('lms_class_id');
        const batchSelect = document.getElementById('lms_batch_id');
        
        if (classSelect && batchSelect) {
            function updateBatches() {
                const selectedClass = classSelect.value;
                const filteredBatches = batches.filter(b => b.class_id == selectedClass);
                batchSelect.innerHTML = '<option value="">Select Batch/Section</option>';
                filteredBatches.forEach(b => {
                    batchSelect.innerHTML += `<option value="${b.batch_id}">${escapeHtml(b.display_name)}</option>`;
                });
            }
            classSelect.addEventListener('change', updateBatches);
            updateBatches();
        }
        
        const form = document.getElementById('createLMSForm');
        if (form) {
            form.onsubmit = async (e) => {
                e.preventDefault();
                clearAllErrors('createLMSForm');
                
                const subscriptionQty = parseInt(document.getElementById('lms_subscription_qty').value);
                const availableQty = parseInt(document.getElementById('lms_available_qty').value) || (subscriptionQty > 0 ? subscriptionQty - 1 : 0);
                
                const formData = {
                    licence_type: 'lms',
                    school_id: parseInt(schoolId),
                    class_id: parseInt(document.getElementById('lms_class_id').value),
                    batch_id: document.getElementById('lms_batch_id').value || null,
                    subscription_type: document.getElementById('lms_subscription_type').value,
                    subscription_qty: subscriptionQty,
                    available_qty: availableQty,
                    joining_date: document.getElementById('lms_joining_date').value,
                    expiry_date: document.getElementById('lms_expiry_date').value,
                    order_id: `ORD-${schoolId}-${Date.now()}`,
                    amount: 0,
                    paid_amount: 0,
                    discount: 0,
                    gateway_charges: 0,
                    tax: 0,
                    currency: 'INR',
                    payment_method: null
                };
                
                const confirmResult = await Swal.fire({
                    title: 'Confirm Creation',
                    text: 'Create LMS Licence?',
                    icon: 'question',
                    showCancelButton: true
                });
                
                if (confirmResult.isConfirmed) {
                    Swal.fire({ title: 'Creating...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    
                    const res = await fetch(API_BASE + 'licence_add.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(formData)
                    });
                    const result = await res.json();
                    
                    if (result.success) {
                        Swal.fire({ icon: 'success', title: 'LMS Licence Created!', text: result.message });
                        setTimeout(() => {
                            loadLMSHistory();
                            refreshActiveLMSPanel();
                        }, 1000);
                        form.reset();
                        if (document.getElementById('lms_joining_date')) document.getElementById('lms_joining_date').value = today;
                        if (document.getElementById('lms_expiry_date')) document.getElementById('lms_expiry_date').value = nextYear.toISOString().split('T')[0];
                        if (subscriptionQtyInput) subscriptionQtyInput.value = 1;
                        if (availableQtyInput) availableQtyInput.value = 0;
                    } else {
                        Swal.fire({ icon: 'error', title: 'Creation Failed', text: result.message });
                    }
                }
            };
        }
        
        loadLMSHistory();
    }, 100);
};
        // ========== MANAGE CLASSES ==========
        window.manageClasses = async function(schoolId, schoolName) {
            activeClassesContext = { schoolId, schoolName };
            let existingClasses = [];
            try {
                const res = await fetch(API_BASE + `classes_list.php?school_id=${schoolId}`);
                const data = await res.json();
                if (data.success && data.data) existingClasses = data.data;
            } catch(e) { console.error('Error loading classes:', e); }
            
            const classOptions = [1,2,3,4,5,6,7,8,9,10,11,12].map(i => `<option value="${i}">Class ${i}</option>`).join('');
            
            const classesTableHtml = existingClasses.length > 0 ? 
                `<div style="overflow-x: auto;">
                    <table style="width:100%; border-collapse: collapse; margin-top:15px;">
                        <thead>
                            <tr><th>Class</th><th>Section</th><th>Board</th><th>Academic Year</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            ${existingClasses.map(c => `
                                <tr>
                                    <td style="padding:8px;">${c.class_id}</td>
                                    <td style="padding:8px;">${c.section || 'A'}</td>
                                    <td style="padding:8px;">${c.board_id === 'C' ? 'CBSE' : c.board_id === 'I' ? 'ICSE' : 'WBBSE'}</td>
                                    <td style="padding:8px;">${c.academic_year || '2025'}</td>
                                    <td style="padding:8px;"><button class="btn btn-danger" onclick="deleteClass(${c.batch_id})">🗑️ Delete</button></td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>` : '<p>No classes found. Add classes below.</p>';
            
            const formHtml = `
                <form id="addClassForm" novalidate>
                    <div class="form-row">
                        <div class="form-group"><label>Class Number *</label><select id="class_id">${classOptions}</select></div>
                        <div class="form-group"><label>Section *</label><input type="text" id="section" value="A" placeholder="Section" required></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label>Board</label><select id="class_board_id"><option value="C">CBSE</option><option value="I">ICSE</option><option value="W">WBBSE</option></select></div>
                        <div class="form-group"><label>Academic Year</label><input type="number" id="academic_year" value="2025"></div>
                    </div>
                    <button type="submit" class="btn-submit">➕ Add Class</button>
                </form>
                <hr>
                <h4>Existing Classes</h4>
                ${classesTableHtml}
            `;
            
            openPanel(`Manage Classes - ${escapeHtml(schoolName)}`, formHtml, null, PANEL_WIDTHS.classes, '📚');
            
            setTimeout(() => {
                const form = document.getElementById('addClassForm');
                if (form) {
                    form.onsubmit = async (e) => {
                        e.preventDefault();
                        
                        const classId = document.getElementById('class_id').value;
                        const section = document.getElementById('section').value.trim();
                        
                        if (!classId || !section) {
                            toastr.error('Class and Section are required');
                            return;
                        }
                        
                        const formData = {
                            school_id: schoolId,
                            class_id: classId,
                            section: section,
                            board_id: document.getElementById('class_board_id').value,
                            academic_year: document.getElementById('academic_year').value,
                            student_count: 100,
                            class_name: `Class ${classId} - Section ${section}`
                        };
                        
                        Swal.fire({ title: 'Adding...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                        
                        const res = await fetch(API_BASE + 'class_add.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(formData)
                        });
                        const result = await res.json();
                        
                        if (result.success) {
                            Swal.fire({ icon: 'success', title: 'Added!', text: result.message });
                            refreshActiveClassesPanel();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Failed', text: result.message });
                        }
                    };
                }
            }, 100);
        };
        
        window.deleteClass = async function(batchId) {
            const result = await Swal.fire({ title: 'Delete Class?', text: 'Are you sure?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33' });
            if (result.isConfirmed) {
                Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const res = await fetch(API_BASE + 'class_delete.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ batch_id: batchId }) });
                const data = await res.json();
                if (data.success) { Swal.fire({ icon: 'success', title: 'Deleted!' }); refreshActiveClassesPanel(); }
                else { Swal.fire({ icon: 'error', title: 'Failed', text: data.message }); }
            }
        };
        
        // ========== CREATE SCHOOL ==========
window.openCreateSchool = function() {
    // Generate academic year options (current year to next 10 years)
    const currentYear = new Date().getFullYear();
    let academicYearOptions = '';
    for (let i = currentYear; i <= currentYear + 10; i++) {
        academicYearOptions += `<option value="${i}">${i}</option>`;
    }
    
    const formHtml = `<form id="createSchoolForm" novalidate>
        <div class="form-row">
            <div class="form-group"><label>School Code *</label><input type="text" id="create_school_code" placeholder="Unique code"></div>
            <div class="form-group"><label>School Name *</label><input type="text" id="create_school_name" placeholder="Full name"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Board *</label><select id="create_board_id"><option value="C">CBSE</option><option value="I">ICSE</option><option value="W">WBBSE</option></select></div>
            <div class="form-group"><label>Academic Year *</label>
                <select id="create_academic_year" required>
                    <option value="">Select Academic Year</option>
                    ${academicYearOptions}
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Status *</label><select id="create_status"><option value="A">Active</option><option value="I">Inactive</option></select></div>
        </div>
        <div class="form-group"><textarea id="create_address" rows="2" placeholder="Address"></textarea></div>
        <div class="form-row">
            <div class="form-group"><label>City *</label><input type="text" id="create_city" placeholder="City" required></div>
            <div class="form-group"><label>State *</label><input type="text" id="create_state" placeholder="State" required></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Postal Code</label><input type="text" id="create_postal_code" placeholder="Postal Code"></div>
            <div class="form-group"><label>Country *</label><select id="create_country_code"><option value="IN">India (+91)</option><option value="US">USA (+1)</option><option value="UK">UK (+44)</option></select></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Contact Person</label><input type="text" id="create_contact_person"></div>
            <div class="form-group"><label>Contact Email</label><input type="email" id="create_contact_email"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Contact Phone</label><input type="tel" id="create_contact_phone"></div>
            <div class="form-group"><label>GST Number</label><input type="text" id="create_gst_number"></div>
        </div>
        <button type="submit" class="btn-submit">🏫 Create School</button>
    </form>`;
    
    openPanel('Create New School', formHtml, loadSchools, PANEL_WIDTHS.school, '🏫');
    
    setTimeout(() => {
        document.getElementById('createSchoolForm').onsubmit = async (e) => {
            e.preventDefault();
            
            // Validate required fields including academic year
            const schoolCode = document.getElementById('create_school_code').value.trim().toUpperCase();
            const schoolName = document.getElementById('create_school_name').value.trim();
            const boardId = document.getElementById('create_board_id').value;
            const academicYear = document.getElementById('create_academic_year').value;
            const city = document.getElementById('create_city').value;
            const state = document.getElementById('create_state').value;
            
            if (!schoolCode) {
                toastr.error('School Code is required');
                return;
            }
            if (!schoolName) {
                toastr.error('School Name is required');
                return;
            }
            if (!academicYear) {
                toastr.error('Academic Year is required');
                return;
            }
            if (!city) {
                toastr.error('City is required');
                return;
            }
            if (!state) {
                toastr.error('State is required');
                return;
            }
            
            const result = await createSchool({
                school_code: schoolCode,
                school_name: schoolName,
                board_id: boardId,
                academic_year: academicYear,
                status: document.getElementById('create_status').value,
                address: document.getElementById('create_address').value,
                city: city,
                state: state,
                postal_code: document.getElementById('create_postal_code').value,
                country_code: document.getElementById('create_country_code').value,
                contact_person: document.getElementById('create_contact_person').value,
                contact_email: document.getElementById('create_contact_email').value,
                contact_phone: document.getElementById('create_contact_phone').value,
                gst_number: document.getElementById('create_gst_number').value
            });
            
            if (result.success) { 
                Swal.fire({ icon: 'success', title: 'Success!', text: result.message }); 
                closePanel(); 
                loadSchools(); 
            } else { 
                Swal.fire({ icon: 'error', title: 'Failed', text: result.message }); 
            }
        };
    }, 100);
};

        
        
        window.openEditSchool = async function(schoolId) {
    try {
        const res = await fetch(API_BASE + 'school_list.php');
        const result = await res.json();
        const school = result.data.find(s => s.school_id == schoolId);
        if (!school) { toastr.error('School not found'); return; }
        
        // Generate academic year options
        const currentYear = new Date().getFullYear();
        let academicYearOptions = '';
        for (let i = currentYear; i <= currentYear + 10; i++) {
            const selected = (school.academic_year == i) ? 'selected' : '';
            academicYearOptions += `<option value="${i}" ${selected}>${i}</option>`;
        }
        
        const formHtml = `<form id="editSchoolForm" novalidate>
            <input type="hidden" id="edit_school_id" value="${school.school_id}">
            <div class="form-row">
                <div class="form-group"><label>School Code</label><input type="text" value="${escapeHtml(school.school_code)}" readonly class="readonly-field"></div>
                <div class="form-group"><label>School Name *</label><input type="text" id="edit_school_name" value="${escapeHtml(school.school_name)}" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Board</label><select id="edit_board_id"><option value="C" ${school.board_id === 'C' ? 'selected' : ''}>CBSE</option><option value="I" ${school.board_id === 'I' ? 'selected' : ''}>ICSE</option><option value="W" ${school.board_id === 'W' ? 'selected' : ''}>WBBSE</option></select></div>
                <div class="form-group"><label>Academic Year *</label>
                    <select id="edit_academic_year" required>
                        <option value="">Select Academic Year</option>
                        ${academicYearOptions}
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Status</label><select id="edit_status"><option value="A" ${school.status === 'A' ? 'selected' : ''}>Active</option><option value="I" ${school.status === 'I' ? 'selected' : ''}>Inactive</option></select></div>
            </div>
            <div class="form-group"><textarea id="edit_address" rows="2">${escapeHtml(school.address || '')}</textarea></div>
            <div class="form-row">
                <div class="form-group"><label>City</label><input type="text" id="edit_city" value="${escapeHtml(school.city || '')}"></div>
                <div class="form-group"><label>State</label><input type="text" id="edit_state" value="${escapeHtml(school.state || '')}"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Postal Code</label><input type="text" id="edit_postal_code" value="${escapeHtml(school.postal_code || '')}"></div>
                <div class="form-group"><label>Country</label><select id="edit_country_code"><option value="IN" ${school.country_code === 'IN' ? 'selected' : ''}>India</option><option value="US" ${school.country_code === 'US' ? 'selected' : ''}>USA</option><option value="UK" ${school.country_code === 'UK' ? 'selected' : ''}>UK</option></select></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Contact Person</label><input type="text" id="edit_contact_person" value="${escapeHtml(school.contact_person || '')}"></div>
                <div class="form-group"><label>Contact Email</label><input type="email" id="edit_contact_email" value="${escapeHtml(school.contact_email || '')}"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Contact Phone</label><input type="tel" id="edit_contact_phone" value="${escapeHtml(school.contact_phone || '')}"></div>
                <div class="form-group"><label>GST Number</label><input type="text" id="edit_gst_number" value="${escapeHtml(school.gst_number || '')}"></div>
            </div>
            <button type="submit" class="btn-submit">✏️ Update School</button>
        </form>`;
        
        openPanel(`Edit School: ${school.school_code}`, formHtml, loadSchools, PANEL_WIDTHS.school, '✏️');
        
        setTimeout(() => {
            document.getElementById('editSchoolForm').onsubmit = async (e) => {
                e.preventDefault();
                
                const academicYear = document.getElementById('edit_academic_year').value;
                if (!academicYear) {
                    toastr.error('Academic Year is required');
                    return;
                }
                
                const result = await updateSchool({
                    school_id: document.getElementById('edit_school_id').value,
                    school_name: document.getElementById('edit_school_name').value.trim(),
                    board_id: document.getElementById('edit_board_id').value,
                    academic_year: academicYear,
                    status: document.getElementById('edit_status').value,
                    address: document.getElementById('edit_address').value,
                    city: document.getElementById('edit_city').value,
                    state: document.getElementById('edit_state').value,
                    postal_code: document.getElementById('edit_postal_code').value,
                    country_code: document.getElementById('edit_country_code').value,
                    contact_person: document.getElementById('edit_contact_person').value,
                    contact_email: document.getElementById('edit_contact_email').value,
                    contact_phone: document.getElementById('edit_contact_phone').value,
                    gst_number: document.getElementById('edit_gst_number').value
                });
                if (result.success) { 
                    Swal.fire({ icon: 'success', title: 'Updated!', text: result.message }); 
                    closePanel(); 
                    loadSchools(); 
                } else { 
                    Swal.fire({ icon: 'error', title: 'Failed', text: result.message }); 
                }
            };
        }, 100);
    } catch(e) { toastr.error('Error loading school'); }
};                    
        
        window.openLicenceSection = async function(schoolId, schoolName) {
            let tvLicences = [], lmsLicences = [];
            try {
                const licenceRes = await fetch(API_BASE + `licence_list.php?school_id=${schoolId}`);
                const licenceData = await licenceRes.json();
                if (licenceData.success) {
                    tvLicences = licenceData.data?.tv || [];
                    lmsLicences = licenceData.data?.lms || [];
                }
            } catch(e) {}
            
            let tvTableHtml = '<p>No TV licences found.</p>';
            if (tvLicences.length > 0) {
                tvTableHtml = `<table style="width:100%"><thead><tr><th>Class</th><th>Used Status</th><th>Joining Date</th><th>Expiry Date</th><th>Status</th></tr></thead><tbody>${tvLicences.map(l => `<tr><td>Class ${l.class_id}</td><td>${l.used_status === 'Y' ? '✓ Used' : '✗ Not Used'}</td><td>${l.joining_date}</td><td>${l.expiry_date}</td><td>${new Date(l.expiry_date) > new Date() ? '<span style="color:green;">Active</span>' : '<span style="color:red;">Expired</span>'}</td></tr>`).join('')}</tbody></table>`;
            }
            
            let lmsTableHtml = '<p>No LMS licences found.</p>';
            if (lmsLicences.length > 0) {
                lmsTableHtml = `<table style="width:100%"><thead><tr><th>Class</th><th>Subscription</th><th>Qty/Avail</th><th>Joining Date</th><th>Expiry Date</th><th>Status</th></tr></thead><tbody>${lmsLicences.map(l => `<td>Class ${l.class_id}</td><td>${l.subscription_type === 'D' ? 'Demo' : l.subscription_type === 'B' ? 'Basic' : 'Premium'}</td><td>${l.subscription_qty || 1}/${l.available_qty || 0}</td><td>${l.joining_date}</td><td>${l.expiry_date}</td><td>${new Date(l.expiry_date) > new Date() ? '<span style="color:green;">Active</span>' : '<span style="color:red;">Expired</span>'}</td></tr>`).join('')}</tbody></table>`;
            }
            
            const formHtml = `<h3>📺 TV Licences</h3>${tvTableHtml}<hr><h3>📚 LMS Licences</h3>${lmsTableHtml}`;
            openPanel(`Licences - ${escapeHtml(schoolName)}`, formHtml, null, PANEL_WIDTHS.licence, '📜');
        };
        
        // ========== SCHOOL LIST FUNCTIONS ==========
        let allSchools = [];
        let currentFilters = { search: '', academicYear: '', board: '' };
        
        async function fetchSchoolAcademicYear(schoolId) {
            try {
                const res = await fetch(API_BASE + `classes_list.php?school_id=${schoolId}`);
                const data = await res.json();
                if (data.success && data.data && data.data.length > 0) {
                    const academicYears = [...new Set(data.data.map(c => c.academic_year).filter(year => year))];
                    return academicYears.length > 0 ? academicYears.join(', ') : 'Not Set';
                }
                return 'Not Set';
            } catch(e) { return 'Not Set'; }
        }
        
        async function loadSchools() {
            try {
                const res = await fetch(API_BASE + 'school_list.php');
                const result = await res.json();
                if (result.success) {
                    const schoolsWithYears = [];
                    for (const school of result.data) {
                        const academicYear = await fetchSchoolAcademicYear(school.school_id);
                        schoolsWithYears.push({ ...school, academic_year: academicYear });
                    }
                    allSchools = schoolsWithYears;
                    renderSchoolsTable(allSchools);
                    populateAcademicYearFilter(allSchools);
                    document.getElementById('loadingDiv').style.display = 'none';
                    document.getElementById('tableContainer').style.display = 'block';
                }
            } catch(e) { toastr.error('Error loading schools'); }
        }
        
        function populateAcademicYearFilter(schools) {
            const academicYearsSet = new Set();
            schools.forEach(school => {
                if (school.academic_year && school.academic_year !== 'Not Set') {
                    school.academic_year.split(', ').forEach(year => academicYearsSet.add(year));
                }
            });
            const academicYearSelect = document.getElementById('searchAcademicYear');
            if (academicYearSelect) {
                while (academicYearSelect.options.length > 1) academicYearSelect.remove(1);
                Array.from(academicYearsSet).sort().forEach(year => {
                    const option = document.createElement('option');
                    option.value = year;
                    option.textContent = year;
                    academicYearSelect.appendChild(option);
                });
            }
        }
        
        function renderSchoolsTable(schools) {
            const tbody = document.getElementById('schoolsList');
            if (schools.length === 0) { 
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">No schools found.</td></tr>';
                return;
            }
            tbody.innerHTML = schools.map(school => `
                <tr>
                    <td>${school.school_id}</td>
                    <td><strong>${escapeHtml(school.school_code)}</strong></td>
                    <td>${escapeHtml(school.school_name)}</td>
                    <td>${escapeHtml(school.board_name)}</td>
                    <td>${escapeHtml(school.academic_year || 'Not Set')}</td>
                    <td><span class="status-${school.status === 'A' ? 'active' : 'inactive'}">${school.status === 'A' ? 'Active' : 'Inactive'}</span></td>
                    <td class="action-buttons">
                        <button class="btn btn-secondary" onclick="openEditSchool(${school.school_id})">✏️ Edit</button>
                        <button class="btn btn-secondary" onclick="manageClasses(${school.school_id}, '${escapeHtml(school.school_name)}')">📚 Classes</button>
                        <button class="btn btn-secondary" onclick="openLicenceSection(${school.school_id}, '${escapeHtml(school.school_name)}')">📜 Licence</button>
                        <button class="btn btn-secondary" onclick="openTVSection(${school.school_id}, '${escapeHtml(school.school_name)}')">📺 TV</button>
                        <button class="btn btn-secondary" onclick="openLMSSection(${school.school_id}, '${escapeHtml(school.school_name)}')">📚 LMS</button>
                        <button class="btn btn-secondary" onclick="openUserManagement(${school.school_id}, '${escapeHtml(school.school_name)}')">👥 Users</button>
                    </td>
                </tr>
            `).join('');
        }
        
        function applyFilters() {
            currentFilters.search = document.getElementById('searchSchool').value.toLowerCase().trim();
            currentFilters.academicYear = document.getElementById('searchAcademicYear').value;
            currentFilters.board = document.getElementById('searchBoard').value;
            
            const filtered = allSchools.filter(school => {
                if (currentFilters.search && !school.school_code.toLowerCase().includes(currentFilters.search) && !school.school_name.toLowerCase().includes(currentFilters.search)) return false;
                if (currentFilters.academicYear && (!school.academic_year || !school.academic_year.includes(currentFilters.academicYear))) return false;
                if (currentFilters.board && school.board_name !== currentFilters.board) return false;
                return true;
            });
            renderSchoolsTable(filtered);
        }
        
        function resetFilters() {
            document.getElementById('searchSchool').value = '';
            document.getElementById('searchAcademicYear').value = '';
            document.getElementById('searchBoard').value = '';
            currentFilters = { search: '', academicYear: '', board: '' };
            renderSchoolsTable(allSchools);
            toastr.info('All filters cleared');
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('createBtn').onclick = () => window.openCreateSchool();
            document.getElementById('applySearchBtn').onclick = applyFilters;
            document.getElementById('resetSearchBtn').onclick = resetFilters;
            document.getElementById('searchSchool').addEventListener('keypress', function(e) { if (e.key === 'Enter') applyFilters(); });
            loadSchools();
        });
    </script>
</body>
</html>