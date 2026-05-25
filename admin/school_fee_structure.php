<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutorix - School Fee Structure</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
        
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px; }
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
        .btn-info { background: #17a2b8; color: white; }
        .btn-info:hover { background: #138496; }
        
        table { width: 100%; background: white; border-collapse: collapse; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e9ecef; }
        th { background: #f8f9fa; font-weight: 600; color: #495057; }
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
        .form-group label .required { color: #dc3545; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;
            transition: all 0.2s;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none; border-color: #4361ee; box-shadow: 0 0 0 3px rgba(67,97,238,0.1);
        }
        .form-group input.error-field, .form-group select.error-field {
            border: 2px solid #dc3545 !important;
            background: #fff0f0 !important;
        }
        .form-row { display: flex; gap: 15px; flex-wrap: wrap; }
        .form-row .form-group { flex: 1; min-width: 150px; }
        
        .btn-submit { width: 100%; padding: 12px; background: #4361ee; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 500; transition: all 0.2s; }
        .btn-submit:hover { background: #3a56d4; }
        
        .loading { text-align: center; padding: 50px; }
        .spinner { border: 3px solid #f3f3f3; border-top: 3px solid #4361ee; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 15px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        .action-buttons { display: flex; flex-wrap: wrap; gap: 5px; }
        
        .group-box {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e0e0e0;
        }

        .group-title {
            font-weight: 600;
            color: #4361ee;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .group-number {
            display: inline-block;
            background: #4361ee;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            text-align: center;
            line-height: 24px;
            font-size: 12px;
        }

        .group-total-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .group-total {
            font-size: 13px;
            font-weight: 600;
            color: #28a745;
            background: #d4edda;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .remove-group-btn {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .remove-group-btn:hover {
            background: #c82333;
            transform: scale(1.05);
        }

        .fee-select-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        .fee-select-row select {
            flex: 1;
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 12px;
            max-width: 250px;
            min-width: 180px;
        }
        .add-fee-to-group-btn {
            background: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            width: 30px;
            height: 30px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .add-fee-to-group-btn:hover {
            background: #218838;
            transform: scale(1.05);
        }

        .fee-items-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .fee-item-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 30px;
            padding: 4px 8px 4px 10px;
            transition: all 0.2s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .fee-item-chip:hover {
            border-color: #4361ee;
            box-shadow: 0 2px 4px rgba(67,97,238,0.1);
        }
        .fee-name-chip {
            font-size: 11px;
            font-weight: 500;
            color: #333;
        }
        .fee-amount-chip {
            font-size: 10px;
            font-weight: 600;
            color: #28a745;
            background: #e8f5e9;
            padding: 2px 5px;
            border-radius: 20px;
        }
        .remove-fee-chip {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            cursor: pointer;
            font-size: 11px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .remove-fee-chip:hover {
            background: #c82333;
            transform: scale(1.1);
        }
        
        .pagination-container { margin-top: 25px; display: flex; justify-content: center; align-items: center; gap: 8px; flex-wrap: wrap; }
        .pagination-btn { padding: 8px 14px; border: 1px solid #ddd; background: white; border-radius: 6px; cursor: pointer; transition: all 0.2s; font-size: 14px; min-width: 40px; text-align: center; }
        .pagination-btn:hover:not(:disabled):not(.active) { background: #4361ee; color: white; border-color: #4361ee; }
        .pagination-btn.active { background: #4361ee; color: white; border-color: #4361ee; cursor: default; }
        .pagination-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .per-page-select { margin-left: 15px; padding: 6px 10px; border-radius: 6px; border: 1px solid #ddd; cursor: pointer; }
        .pagination-info { text-align: center; margin-top: 15px; color: #666; font-size: 13px; }
        
        .rightpanel-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5); z-index: 9998; display: none; opacity: 0;
            transition: opacity 0.3s ease;
        }
        .rightpanel-drawer {
            position: fixed; top: 0; right: 0; height: 100%; background: white;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1); z-index: 9999;
            display: flex; flex-direction: column; transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        
        .swal2-container { z-index: 20000 !important; }
        
        .info-message {
            background: #e3f2fd;
            border: 1px solid #90caf9;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
            color: #1565c0;
        }
        
        .field-hint {
            display: block;
            font-size: 11px;
            color: #6c757d;
            margin-top: 4px;
        }
        
        /* Fee Name Manager Styles - Table Layout */
        .fee-manager-section {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .fee-manager-title {
            font-weight: 600;
            color: #4361ee;
            font-size: 14px;
            margin-bottom: 12px;
        }
        .fee-input-row {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        .fee-input-row input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 13px;
        }
        .fee-names-table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }
        .fee-names-table th,
        .fee-names-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        .fee-names-table th {
            background: #f8f9fa;
            font-weight: 600;
        }
        .fee-names-table td:first-child {
            width: 60px;
        }
        .fee-names-table td:nth-child(2) {
            width: auto;
        }
        .fee-names-table td:nth-child(3) {
            width: 120px;
        }
        .fee-names-table td:last-child {
            width: 100px;
            white-space: nowrap;
        }
        .btn-sm {
            padding: 4px 10px;
            font-size: 12px;
            margin: 0 3px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: inline-block;
        }
        .btn-warning {
            background: #ffc107;
            color: #333;
        }
        .btn-warning:hover {
            background: #e0a800;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        
        .optional-text {
            font-size: 11px;
            color: #6c757d;
            margin-left: 5px;
            font-weight: normal;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Tutorix</h2>
            </div>
            <div class="nav-menu">
                <a href="school-management.php" class="nav-item">
                    <span class="icon">🏫</span>
                    <span>School Management</span>
                </a>
                <a href="holidays.php" class="nav-item">
                    <span class="icon">📅</span>
                    <span>School Holidays</span>
                </a>
                <a href="school-fee-structure.php" class="nav-item active">
                    <span class="icon">💰</span>
                    <span>School Fee Structure</span>
                </a>
            </div>
        </div>
        
        <div class="main-content">
            <div class="container">
                <div class="header">
                    <h1>💰 School Fee Structure</h1>
                    <div style="display: flex; gap: 10px;">
                        <button class="btn btn-primary" id="manageFeeNamesBtn"> Fee Names</button>
                        <button class="btn btn-primary" id="createFeeBtn">+ Create Fee</button>
                    </div>
                </div>
                
                <div class="filter-section">
                    <h3 style="margin-bottom: 15px;">🔍 Filter Fee Structures</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>School Name / Code</label>
                            <select id="filter_school_id">
                                <option value="">All Schools</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Academic Year</label>
                            <select id="filter_academic_year">
                                <option value="">All Years</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Board</label>
                            <select id="filter_board_id">
                                <option value="">All Boards</option>
                                <option value="C">CBSE</option>
                                <option value="I">ICSE</option>
                                <option value="W">WBBSE</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Class</label>
                            <select id="filter_class_id">
                                <option value="">All Classes</option>
                                <?php for($i=1;$i<=12;$i++) echo "<option value='$i'>Class $i</option>"; ?>
                            </select>
                        </div>
                        <div class="form-group" style="display: flex; align-items: flex-end;">
                            <button id="applyFilterBtn" class="btn btn-primary" style="padding: 10px 25px;">Apply Filter</button>
                            <button id="resetFilterBtn" class="btn btn-primary" style="padding: 10px 25px; margin-left: 10px; background: #6c757d;">Reset</button>
                        </div>
                    </div>
                </div>
                
                <div id="infoMessage" class="info-message" style="display: none;">
                     Please apply filters to view fee structures
                </div>
                
                <div id="loadingDiv" class="loading" style="display: none;">
                    <div class="spinner"></div>
                    <p>Loading fee structures...</p>
                </div>
                
                <div id="tableContainer" style="display: none;">
                    <div style="overflow-x: auto;">
                        <table style="min-width: 1000px;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>School</th>
                                    <th>Board</th>
                                    <th>Class</th>
                                    <th>Group ID</th>
                                    <th>Fee Name</th>
                                    <th>Amount (₹)</th>
                                    <th>Academic Year</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="feeStructuresList"></tbody>
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
        let groups = [];
        let allFees = [];
        let hasFilterApplied = false;
        let schoolClasses = {};
        let editingFeeStructureId = null;
        let feeNamesCurrentPage = 1;
        let feeNamesTotalPages = 1;
        let feeNamesPerPage = 10;
        let feeNamesTotalRows = 0;
        let allFeeNames = [];
        
        function escapeHtml(str) { if (!str) return ''; return String(str).replace(/[&<>]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[m])); }
        
        function formatDate(dateStr) {
            if (!dateStr || dateStr === '0000-00-00 00:00:00') return '-';
            return dateStr;
        }
        
        function openPanel(title, contentHtml, onClose, width = '600px', icon = '💰') {
            return RightPanel.open({ title, icon, width, html: contentHtml, onClose });
        }
        function closePanel() { RightPanel.closeTop(); }
        
        // ========== GLOBAL FUNCTIONS FOR GROUP MANAGEMENT ==========
        function addGroup() { 
            groups.push({ feeItems: [] }); 
            renderGroups(); 
        }
        
        function removeGroup(groupIdx) { 
            groups.splice(groupIdx, 1); 
            renderGroups(); 
            toastr.success(`Group ${groupIdx + 1} removed`);
        }
        
        function addFeeToGroup(groupIdx) {
            const select = document.getElementById(`fee_select_${groupIdx}`);
            if (!select) return;
            const selectedOption = select.options[select.selectedIndex];
            const feeName = selectedOption.value;
            const amount = parseFloat(selectedOption.getAttribute('data-amount'));
            
            if (!feeName) {
                toastr.error('Please select a fee first');
                return;
            }
            
            if (!groups[groupIdx]) return;
            
            const exists = groups[groupIdx].feeItems.some(item => item.name === feeName);
            if (exists) {
                toastr.warning('This fee is already added to the group');
                return;
            }
            
            groups[groupIdx].feeItems.push({ name: feeName, amount: amount });
            renderGroups();
            toastr.success(`Added "${feeName}" (₹${amount}) to Group ${groupIdx + 1}`);
        }
        
        function removeFeeItemFromGroup(groupIdx, itemIdx) { 
            if (groups[groupIdx]) { 
                const feeName = groups[groupIdx].feeItems[itemIdx].name;
                groups[groupIdx].feeItems.splice(itemIdx, 1); 
                renderGroups(); 
                if (feeName) {
                    toastr.info(`Removed "${feeName}" from group`);
                }
            } 
        }
        // ========== END GLOBAL FUNCTIONS ==========
        
        async function loadSchoolClasses(schoolId) {
            if (!schoolId) return [];
            if (schoolClasses[schoolId]) return schoolClasses[schoolId];
            
            try {
                const res = await fetch(`${API_BASE}classes_list.php?school_id=${schoolId}`);
                const data = await res.json();
                if (data.success && data.data) {
                    const uniqueClasses = [...new Set(data.data.map(c => parseInt(c.class_id)))];
                    uniqueClasses.sort((a, b) => a - b);
                    schoolClasses[schoolId] = uniqueClasses;
                    return uniqueClasses;
                }
                return [];
            } catch(e) {
                console.error('Error loading classes:', e);
                return [];
            }
        }
        
        async function updateClassDropdown(schoolId) {
            const classSelect = document.getElementById('fee_class_id');
            if (!classSelect) return;
            
            classSelect.innerHTML = '<option value="">Loading classes...</option>';
            classSelect.disabled = true;
            
            const classes = await loadSchoolClasses(schoolId);
            classSelect.disabled = false;
            
            if (classes.length > 0) {
                classSelect.innerHTML = '<option value="">Select Class</option>';
                classes.forEach(classId => {
                    classSelect.innerHTML += `<option value="${classId}">Class ${classId}</option>`;
                });
            } else {
                classSelect.innerHTML = '<option value="">No classes found for this school</option>';
                toastr.warning('No classes found for this school. Please add classes in School Management first.');
            }
        }
        
        async function loadAllFees() {
            try {
                const res = await fetch(API_BASE + 'fee_list_all.php');
                const data = await res.json();
                if (data.success && data.data) {
                    allFees = data.data;
                } else {
                    allFees = [];
                }
            } catch(e) {
                console.error('Error loading fees:', e);
                allFees = [];
            }
        }
        
        async function loadMasterFeesForManager(page = 1, limit = 10) {
    try {
        const res = await fetch(API_BASE + `fee_list_custom.php?page=${page}&limit=${limit}`);
        const data = await res.json();
        if (data.success && data.data) {
            feeNamesTotalPages = data.total_pages || 1;
            feeNamesTotalRows = data.total_rows || 0;
            return data.data;
        }
        return [];
    } catch(e) {
        console.error('Error loading fees:', e);
        return [];
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
        
        function checkFiltersApplied() {
            const schoolId = document.getElementById('filter_school_id')?.value || '';
            const academicYear = document.getElementById('filter_academic_year')?.value || '';
            const boardId = document.getElementById('filter_board_id')?.value || '';
            const classId = document.getElementById('filter_class_id')?.value || '';
            hasFilterApplied = !!(schoolId || academicYear || boardId || classId);
            return hasFilterApplied;
        }
        
        async function loadFeeStructures(page = 1) {
            if (!checkFiltersApplied()) {
                document.getElementById('infoMessage').style.display = 'block';
                document.getElementById('loadingDiv').style.display = 'none';
                document.getElementById('tableContainer').style.display = 'none';
                return;
            }
            
            document.getElementById('infoMessage').style.display = 'none';
            document.getElementById('loadingDiv').style.display = 'block';
            document.getElementById('tableContainer').style.display = 'none';
            
            currentPage = page;
            const schoolId = document.getElementById('filter_school_id')?.value || '';
            const academicYear = document.getElementById('filter_academic_year')?.value || '';
            const boardId = document.getElementById('filter_board_id')?.value || '';
            const classId = document.getElementById('filter_class_id')?.value || '';
            
            let url = `${API_BASE}fee_structure_list.php?page=${page}&limit=${perPage}`;
            if (schoolId) url += `&school_id=${schoolId}`;
            if (academicYear) url += `&academic_year=${academicYear}`;
            if (boardId) url += `&board_id=${boardId}`;
            if (classId) url += `&class_id=${classId}`;
            
            try {
                const res = await fetch(url);
                const data = await res.json();
                
                const tbody = document.getElementById('feeStructuresList');
                const loadingDiv = document.getElementById('loadingDiv');
                const tableContainer = document.getElementById('tableContainer');
                
                if (data.success && data.data && data.data.length > 0) {
                    totalPages = data.total_pages;
                    totalRows = data.total_rows;
                    
                    tbody.innerHTML = data.data.map(fee => `
                        <tr>
                            <td>${fee.id}</td>
                            <td><strong>${escapeHtml(fee.school_name)}</strong><br><small>${escapeHtml(fee.school_code)}</small></td>
                            <td>${fee.board_id === 'C' ? 'CBSE' : fee.board_id === 'I' ? 'ICSE' : 'WBBSE'}</td>
                            <td>Class ${fee.class_id}</td>
                            <td>${fee.group_id || '-'}</td>
                            <td>${escapeHtml(fee.fee_name)}</td>
                            <td>₹ ${parseFloat(fee.amount).toLocaleString('en-IN')}</td>
                            <td>${fee.academic_year}</td>
                            <td>${formatDate(fee.created_datetime)}</td>
                            <td class="action-buttons">
                                <button class="btn btn-secondary" onclick="editFeeStructure(${fee.id})" style="background:#5a6268; color:white;">✏️ Edit</button>
                                <button class="btn btn-secondary" onclick="deleteFeeStructure(${fee.id})" style="background:#dc3545; color:white;">🗑️ Delete</button>
                            </td>
                        </tr>
                    `).join('');
                    
                    const start = (data.current_page - 1) * perPage + 1;
                    const end = Math.min(data.current_page * perPage, totalRows);
                    document.getElementById('paginationInfo').innerHTML = `Showing ${start} to ${end} of ${totalRows} fee structures`;
                    renderPagination(data.current_page, data.total_pages);
                } else {
                    tbody.innerHTML = '';
                    totalPages = 0;
                    renderPagination(1, 0);
                }
                loadingDiv.style.display = 'none';
                tableContainer.style.display = 'block';
            } catch(e) {
                console.error('Error loading fee structures:', e);
                toastr.error('Error loading fee structures');
                document.getElementById('loadingDiv').style.display = 'none';
            }
        }
        
        function renderPagination(currentPageNum, totalPagesNum) {
            const container = document.getElementById('paginationContainer');
            if (!container) return;
            if (!totalPagesNum || totalPagesNum <= 1) { container.innerHTML = ''; return; }
            
            let html = `<button class="pagination-btn" onclick="changePage(${currentPageNum - 1})" ${currentPageNum === 1 ? 'disabled' : ''}>← Prev</button>`;
            for (let i = 1; i <= totalPagesNum; i++) {
                html += `<button class="pagination-btn ${i === currentPageNum ? 'active' : ''}" onclick="changePage(${i})">${i}</button>`;
            }
            html += `<button class="pagination-btn" onclick="changePage(${currentPageNum + 1})" ${currentPageNum === totalPagesNum ? 'disabled' : ''}>Next →</button>`;
            html += `<select class="per-page-select" onchange="changePerPage(this.value)">
                        <option value="10" ${perPage === 10 ? 'selected' : ''}>10 per page</option>
                        <option value="15" ${perPage === 15 ? 'selected' : ''}>15 per page</option>
                        <option value="20" ${perPage === 20 ? 'selected' : ''}>20 per page</option>
                        <option value="25" ${perPage === 25 ? 'selected' : ''}>25 per page</option>
                        <option value="50" ${perPage === 50 ? 'selected' : ''}>50 per page</option>
                    </select>`;
            container.innerHTML = html;
        }
        
        function changePage(page) { if (page >= 1 && page <= totalPages) loadFeeStructures(page); }
        function changePerPage(value) { perPage = parseInt(value); loadFeeStructures(1); }
        
        function calculateGroupTotal(feeItems) {
            let total = 0;
            feeItems.forEach(item => {
                total += item.amount || 0;
            });
            return total;
        }
        
        function renderGroups() {
            const container = document.getElementById('groupsContainer');
            if (!container) return;
            
            if (groups.length === 0) {
                container.innerHTML = '';
                return;
            }
            
            let feeOptions = '<option value="">-- Select Fee --</option>';
            allFees.forEach(fee => {
                feeOptions += `<option value="${escapeHtml(fee.fee_name)}" data-amount="${fee.amount}">${escapeHtml(fee.fee_name)} (₹${fee.amount})</option>`;
            });
            
            let html = '';
            groups.forEach((group, idx) => {
                const groupTotal = calculateGroupTotal(group.feeItems);
                
                let feeItemsHtml = '';
                if (group.feeItems && group.feeItems.length > 0) {
                    feeItemsHtml = '<div class="fee-items-list">';
                    group.feeItems.forEach((item, itemIdx) => {
                        feeItemsHtml += `
                            <div class="fee-item-chip" data-group="${idx}" data-item="${itemIdx}">
                                <span class="fee-name-chip">${escapeHtml(item.name)}</span>
                                <span class="fee-amount-chip">₹${item.amount}</span>
                                <button type="button" class="remove-fee-chip" onclick="removeFeeItemFromGroup(${idx}, ${itemIdx})">✕</button>
                            </div>
                        `;
                    });
                    feeItemsHtml += '</div>';
                } else {
                    feeItemsHtml = '';
                }
                
                html += `<div class="group-box">
                    <div class="group-header">
                        <div class="group-title">
                            <span class="group-number">${idx + 1}</span>
                            <span>Group ${idx + 1}</span>
                        </div>
                        <div class="group-total-wrapper">
                            <div class="group-total">Total: ₹${groupTotal.toLocaleString('en-IN')}</div>
                            <button type="button" class="remove-group-btn" onclick="removeGroup(${idx})" title="Remove Group">✕</button>
                        </div>
                    </div>
                    <div class="fee-select-row">
                        <select id="fee_select_${idx}" class="group-fee-select">${feeOptions}</select>
                        <button type="button" class="add-fee-to-group-btn" onclick="addFeeToGroup(${idx})">+</button>
                    </div>
                    ${feeItemsHtml}
                </div>`;
            });
            container.innerHTML = html;
        }
        
        // Render Fee Names Table (similar to Manage Classes table)
        async function renderFeeNamesTable() {
            const container = document.getElementById('feeNamesTableContainer');
            if (!container) return;
            
            container.innerHTML = '<div style="text-align:center; padding:20px;"><div class="spinner" style="width:30px; height:30px;"></div><p>Loading fee names...</p></div>';
            
            const fees = await loadMasterFeesForManager();
            
            if (!fees || fees.length === 0) {
                container.innerHTML = '';
                return;
            }
            
            let html = `<table class="fee-names-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fee Name</th>
                        <th>Amount (₹)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>`;
            
            fees.forEach((fee) => {
                html += `<tr>
                    <td>${fee.id}</td>
                    <td><strong>${escapeHtml(fee.fee_name)}</strong></td>
                    <td>₹ ${parseFloat(fee.amount).toLocaleString('en-IN')}</td>
                    <td>
                        <button class="btn-sm btn-secondary" onclick="editCustomFee(${fee.id})">✏️ Edit</button>
                        <button class="btn-sm btn-danger" onclick="deleteCustomFee(${fee.id})">🗑️ Delete</button>
                    </td>
                </tr>`;
            });
            
            html += `</tbody></table>`;
            container.innerHTML = html;
        }
        
        async function addCustomFeeToDatabase(feeName, feeAmount) {
            try {
                const res = await fetch(API_BASE + 'fee_custom_add.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ fee_name: feeName, amount: feeAmount })
                });
                const data = await res.json();
                return data;
            } catch(e) {
                console.error('Error adding fee:', e);
                return { success: false, message: e.message };
            }
        }
        
        async function updateCustomFeeInDatabase(id, feeName, feeAmount) {
            try {
                const res = await fetch(API_BASE + 'fee_custom_update.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id, fee_name: feeName, amount: feeAmount })
                });
                const data = await res.json();
                return data;
            } catch(e) {
                console.error('Error updating fee:', e);
                return { success: false, message: e.message };
            }
        }
        
        async function deleteCustomFeeFromDatabase(id) {
            try {
                const res = await fetch(API_BASE + 'fee_custom_delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                });
                const data = await res.json();
                return data;
            } catch(e) {
                console.error('Error deleting fee:', e);
                return { success: false, message: e.message };
            }
        }
        
        window.editCustomFee = async function(id) {
            const fees = await loadMasterFeesForManager();
            const fee = fees.find(f => f.id == id);
            if (!fee) {
                toastr.error('Fee not found');
                return;
            }
            
            Swal.fire({
                title: 'Edit Fee',
                html: `
                    <input type="text" id="edit_fee_name" class="swal2-input" placeholder="Fee Name" value="${escapeHtml(fee.fee_name)}">
                    <input type="number" id="edit_fee_amount" class="swal2-input" placeholder="Amount" value="${fee.amount}" step="0.01">
                `,
                confirmButtonText: 'Save',
                cancelButtonText: 'Cancel',
                showCancelButton: true,
                preConfirm: () => {
                    const newName = document.getElementById('edit_fee_name').value;
                    const newAmount = parseFloat(document.getElementById('edit_fee_amount').value);
                    if (!newName) {
                        Swal.showValidationMessage('Fee name is required');
                        return false;
                    }
                    if (!newAmount || newAmount <= 0) {
                        Swal.showValidationMessage('Valid amount is required');
                        return false;
                    }
                    return { name: newName, amount: newAmount };
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const updateResult = await updateCustomFeeInDatabase(id, result.value.name, result.value.amount);
                    if (updateResult.success) {
                        await loadAllFees();
                        await renderFeeNamesTable();
                        toastr.success('Fee updated successfully');
                    } else {
                        toastr.error(updateResult.message);
                    }
                }
            });
        };
        
        window.deleteCustomFee = async function(id) {
            const fees = await loadMasterFeesForManager();
            const fee = fees.find(f => f.id == id);
            
            Swal.fire({
                title: 'Delete Fee?',
                text: `Are you sure you want to delete "${fee.fee_name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const deleteResult = await deleteCustomFeeFromDatabase(id);
                    if (deleteResult.success) {
                        await loadAllFees();
                        await renderFeeNamesTable();
                        toastr.success('Fee deleted successfully');
                    } else {
                        toastr.error(deleteResult.message);
                    }
                }
            });
        };
        
        window.openFeeNameManager = async function() {
            await loadAllFees();
            await renderFeeNamesTable();
            
            const feeManagerHtml = `
                <div class="fee-manager-section">
                    <div class="fee-manager-title">➕ Add New Fee Name</div>
                    <div class="fee-input-row">
                        <input type="text" id="new_fee_name" placeholder="Fee Name (e.g., Sports Fee, Lab Fee)" maxlength="100">
                        <input type="number" id="new_fee_amount" placeholder="Amount " step="0.01">
                        <button type="button" class="btn btn-success" onclick="addCustomFeeFromManager()" style="padding: 8px 20px;">+ Add</button>
                    </div>
                </div>
                <div class="fee-manager-section">
                    <div class="fee-manager-title"> Existing Fee Names</div>
                    <div id="feeNamesTableContainer"></div>
                </div>
                <div style="margin-top: 20px;">
                    <button type="button" onclick="closePanel()" class="btn-secondary" style="padding: 10px 20px;">Close</button>
                </div>
            `;
            
            openPanel('Manage Fee Names', feeManagerHtml, async () => {
                await loadAllFees();
            }, '650px', '📝');
            
            setTimeout(async () => {
                await renderFeeNamesTable();
            }, 100);
            
            window.addCustomFeeFromManager = async function() {
                const feeName = document.getElementById('new_fee_name')?.value.trim();
                const feeAmount = document.getElementById('new_fee_amount')?.value;
                
                if (!feeName) {
                    toastr.error('Please enter a fee name');
                    return;
                }
                if (!feeAmount || parseFloat(feeAmount) <= 0) {
                    toastr.error('Please enter a valid amount');
                    return;
                }
                
                const result = await addCustomFeeToDatabase(feeName, parseFloat(feeAmount));
                if (result.success) {
                    document.getElementById('new_fee_name').value = '';
                    document.getElementById('new_fee_amount').value = '';
                    await loadAllFees();
                    await renderFeeNamesTable();
                    toastr.success(`Added "${feeName}" with amount ₹${feeAmount}`);
                } else {
                    toastr.error(result.message);
                }
            };
        };
        
        window.openCreateFee = async function() {
            groups = [];
            editingFeeStructureId = null;
            await loadAllFees();
            
            let schoolsHtml = '<option value="">Select School</option>';
            let schoolsData = [];
            try {
                const res = await fetch(API_BASE + 'school_list.php');
                const data = await res.json();
                if (data.success) {
                    schoolsData = data.data;
                    data.data.forEach(school => {
                        schoolsHtml += `<option value="${school.school_id}" data-board="${school.board_id}">${escapeHtml(school.school_name)} (${school.school_code})</option>`;
                    });
                }
            } catch(e) {}
            
            const currentYear = new Date().getFullYear();
            let academicYearOptions = '';
            for (let i = currentYear - 2; i <= currentYear + 5; i++) {
                academicYearOptions += `<option value="${i}" ${i === currentYear ? 'selected' : ''}>${i}</option>`;
            }
            
            const formHtml = `
                <form id="createFeeForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label>School <span class="required">*</span></label>
                            <select id="fee_school_id" required>${schoolsHtml}</select>
                        </div>
                        <div class="form-group">
                            <label>Academic Year <span class="required">*</span></label>
                            <select id="fee_academic_year" required>${academicYearOptions}</select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Board <span class="required">*</span></label>
                            <select id="fee_board_id" required>
                                <option value="">Select School first</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Class <span class="required">*</span></label>
                            <select id="fee_class_id" required>
                                <option value="">Select School first</option>
                            </select>
                        </div>
                    </div>
                    
                    <div style="margin: 20px 0;">
                        <button type="button" class="btn btn-success" onclick="addGroup()" style="width: auto;">+ Create Group</button>
                        
                    </div>
                    
                    <div id="groupsContainer"></div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Account Number <span class="optional-text">(Optional)</span></label>
                            <input type="text" id="account_number" placeholder="e.g., 1234567890123456" maxlength="16">
                            <small class="field-hint">10-16 alphanumeric characters</small>
                        </div>
                        <div class="form-group">
                            <label>IFSC Code <span class="optional-text">(Optional)</span></label>
                            <input type="text" id="ifsc_code" placeholder="e.g., SBIN0001234" maxlength="11" style="text-transform: uppercase;">
                            <small class="field-hint">11 characters - First 4 letters, 5th is 0</small>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 15px; margin-top: 20px;">
                        <button type="button" onclick="closePanel()" class="btn-secondary" style="padding: 12px; flex:1;">Close</button>
                        <button type="submit" class="btn-submit" style="flex:1;">Create Fee Structure</button>
                    </div>
                </form>
            `;
            
            openPanel('Create Fee Structure', formHtml, () => {}, '600px', '💰');
            
            setTimeout(() => {
                const ifscInput = document.getElementById('ifsc_code');
                if (ifscInput) {
                    ifscInput.addEventListener('input', function() {
                        this.value = this.value.toUpperCase();
                    });
                }
                
                const schoolSelect = document.getElementById('fee_school_id');
                const boardSelect = document.getElementById('fee_board_id');
                const classSelect = document.getElementById('fee_class_id');
                
                if (schoolSelect) {
                    schoolSelect.addEventListener('change', async function() {
                        const selectedOption = this.options[this.selectedIndex];
                        const schoolId = this.value;
                        const schoolBoard = selectedOption.getAttribute('data-board');
                        
                        if (schoolId && schoolBoard) {
                            let boardName = '';
                            if (schoolBoard === 'C' || schoolBoard === 'CBSE') boardName = 'CBSE';
                            else if (schoolBoard === 'I' || schoolBoard === 'ICSE') boardName = 'ICSE';
                            else if (schoolBoard === 'W' || schoolBoard === 'WBBSE') boardName = 'WBBSE';
                            
                            boardSelect.innerHTML = `<option value="${schoolBoard}">${boardName}</option>`;
                        } else if (schoolId) {
                            const school = schoolsData.find(s => s.school_id == schoolId);
                            if (school && school.board_id) {
                                let boardName = '';
                                if (school.board_id === 'C') boardName = 'CBSE';
                                else if (school.board_id === 'I') boardName = 'ICSE';
                                else if (school.board_id === 'W') boardName = 'WBBSE';
                                
                                boardSelect.innerHTML = `<option value="${school.board_id}">${boardName}</option>`;
                            } else {
                                boardSelect.innerHTML = '<option value="">No board found for this school</option>';
                            }
                        } else {
                            boardSelect.innerHTML = '<option value="">Select School first</option>';
                        }
                        
                        if (schoolId) {
                            await updateClassDropdown(schoolId);
                        } else {
                            classSelect.innerHTML = '<option value="">Select School first</option>';
                        }
                    });
                }
                
                const form = document.getElementById('createFeeForm');
                if (form) {
                    form.onsubmit = async (e) => {
                        e.preventDefault();
                        
                        const schoolId = document.getElementById('fee_school_id')?.value;
                        const academicYear = document.getElementById('fee_academic_year')?.value;
                        const boardId = document.getElementById('fee_board_id')?.value;
                        const classId = document.getElementById('fee_class_id')?.value;
                        const accountNumber = document.getElementById('account_number')?.value;
                        const ifscCode = document.getElementById('ifsc_code')?.value;
                        
                        if (!schoolId) { toastr.error('Please select a School'); return; }
                        if (!academicYear) { toastr.error('Please select an Academic Year'); return; }
                        if (!boardId) { toastr.error('Please select a Board'); return; }
                        if (!classId) { toastr.error('Please select a Class'); return; }
                        
                        const feeItems = [];
                        groups.forEach((group, groupIdx) => {
                            if (group.feeItems && group.feeItems.length > 0) {
                                group.feeItems.forEach(item => {
                                    if (item.name && item.amount) {
                                        feeItems.push({
                                            group_id: groupIdx + 1,
                                            fee_name: item.name,
                                            amount: item.amount
                                        });
                                    }
                                });
                            }
                        });
                        
                        if (feeItems.length === 0) {
                            toastr.error('Please add at least one fee item to a group');
                            return;
                        }
                        
                        Swal.fire({ title: 'Creating...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                        
                        let successCount = 0;
                        let errorCount = 0;
                        
                        for (const item of feeItems) {
                            const res = await fetch(API_BASE + 'fee_structure_add.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({
                                    school_id: schoolId,
                                    board_id: boardId,
                                    class_id: classId,
                                    group_id: item.group_id,
                                    academic_year: academicYear,
                                    fee_name: item.fee_name,
                                    amount: item.amount,
                                    account_number: accountNumber || null,
                                    ifsc_code: ifscCode ? ifscCode.toUpperCase() : null
                                })
                            });
                            const result = await res.json();
                            if (result.success) successCount++;
                            else errorCount++;
                        }
                        
                        if (successCount > 0) {
                            Swal.fire({ 
                                icon: 'success', 
                                title: 'Created!', 
                                text: `${successCount} fee items added successfully. ${errorCount > 0 ? errorCount + ' failed.' : ''}\n\nPlease use filters to view the fee structures.`,
                                
                            });
                            closePanel();
                            groups = [];
                        } else {
                            Swal.fire({ icon: 'error', title: 'Failed', text: 'Could not create fee structure' });
                        }
                    };
                }
                
                renderGroups();
            }, 100);
        };
        
        window.editFeeStructure = async function(id) {
            try {
                const res = await fetch(API_BASE + `fee_structure_list.php?limit=1000`);
                const data = await res.json();
                const feeEntries = data.data.filter(f => f.id == id);
                
                if (feeEntries.length === 0) {
                    toastr.error('Fee structure not found');
                    return;
                }
                
                editingFeeStructureId = id;
                groups = [];
                await loadAllFees();
                
                const groupedFees = {};
                feeEntries.forEach(fee => {
                    const groupId = fee.group_id || 1;
                    if (!groupedFees[groupId]) {
                        groupedFees[groupId] = [];
                    }
                    groupedFees[groupId].push({ name: fee.fee_name, amount: fee.amount });
                });
                
                const groupIds = Object.keys(groupedFees).sort((a,b) => a-b);
                groupIds.forEach(gid => {
                    groups.push({ feeItems: groupedFees[gid] });
                });
                
                const schoolId = feeEntries[0].school_id;
                const academicYear = feeEntries[0].academic_year;
                const boardId = feeEntries[0].board_id;
                const classId = feeEntries[0].class_id;
                const accountNumber = feeEntries[0].account_number;
                const ifscCode = feeEntries[0].ifsc_code;
                
                let schoolsHtml = '<option value="">Select School</option>';
                let schoolsData = [];
                const schoolRes = await fetch(API_BASE + 'school_list.php');
                const schoolData = await schoolRes.json();
                if (schoolData.success) {
                    schoolsData = schoolData.data;
                    schoolData.data.forEach(school => {
                        schoolsHtml += `<option value="${school.school_id}" ${school.school_id == schoolId ? 'selected' : ''} data-board="${school.board_id}">${escapeHtml(school.school_name)} (${school.school_code})</option>`;
                    });
                }
                
                const currentYear = new Date().getFullYear();
                let academicYearOptions = '';
                for (let i = currentYear - 2; i <= currentYear + 5; i++) {
                    academicYearOptions += `<option value="${i}" ${academicYear == i ? 'selected' : ''}>${i}</option>`;
                }
                
                const editFormHtml = `
                    <form id="editFeeForm">
                        <input type="hidden" id="edit_fee_id" value="${id}">
                        <div class="form-row">
                            <div class="form-group">
                                <label>School <span class="required">*</span></label>
                                <select id="edit_school_id" required>${schoolsHtml}</select>
                            </div>
                            <div class="form-group">
                                <label>Academic Year <span class="required">*</span></label>
                                <select id="edit_academic_year" required>${academicYearOptions}</select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Board <span class="required">*</span></label>
                                <select id="edit_board_id" required>
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Class <span class="required">*</span></label>
                                <select id="edit_class_id" required>
                                    <option value="">Select Class</option>
                                </select>
                            </div>
                        </div>
                        
                        <div style="margin: 20px 0;">
                            <button type="button" class="btn btn-success" onclick="addGroup()" style="width: auto;">+ Create Group</button>
                            
                        </div>
                        
                        <div id="groupsContainer"></div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Account Number <span class="optional-text">(Optional)</span></label>
                                <input type="text" id="edit_account_number" value="${escapeHtml(accountNumber || '')}" maxlength="16">
                                <small class="field-hint">10-16 alphanumeric characters</small>
                            </div>
                            <div class="form-group">
                                <label>IFSC Code <span class="optional-text">(Optional)</span></label>
                                <input type="text" id="edit_ifsc_code" value="${escapeHtml(ifscCode || '')}" maxlength="11" style="text-transform: uppercase;">
                                <small class="field-hint">11 characters - First 4 letters, 5th is 0</small>
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 15px; margin-top: 20px;">
                            <button type="button" onclick="closePanel()" class="btn-secondary" style="padding: 12px; flex:1;">Cancel</button>
                            <button type="submit" class="btn-submit" style="flex:1;">Update Fee Structure</button>
                        </div>
                    </form>
                `;
                
                openPanel('Edit Fee Structure', editFormHtml, () => { loadFeeStructures(currentPage); }, '600px', '✏️');
                
                setTimeout(async () => {
                    const editSchoolSelect = document.getElementById('edit_school_id');
                    const editBoardSelect = document.getElementById('edit_board_id');
                    const editClassSelect = document.getElementById('edit_class_id');
                    
                    if (editSchoolSelect) {
                        const setBoardForSchool = (selectedSchoolId) => {
                            const school = schoolsData.find(s => s.school_id == selectedSchoolId);
                            if (school && school.board_id) {
                                let boardName = '';
                                if (school.board_id === 'C') boardName = 'CBSE';
                                else if (school.board_id === 'I') boardName = 'ICSE';
                                else if (school.board_id === 'W') boardName = 'WBBSE';
                                editBoardSelect.innerHTML = `<option value="${school.board_id}">${boardName}</option>`;
                            } else {
                                editBoardSelect.innerHTML = '<option value="">No board found</option>';
                            }
                        };
                        
                        setBoardForSchool(schoolId);
                        
                        const classes = await loadSchoolClasses(schoolId);
                        if (classes.length > 0) {
                            editClassSelect.innerHTML = '<option value="">Select Class</option>';
                            classes.forEach(c => {
                                editClassSelect.innerHTML += `<option value="${c}" ${classId == c ? 'selected' : ''}>Class ${c}</option>`;
                            });
                        }
                        
                        editSchoolSelect.addEventListener('change', async function() {
                            const newSchoolId = this.value;
                            
                            if (newSchoolId) {
                                setBoardForSchool(newSchoolId);
                                const newClasses = await loadSchoolClasses(newSchoolId);
                                if (newClasses.length > 0) {
                                    editClassSelect.innerHTML = '<option value="">Select Class</option>';
                                    newClasses.forEach(c => {
                                        editClassSelect.innerHTML += `<option value="${c}">Class ${c}</option>`;
                                    });
                                } else {
                                    editClassSelect.innerHTML = '<option value="">No classes found</option>';
                                }
                            } else {
                                editBoardSelect.innerHTML = '<option value="">Select School first</option>';
                                editClassSelect.innerHTML = '<option value="">Select School first</option>';
                            }
                        });
                    }
                    
                    const form = document.getElementById('editFeeForm');
                    if (form) {
                        form.onsubmit = async (e) => {
                            e.preventDefault();
                            
                            const editSchoolId = document.getElementById('edit_school_id')?.value;
                            const editAcademicYear = document.getElementById('edit_academic_year')?.value;
                            const editBoardId = document.getElementById('edit_board_id')?.value;
                            const editClassId = document.getElementById('edit_class_id')?.value;
                            const editAccountNumber = document.getElementById('edit_account_number')?.value;
                            const editIfscCode = document.getElementById('edit_ifsc_code')?.value;
                            
                            if (!editSchoolId) { toastr.error('Please select a School'); return; }
                            if (!editAcademicYear) { toastr.error('Please select an Academic Year'); return; }
                            if (!editBoardId) { toastr.error('Please select a Board'); return; }
                            if (!editClassId) { toastr.error('Please select a Class'); return; }
                            
                            await fetch(API_BASE + 'fee_structure_delete.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ id: editingFeeStructureId })
                            });
                            
                            const feeItems = [];
                            groups.forEach((group, groupIdx) => {
                                if (group.feeItems && group.feeItems.length > 0) {
                                    group.feeItems.forEach(item => {
                                        if (item.name && item.amount) {
                                            feeItems.push({
                                                group_id: groupIdx + 1,
                                                fee_name: item.name,
                                                amount: item.amount
                                            });
                                        }
                                    });
                                }
                            });
                            
                            if (feeItems.length === 0) {
                                toastr.error('Please add at least one fee item to a group');
                                return;
                            }
                            
                            Swal.fire({ title: 'Updating...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                            
                            let successCount = 0;
                            for (const item of feeItems) {
                                const addRes = await fetch(API_BASE + 'fee_structure_add.php', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({
                                        school_id: editSchoolId,
                                        board_id: editBoardId,
                                        class_id: editClassId,
                                        group_id: item.group_id,
                                        academic_year: editAcademicYear,
                                        fee_name: item.fee_name,
                                        amount: item.amount,
                                        account_number: editAccountNumber || null,
                                        ifsc_code: editIfscCode ? editIfscCode.toUpperCase() : null
                                    })
                                });
                                const result = await addRes.json();
                                if (result.success) successCount++;
                            }
                            
                            if (successCount > 0) {
                                Swal.fire({ icon: 'success', title: 'Updated!', text: `${successCount} fee items updated successfully` });
                                closePanel();
                                loadFeeStructures(currentPage);
                            } else {
                                Swal.fire({ icon: 'error', title: 'Failed', text: 'Could not update fee structure' });
                            }
                        };
                    }
                    
                    renderGroups();
                }, 100);
                
            } catch(e) {
                console.error('Error:', e);
                toastr.error('Error loading fee details: ' + e.message);
            }
        };
        
        window.deleteFeeStructure = async function(id) {
            const result = await Swal.fire({ title: 'Delete Fee Structure?', text: 'This action cannot be undone!', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete!' });
            if (result.isConfirmed) {
                Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const res = await fetch(API_BASE + 'fee_structure_delete.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id: id }) });
                const data = await res.json();
                if (data.success) { 
                    Swal.fire({ icon: 'success', title: 'Deleted!' }); 
                    if (checkFiltersApplied()) loadFeeStructures(currentPage);
                } else { 
                    Swal.fire({ icon: 'error', title: 'Failed', text: data.message }); 
                }
            }
        };
        
        document.getElementById('filter_academic_year').innerHTML = generateAcademicYearOptions();
        document.getElementById('createFeeBtn').onclick = () => window.openCreateFee();
        document.getElementById('manageFeeNamesBtn').onclick = () => window.openFeeNameManager();
        document.getElementById('applyFilterBtn').onclick = () => loadFeeStructures(1);
        document.getElementById('resetFilterBtn').onclick = () => {
            document.getElementById('filter_school_id').value = '';
            document.getElementById('filter_academic_year').value = '';
            document.getElementById('filter_board_id').value = '';
            document.getElementById('filter_class_id').value = '';
            document.getElementById('infoMessage').style.display = 'block';
            document.getElementById('loadingDiv').style.display = 'none';
            document.getElementById('tableContainer').style.display = 'none';
        };
        
        loadAllFees();
        loadSchools();
        document.getElementById('infoMessage').style.display = 'block';
    </script>
</body>
</html>