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
        .status-disabled { background: #e9ecef; color: #6c757d; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; display: inline-block; }
        
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
        
        /* User table styles */
        .user-table { width: 100%; border-collapse: collapse; }
        .user-table th, .user-table td { padding: 10px; text-align: left; border-bottom: 1px solid #e9ecef; }
        .user-table th { background: #f8f9fa; font-weight: 600; }
        
        .batch-badge {
            background: #e3f2fd;
            color: #1565c0;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="sidebar">
            <div class="sidebar-header"><h2>Tutorix</h2></div>
            <div class="nav-menu">
                <a href="school-management.php" class="nav-item active"><span class="icon">🏫</span><span>School Management</span></a>
                <a href="holidays.php" class="nav-item"><span class="icon">📅</span><span>School Holidays</span></a>
                <a href="school_fee_structure.php" class="nav-item"><span class="icon">💰</span><span>School Fee Structure</span></a>
                <a href="student_fee_details.php" class="nav-item"><span class="icon">👨‍🎓</span><span>Student Fee Details</span></a>
            </div>
        </div>
        
        <div class="main-content">
            <div class="container">
                <div class="header">
                    <h1>🏫 School Management System</h1>
                    <button class="btn btn-primary" id="createBtn">+ Create School</button>
                </div>

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
        
        function validatePhoneByCountry(phone, countryCode) {
            if (countryCode === '+91') {
                const re = /^\d{10}$/;
                return { valid: re.test(phone), message: 'Please enter a valid 10-digit mobile number' };
            }
            return { valid: phone.length >= 5, message: 'Please enter a valid mobile number' };
        }
        
        const PANEL_WIDTHS = { school: '500px', classes: '650px', licence: '600px', tv: '700px', api: '600px', lms: '700px', user: '850px' };
        let activeTVContext = null, activeLMSContext = null, activeAPIContext = null, activeClassesContext = null, activeUserContext = null;
        
        function openPanel(title, contentHtml, onClose, width, icon) {
            width = width || '720px';
            icon = icon || '📄';
            return RightPanel.open({ title, icon, width, html: contentHtml, onClose });
        }
        
        function closePanel() {
            RightPanel.closeTop();
        }

        function refreshActiveTVPanel() {
            if (!activeTVContext) return;
            closePanel();
            setTimeout(function() { openTVSection(activeTVContext.schoolId, activeTVContext.schoolName); }, 350);
        }
        
        function refreshActiveLMSPanel() {
            if (!activeLMSContext) return;
            closePanel();
            setTimeout(function() { openLMSSection(activeLMSContext.schoolId, activeLMSContext.schoolName); }, 350);
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
            if (boardId === 'C') return 'CBSE';
            if (boardId === 'I') return 'ICSE';
            if (boardId === 'W') return 'WBBSE';
            return boardId || '-';
        }

        function getBatchLetter(batchId) {
            var letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            return letters[parseInt(batchId) - 1] || String.fromCharCode(64 + (parseInt(batchId) % 26 || 26));
        }

        // ========== CHECK LMS LICENSE VALIDATION ==========
        async function checkLMSLicense(schoolId) {
            try {
                const res = await fetch(API_BASE + 'licence_list.php?school_id=' + schoolId + '&_=' + Date.now());
                const data = await res.json();
                if (data.success && data.data && data.data.lms) {
                    var activeLicenses = data.data.lms.filter(function(l) { return l.expiry_date && new Date(l.expiry_date) > new Date() && l.is_deleted != 1 && l.available_qty > 0; });
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
            var html = '<div style="overflow-x: auto;"><table class="user-table" style="width:100%; font-size:13px; border-collapse: collapse;"><thead><tr style="background:#f8f9fa;"><th style="padding:10px;">ID</th><th style="padding:10px;">Full Name</th><th style="padding:10px;">Username</th><th style="padding:10px;">Email</th><th style="padding:10px;">Mobile</th><th style="padding:10px;">Role</th><th style="padding:10px;">Status</th><th style="padding:10px;">Actions</th></tr></thead><tbody>';
            for (var i = 0; i < users.length; i++) {
                var u = users[i];
                var statusClass = '';
                var statusText = '';
                if (u.user_status === 'A') {
                    statusClass = 'status-active';
                    statusText = 'Active';
                } else if (u.user_status === 'I') {
                    statusClass = 'status-inactive';
                    statusText = 'Inactive';
                } else if (u.user_status === 'B') {
                    statusClass = 'status-inactive';
                    statusText = 'Blocked';
                } else if (u.user_status === 'D') {
                    statusClass = 'status-disabled';
                    statusText = 'Disabled';
                } else {
                    statusClass = 'status-inactive';
                    statusText = 'Inactive';
                }
                html += '<tr><td style="padding:8px; border-bottom:1px solid #e9ecef;">' + (u.user_id || '-') + '</td>';
                html += '<td style="padding:8px; border-bottom:1px solid #e9ecef;">' + escapeHtml(u.full_name || '-') + '</td>';
                html += '<td style="padding:8px; border-bottom:1px solid #e9ecef;">' + escapeHtml(u.user_name || '-') + '</td>';
                html += '<td style="padding:8px; border-bottom:1px solid #e9ecef;">' + escapeHtml(u.email_id || '-') + '</td>';
                html += '<td style="padding:8px; border-bottom:1px solid #e9ecef;">' + (u.mobile_number || '-') + '</td>';
                html += '<td style="padding:8px; border-bottom:1px solid #e9ecef;">' + (u.role || '-') + '</td>';
                html += '<td style="padding:8px; border-bottom:1px solid #e9ecef;"><span class="' + statusClass + '">' + statusText + '</span></td>';
                html += '<td style="padding:8px; border-bottom:1px solid #e9ecef;">';
                html += '<button class="btn btn-info" onclick="viewUserDetails(' + u.user_id + ')" style="background:#17a2b8; padding:4px 10px;">👁️ View</button> ';
                html += '<button class="btn btn-warning" onclick="editUserDetails(' + u.user_id + ')" style="background:#ffc107; color:#333; padding:4px 10px;">✏️ Edit</button> ';
                html += '<button class="btn btn-danger" onclick="deleteUserAccount(' + u.user_id + ')" style="padding:4px 10px;">🗑️ Delete</button>';
                html += '</td></tr>';
            }
            html += '</tbody></table></div>';
            return html;
        }

       window.viewUserDetails = async function(userId) {
    try {
        var res = await fetch(API_BASE + 'user_details.php?user_id=' + userId + '&_=' + Date.now());
        var data = await res.json();
        
        if (!data.success || !data.data) {
            toastr.error('Could not fetch user details');
            return;
        }
        
        var user = data.data;
        
        var genderText = '';
        if (user.gender === 'M') genderText = 'Male';
        else if (user.gender === 'F') genderText = 'Female';
        else if (user.gender === 'O') genderText = 'Other';
        else genderText = '-';
        
        var statusText = '';
        if (user.user_status === 'A') statusText = 'Active';
        else if (user.user_status === 'I') statusText = 'Inactive';
        else if (user.user_status === 'B') statusText = 'Blocked';
        else if (user.user_status === 'D') statusText = 'Disabled';
        else statusText = user.user_status || '-';
        
        var roleText = '';
        if (user.role === 'student') roleText = 'Student';
        else if (user.role === 'teacher') roleText = 'Teacher';
        else if (user.role === 'mentor') roleText = 'Mentor';
        else if (user.role === 'admin') roleText = 'Admin';
        else roleText = user.role || '-';
        
        var boardText = '';
        if (user.board_id === 'C') boardText = 'CBSE';
        else if (user.board_id === 'I') boardText = 'ICSE';
        else if (user.board_id === 'W') boardText = 'WBBSE';
        else boardText = user.board_id || '-';
        
        var sectionText = user.section || '-';
        var academicYearText = user.academic_year || '-';
        var className = user.current_class || '-';
        
        var detailsText ="👤 PERSONAL INFORMATION\n" +
                          "────────────────────────────────────────\n" +
                          "User ID         : " + (user.user_id || '-') + "\n" +
                          "Full Name       : " + (user.full_name || '-') + "\n" +
                          "Username        : " + (user.user_name || '-') + "\n" +
                          "Email           : " + (user.email_id || '-') + "\n" +
                          "Mobile          : " + (user.mobile_number || '-') + "\n" +
                          "Country Code    : " + (user.country_code || '+91') + "\n" +
                          "Gender          : " + genderText + "\n" +
                          "Status          : " + statusText + "\n" +
                          "Role            : " + roleText + "\n" +
                          "Created Date    : " + (user.created_dtm || '-') + "\n\n" +
                          "📚 ACADEMIC INFORMATION\n" +
                          "────────────────────────────────────────\n" +
                          "Class           : " + className + "\n" +
                          "Section         : " + sectionText + "\n" +
                          "Board           : " + boardText + "\n" +
                          "Academic Year   : " + academicYearText + "\n\n" ;
        
        var viewHtml = '<div class="user-detail-text">' + detailsText.replace(/\n/g, '<br>') + '</div>' +
                       '<div style="margin-top: 20px;">' +
                       '<button type="button" onclick="closePanel()" class="btn-submit" style="background:#6c757d;">Close</button>' +
                       '</div>';
        
        openPanel('User Details: ' + escapeHtml(user.full_name), viewHtml, null, '600px', '👁️');
        
    } catch(e) {
        console.error('Error loading user:', e);
        toastr.error('Error loading user details');
    }
};
        // ========== EDIT USER DETAILS ==========
        window.editUserDetails = async function(userId) {
            try {
                var res = await fetch(API_BASE + 'user_list.php?school_id=' + (activeUserContext ? activeUserContext.schoolId : '') + '&_=' + Date.now());
                var data = await res.json();
                
                if (!data.success || !data.data) {
                    toastr.error('Could not fetch user details');
                    return;
                }
                
                var user = null;
                for (var i = 0; i < data.data.length; i++) {
                    if (data.data[i].user_id == userId) {
                        user = data.data[i];
                        break;
                    }
                }
                if (!user) {
                    toastr.error('User not found');
                    return;
                }
                
                var editHtml = '<form id="editUserFormModal" novalidate>' +
                    '<input type="hidden" id="edit_user_id" value="' + user.user_id + '">' +
                    '<div class="form-row">' +
                        '<div class="form-group"><label>Full Name <span class="required">*</span></label><input type="text" id="edit_full_name" value="' + escapeHtml(user.full_name) + '" required></div>' +
                        '<div class="form-group"><label>Username</label><input type="text" value="' + escapeHtml(user.user_name) + '" readonly class="readonly-field"></div>' +
                    '</div>' +
                    '<div class="form-row">' +
                        '<div class="form-group"><label>Email <span class="required">*</span></label><input type="email" id="edit_email" value="' + escapeHtml(user.email_id) + '" required></div>' +
                        '<div class="form-group"><label>Mobile Number</label><input type="tel" id="edit_mobile" value="' + (user.mobile_number || '') + '"></div>' +
                    '</div>' +
                    '<div class="form-row">' +
                        '<div class="form-group"><label>Country Code</label><select id="edit_country_code"><option value="+91"' + (user.country_code === '+91' ? ' selected' : '') + '>+91 (India)</option><option value="+1"' + (user.country_code === '+1' ? ' selected' : '') + '>+1 (USA)</option><option value="+44"' + (user.country_code === '+44' ? ' selected' : '') + '>+44 (UK)</option></select></div>' +
                        '<div class="form-group"><label>Gender</label><select id="edit_gender"><option value="">Select Gender</option><option value="M"' + (user.gender === 'M' ? ' selected' : '') + '>Male</option><option value="F"' + (user.gender === 'F' ? ' selected' : '') + '>Female</option><option value="O"' + (user.gender === 'O' ? ' selected' : '') + '>Other</option></select></div>' +
                    '</div>' +
                    '<div class="form-row">' +
                        '<div class="form-group"><label>Status <span class="required">*</span></label><select id="edit_status" required><option value="A"' + (user.user_status === 'A' ? ' selected' : '') + '>Active</option><option value="B"' + (user.user_status === 'B' ? ' selected' : '') + '>Blocked</option><option value="I"' + (user.user_status === 'I' ? ' selected' : '') + '>Inactive</option><option value="D"' + (user.user_status === 'D' ? ' selected' : '') + '>Disabled</option></select></div>' +
                        '<div class="form-group"><label>Role <span class="required">*</span></label><select id="edit_role" required><option value="student"' + (user.role === 'student' ? ' selected' : '') + '>Student</option><option value="teacher"' + (user.role === 'teacher' ? ' selected' : '') + '>Teacher</option><option value="mentor"' + (user.role === 'mentor' ? ' selected' : '') + '>Mentor</option><option value="admin"' + (user.role === 'admin' ? ' selected' : '') + '>Admin</option></select></div>' +
                    '</div>' +
                    '<div class="form-row">' +
                        '<div class="form-group"><label>Class</label><input type="text" id="edit_class" value="' + (user.current_class || '') + '" placeholder="Class"></div>' +
                    '</div>' +
                    '<div style="display:flex; gap:10px; margin-top:20px;">' +
                        '<button type="submit" class="btn-submit" style="background:#28a745;">💾 Save Changes</button>' +
                        '<button type="button" onclick="closePanel()" class="btn-secondary" style="background:#6c757d;">Cancel</button>' +
                    '</div>' +
                '</form>';
                
                openPanel('Edit User: ' + escapeHtml(user.full_name), editHtml, null, '600px', '✏️');
                
                setTimeout(function() {
                    document.getElementById('editUserFormModal').onsubmit = async function(e) {
                        e.preventDefault();
                        var email = document.getElementById('edit_email').value;
                        if (!email || !validateEmail(email)) {
                            toastr.error('Valid email is required');
                            return;
                        }
                        
                        var updateData = {
                            user_id: parseInt(document.getElementById('edit_user_id').value),
                            email_id: email,
                            full_name: document.getElementById('edit_full_name').value,
                            mobile_number: document.getElementById('edit_mobile').value,
                            country_code: document.getElementById('edit_country_code').value,
                            gender: document.getElementById('edit_gender').value,
                            user_status: document.getElementById('edit_status').value,
                            role: document.getElementById('edit_role').value,
                            current_class: document.getElementById('edit_class').value
                        };
                        
                        Swal.fire({ title: 'Updating...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
                        
                        var res = await fetch(API_BASE + 'user_update.php', {
                            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(updateData)
                        });
                        var result = await res.json();
                        
                        if (result.success) {
                            Swal.fire({ icon: 'success', title: 'Updated!', text: result.message, timer: 1500, showConfirmButton: false });
                            closePanel();
                            await refreshUserHistory(activeUserContext ? activeUserContext.schoolId : null);
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
            var result = await Swal.fire({
                title: 'Delete User?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete!',
                cancelButtonText: 'Cancel'
            });
            
            if (result.isConfirmed) {
                Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
                
                var res = await fetch(API_BASE + 'user_delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: userId })
                });
                var data = await res.json();
                
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message });
                    await refreshUserHistory(activeUserContext ? activeUserContext.schoolId : null);
                    if (activeUserContext) showUserToggleContent('userHistory');
                } else {
                    Swal.fire({ icon: 'error', title: 'Delete Failed', text: data.message });
                }
            }
        };

        // ========== REFRESH USER HISTORY ==========
        async function refreshUserHistory(schoolId) {
            var historyDiv = document.getElementById('userHistory');
            if (historyDiv) {
                historyDiv.innerHTML = '<div style="text-align:center; padding:20px;"><div class="spinner" style="margin:0 auto;"></div><p>Loading users...</p></div>';
            }
            
            try {
                var res = await fetch(API_BASE + 'user_list.php?school_id=' + schoolId + '&_=' + Date.now());
                var data = await res.json();
                
                if (data.success && data.data && data.data.length > 0) {
                    var html = renderUserHistoryTable(data.data);
                    if (historyDiv) historyDiv.innerHTML = html;
                    return data.data;
                } else {
                    if (historyDiv) historyDiv.innerHTML = '<p style="padding:15px; text-align:center;">No users found.</p>';
                    return [];
                }
            } catch(e) {
                console.error('Error loading users:', e);
                if (historyDiv) historyDiv.innerHTML = '<p style="padding:15px; text-align:center;">Unable to load users. Please try again.</p>';
                return [];
            }
        }
        async function checkAvailableSlots(schoolId) {
    try {
        var res = await fetch(API_BASE + 'licence_list.php?school_id=' + schoolId + '&_=' + Date.now());
        var data = await res.json();
        if (data.success && data.data && data.data.lms && data.data.lms.length > 0) {
            var totalAvailable = 0;
            for (var i = 0; i < data.data.lms.length; i++) {
                totalAvailable += data.data.lms[i].available_qty || 0;
            }
            return totalAvailable;
        }
        return 0;
    } catch(e) {
        console.error('Error checking slots:', e);
        return 0;
    }
}

        window.openUserManagement = async function(schoolId, schoolName) {
            activeUserContext = { schoolId: schoolId, schoolName: schoolName };
            var activeLicense = await checkLMSLicense(schoolId);
            
            var batches = [];
            var classesList = [];
            try {
                var classRes = await fetch(API_BASE + 'classes_list.php?school_id=' + schoolId);
                var classData = await classRes.json();
                if (classData.success && classData.data) {
                    var uniqueClasses = new Set();
                    for (var i = 0; i < classData.data.length; i++) {
                        var c = classData.data[i];
                        if (c.class_id) uniqueClasses.add(c.class_id);
                    }
                    classesList = Array.from(uniqueClasses).sort(function(a,b){return a-b;});
                }
                
                var batchRes = await fetch(API_BASE + 'batches_list.php?school_id=' + schoolId);
                var batchData = await batchRes.json();
                if (batchData.success && batchData.data) {
                    batches = batchData.data;
                }
            } catch(e) { console.error('Error loading data:', e); }
            
            var classOptions = '<option value="">Select Class</option>';
            for (var i = 0; i < classesList.length; i++) {
                classOptions += '<option value="' + classesList[i] + '">Class ' + classesList[i] + '</option>';
            }
            
            var batchOptions = '<option value="">Select Batch/Section</option>';
            for (var i = 0; i < batches.length; i++) {
                var b = batches[i];
                var displayName = b.section ? 'Class ' + b.class_id + ' - Section ' + b.section : (b.display_name || b.class_name || 'Batch');
                batchOptions += '<option value="' + b.batch_id + '">' + escapeHtml(displayName) + '</option>';
            }
            
            var licenseWarning = !activeLicense ? '<div style="background: #fff3cd; border: 1px solid #ffecb5; padding: 12px; border-radius: 8px; margin-bottom: 15px; color: #856404;"><strong>⚠️ Warning:</strong> No active LMS license with available slots found. You cannot create student users until an LMS license is added.</div>' : '<div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 12px; border-radius: 8px; margin-bottom: 15px; color: #155724;"><strong>✅ Active LMS License Found</strong></div>';
            
            var users = await refreshUserHistory(schoolId);
            var usersHtml = renderUserHistoryTable(users);
            
            var createFormHtml = '<form id="addUserForm" novalidate>' +
                '<input type="hidden" id="user_school_id" value="' + schoolId + '">' +
                licenseWarning +
                '<fieldset><legend>👤 Personal Information</legend>' +
                    '<div class="form-row">' +
                        '<div class="form-group"><label>Full Name <span class="required">*</span></label><input type="text" id="user_full_name" placeholder="Enter full name" required><div class="error-message"></div></div>' +
                        '<div class="form-group"><label>Username</label><input type="text" id="user_name" placeholder="Leave empty to auto-generate"></div>' +
                    '</div>' +
                    '<div class="form-row">' +
                        '<div class="form-group"><label>Country Code</label><select id="user_country_code"><option value="+91">+91 (India)</option><option value="+1">+1 (USA)</option><option value="+44">+44 (UK)</option></select></div>' +
                        '<div class="form-group"><label>Mobile Number <span class="required">*</span></label><input type="tel" id="user_mobile" placeholder="9876543210" required><div class="error-message"></div></div>' +
                    '</div>' +
                    '<div class="form-row">' +
                        '<div class="form-group"><label>Email ID <span class="required">*</span></label><input type="email" id="user_email_id" placeholder="email@school.com" required><div class="error-message"></div></div>' +
                        '<div class="form-group"><label>Gender</label><select id="user_gender"><option value="">Select Gender</option><option value="M">Male</option><option value="F">Female</option><option value="O">Other</option></select></div>' +
                    '</div>' +
                    '<div class="form-row">' +
                        '<div class="form-group"><label>User Status <span class="required">*</span></label><select id="user_status" required><option value="A">Active</option><option value="B">Blocked</option><option value="I">Inactive</option><option value="D">Disabled</option></select></div>' +
                        '<div class="form-group"><label>Role <span class="required">*</span></label><select id="user_role" required><option value="">Select Role</option><option value="student">Student</option><option value="teacher">Teacher</option><option value="mentor">Mentor</option><option value="admin">Admin</option></select></div>' +
                    '</div>' +
                '</fieldset>' +
                '<div id="studentSection" style="display:none;">' +
                    '<fieldset><legend>📚 Academic Information</legend>' +
                        '<div class="form-row">' +
                            '<div class="form-group"><label>Class <span class="required">*</span></label><select id="user_class_id">' + classOptions + '</select></div>' +
                            '<div class="form-group"><label>Batch / Section</label><select id="user_batch_id">' + batchOptions + '</select></div>' +
                        '</div>' +
                        '<div class="form-row">' +
                            '<div class="form-group"><label>Subscription Type</label><select id="user_subscription"><option value="D">Demo</option><option value="B">Basic</option><option value="P">Premium</option></select></div>' +
                        '</div>' +
                    '</fieldset>' +
                '</div>' +
                '<div id="teacherSection" style="display:none;">' +
                    '<fieldset><legend>👨‍🏫 Teacher/Mentor Information</legend>' +
                        '<div class="form-row">' +
                            '<div class="form-group"><label>Batch</label><select id="teacher_batch_id">' + batchOptions + '</select></div>' +
                            '<div class="form-group"><label>Subject ID</label><input type="text" id="user_subject" placeholder="e.g., MAT, SCI, ENG"></div>' +
                        '</div>' +
                    '</fieldset>' +
                '</div>' +
                '<button type="submit" class="btn-submit"' + (!activeLicense ? ' disabled style="opacity:0.6; cursor:not-allowed;"' : '') + '>👤 Create User</button>' +
                (!activeLicense ? '<p style="color:#dc3545; font-size:12px; margin-top:10px;">* Please add an LMS license with available slots before creating student users.</p>' : '') +
            '</form>';
            
            var csvFormHtml = '<form id="csvUploadForm" enctype="multipart/form-data">' +
                '<input type="hidden" name="school_id" value="' + schoolId + '">' +
                '<div class="form-group"><label>CSV File *</label><input type="file" name="csv_file" accept=".csv" id="csvFileInput"><div class="error-message" id="csvFileError"></div></div>' +
                '<button type="button" class="btn btn-secondary" onclick="previewCSV()" style="margin-bottom:10px;">👁️ Preview CSV Data</button>' +
                '<div id="csvPreviewContainer" class="preview-container" style="display:none;"></div>' +
                '<button type="submit" class="btn-submit"' + (!activeLicense ? ' disabled style="opacity:0.6; cursor:not-allowed;"' : '') + '>📤 Upload CSV</button>' +
                (!activeLicense ? '<p style="color:#dc3545; font-size:12px; margin-top:10px;">* Please add an LMS license with available slots before uploading students.</p>' : '') +
            '</form>';
            
            var formHtml = '<div class="toggle-container">' +
                '<button class="toggle-btn active" onclick="showUserToggleContent(\'userCreate\')">➕ Create Single User</button>' +
                '<button class="toggle-btn" onclick="showUserToggleContent(\'userCSV\')">📁 Bulk Upload</button>' +
                '<button class="toggle-btn" onclick="showUserToggleContent(\'userHistory\')">📜 User History</button>' +
            '</div>' +
            '<div id="userCreate" class="toggle-content active-content">' + createFormHtml + '</div>' +
            '<div id="userCSV" class="toggle-content"><div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;"><h4 style="margin:0;">Bulk Upload</h4><button type="button" class="btn btn-download" onclick="downloadSampleCSV()">📥 Download Sample CSV</button></div>' + csvFormHtml + '</div>' +
            '<div id="userHistory" class="toggle-content">' + usersHtml + '</div>';
            
            openPanel('User Management - ' + escapeHtml(schoolName), formHtml, null, PANEL_WIDTHS.user, '👥');
            
            window.previewCSV = function() {
                var fileInput = document.getElementById('csvFileInput');
                var previewContainer = document.getElementById('csvPreviewContainer');
                var fileError = document.getElementById('csvFileError');
                if (!fileInput.files || !fileInput.files[0]) { fileError.style.display = 'block'; fileError.innerHTML = 'Please select a CSV file to preview'; toastr.error('Please select a CSV file'); return; }
                var file = fileInput.files[0];
                if (!file.name.endsWith('.csv')) { fileError.style.display = 'block'; fileError.innerHTML = 'Please upload a valid CSV file (.csv)'; toastr.error('Please upload a valid CSV file'); return; }
                fileError.style.display = 'none';
                var reader = new FileReader();
                reader.onload = function(e) {
                    var content = e.target.result;
                    var cleanContent = content.replace(/"(?![^"]*")/g, '').replace(/""/g, '"');
                    var rows = cleanContent.split(/\r?\n/);
                    var headers = rows[0] ? rows[0].split(',').map(function(h) { return h.trim(); }) : [];
                    if (headers.length < 10) { toastr.error('CSV must have at least 10 columns'); return; }
                    var previewHtml = '<h4>📊 CSV Data Preview (First 10 rows)</h4>';
                    previewHtml += '<table class="preview-table"><thead><tr>';
                    for (var h = 0; h < headers.length; h++) previewHtml += '<th>' + escapeHtml(headers[h]) + '</th>';
                    previewHtml += '</tr></thead><tbody>';
                    for (var r = 1; r < Math.min(rows.length, 11); r++) {
                        if (rows[r].trim()) {
                            var cols = rows[r].split(',').map(function(c) { return c.trim(); });
                            previewHtml += '<tr>';
                            for (var j = 0; j < headers.length; j++) previewHtml += '<td>' + escapeHtml(cols[j] || '-') + '</td>';
                            previewHtml += '</tr>';
                        }
                    }
                    previewHtml += '</tbody></table>';
                    previewHtml += '<p style="margin-top:10px;"><strong>Total rows:</strong> ' + (rows.length - 1) + '</p>';
                    previewContainer.innerHTML = previewHtml;
                    previewContainer.style.display = 'block';
                };
                reader.readAsText(file);
            };
            
            window.downloadSampleCSV = function() {
                var sampleData = [['full_name', 'email', 'mobile', 'academic_year', 'board', 'class_id', 'batch_id', 'subscription_type', 'joining_date', 'expiry_date'], ['John Doe', 'john@school.com', '9876543210', '2025-2026', 'C', '6', '1', 'D', '2026-05-14', '2027-05-14'], ['Jane Smith', 'jane@school.com', '9876543211', '2025-2026', 'I', '7', '2', 'B', '2026-05-14', '2027-05-14']];
                var csvContent = '';
                for (var i = 0; i < sampleData.length; i++) {
                    var row = sampleData[i];
                    for (var j = 0; j < row.length; j++) {
                        csvContent += '"' + row[j] + '"';
                        if (j < row.length - 1) csvContent += ',';
                    }
                    csvContent += '\r\n';
                }
                var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                var url = window.URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.href = url;
                a.download = 'sample_users.csv';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
                toastr.success('Sample CSV downloaded successfully');
            };
            
            setTimeout(function() {
                var classSelect = document.getElementById('user_class_id');
                var batchSelect = document.getElementById('user_batch_id');
                if (classSelect && batchSelect) {
                    classSelect.onchange = function() {
                        var selectedClass = this.value;
                        var filteredBatches = batches.filter(function(b) { return b.class_id == selectedClass; });
                        batchSelect.innerHTML = '<option value="">Select Batch/Section</option>';
                        for (var i = 0; i < filteredBatches.length; i++) {
                            var b = filteredBatches[i];
                            var displayName = b.section ? 'Class ' + b.class_id + ' - Section ' + b.section : (b.display_name || b.class_name || 'Batch');
                            batchSelect.innerHTML += '<option value="' + b.batch_id + '">' + escapeHtml(displayName) + '</option>';
                        }
                    };
                }
                var roleSelect = document.getElementById('user_role');
                var studentSection = document.getElementById('studentSection');
                var teacherSection = document.getElementById('teacherSection');
                if (roleSelect) {
                    roleSelect.onchange = function() {
                        var role = roleSelect.value;
                        if (studentSection) studentSection.style.display = role === 'student' ? 'block' : 'none';
                        if (teacherSection) teacherSection.style.display = (role === 'teacher' || role === 'mentor') ? 'block' : 'none';
                    };
                }
                var addForm = document.getElementById('addUserForm');
                if (addForm) {
                    addForm.onsubmit = async function(e) {
                        e.preventDefault();
                        clearAllErrors('addUserForm');
                        var isValid = true;
                        if (!validateRequired('user_full_name', 'Full Name')) isValid = false;
                        if (!validateRequired('user_email_id', 'Email ID')) isValid = false;
                        if (!validateRequired('user_mobile', 'Mobile Number')) isValid = false;
                        if (!validateRequired('user_status', 'User Status')) isValid = false;
                        if (!validateRequired('user_role', 'Role')) isValid = false;
                        var email = document.getElementById('user_email_id').value;
                        if (email && !validateEmail(email)) { showFieldError('user_email_id', 'Valid email required'); isValid = false; }
                        var mobile = document.getElementById('user_mobile').value;
                        var countryCode = document.getElementById('user_country_code').value;
                        if (mobile) {
                            var phoneValid = validatePhoneByCountry(mobile, countryCode);
                            if (!phoneValid.valid) { showFieldError('user_mobile', phoneValid.message); isValid = false; }
                        }
                        var role = document.getElementById('user_role').value;
                        if (role === 'student' && !validateRequired('user_class_id', 'Class')) isValid = false;
                        if (!isValid) return;
                        
                        var formData = { 
                            school_id: schoolId, 
                            full_name: document.getElementById('user_full_name').value, 
                            user_name: document.getElementById('user_name').value || null, 
                            email_id: document.getElementById('user_email_id').value, 
                            country_code: document.getElementById('user_country_code').value, 
                            mobile_number: document.getElementById('user_mobile').value, 
                            user_status: document.getElementById('user_status').value, 
                            role: role, 
                            gender: document.getElementById('user_gender').value || null 
                        };
                        if (role === 'student') { 
                            formData.current_class = document.getElementById('user_class_id').value; 
                            formData.batch_id = document.getElementById('user_batch_id').value || null; 
                            formData.subscription_type = document.getElementById('user_subscription').value; 
                        }
                        if (role === 'teacher' || role === 'mentor') { 
                            formData.batch_id = document.getElementById('teacher_batch_id').value || null; 
                            formData.subject_id = document.getElementById('user_subject').value || null; 
                        }
                        
                        Swal.fire({ title: 'Creating...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
                        var res = await fetch(API_BASE + 'user_add.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(formData) });
                        var result = await res.json();
                        if (result.success) { 
                            Swal.fire({ icon: 'success', title: 'User Added!', html: 'Username: <strong>' + result.user_name + '</strong>' }); 
                            addForm.reset(); 
                            if (studentSection) studentSection.style.display = 'none'; 
                            if (teacherSection) teacherSection.style.display = 'none'; 
                            await refreshUserHistory(schoolId); 
                            showUserToggleContent('userHistory'); 
                        } else { 
                            Swal.fire({ icon: 'error', title: 'Failed', text: result.message }); 
                        }
                    };
                }
                var csvForm = document.getElementById('csvUploadForm');
                if (csvForm) {
                    csvForm.onsubmit = async function(e) {
                        e.preventDefault();
                        var fileInput = document.getElementById('csvFileInput');
                        var fileError = document.getElementById('csvFileError');
                        if (!fileInput.files || !fileInput.files[0]) { fileError.style.display = 'block'; fileError.innerHTML = 'Please select a CSV file to upload'; toastr.error('Please select a CSV file to upload'); return; }
                        var file = fileInput.files[0];
                        if (!file.name.endsWith('.csv')) { fileError.style.display = 'block'; fileError.innerHTML = 'Please upload a valid CSV file (.csv)'; toastr.error('Please upload a valid CSV file'); return; }
                        fileError.style.display = 'none';
                        var confirmResult = await Swal.fire({ title: 'Confirm Upload', text: 'Are you sure you want to upload this CSV file?', icon: 'question', showCancelButton: true, confirmButtonColor: '#28a745', confirmButtonText: 'Yes, upload!' });
                        if (confirmResult.isConfirmed) {
                            Swal.fire({ title: 'Uploading...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
                            var formData = new FormData(csvForm);
                            var res = await fetch(API_BASE + 'user_csv_upload.php', { method: 'POST', body: formData });
                            var result = await res.json();
                            if (result.success) {
                                if (result.success_count > 0) { Swal.fire({ icon: 'success', title: 'Upload Complete!', html: '✅ ' + result.success_count + ' users added<br>❌ ' + result.failed_count + ' failed' }); csvForm.reset(); document.getElementById('csvPreviewContainer').style.display = 'none'; await refreshUserHistory(schoolId); showUserToggleContent('userHistory'); }
                                else { Swal.fire({ icon: 'warning', title: 'Upload Completed with Errors', html: '❌ ' + result.failed_count + ' failed<br>' + (result.errors ? result.errors.slice(0, 5).join('<br>') : '') }); }
                            } else { Swal.fire({ icon: 'error', title: 'Upload Failed', text: result.message }); }
                        }
                    };
                }
            }, 100);
        };
        
        window.showUserToggleContent = function(activeId) {
            var contents = document.querySelectorAll('#userCreate, #userCSV, #userHistory');
            for (var i = 0; i < contents.length; i++) contents[i].classList.remove('active-content');
            var btns = document.querySelectorAll('.toggle-btn');
            for (var i = 0; i < btns.length; i++) btns[i].classList.remove('active');
            document.getElementById(activeId).classList.add('active-content');
            var btnsArray = document.querySelectorAll('.toggle-btn');
            for (var i = 0; i < btnsArray.length; i++) {
                if (btnsArray[i].textContent.includes(activeId === 'userCreate' ? 'Create Single' : activeId === 'userCSV' ? 'Bulk CSV' : 'User History')) {
                    btnsArray[i].classList.add('active');
                    break;
                }
            }
            if (activeId === 'userHistory') refreshUserHistory(activeUserContext ? activeUserContext.schoolId : null);
        };
        
        // ========== TV SECTION ==========
        window.openTVSection = async function(schoolId, schoolName) {
            activeTVContext = { schoolId: schoolId, schoolName: schoolName };
            var classes = [];
            
            try {
                var res = await fetch(API_BASE + 'classes_list.php?school_id=' + schoolId);
                var data = await res.json();
                if (data.success && data.data) {
                    var seenIds = new Set();
                    for (var i = 0; i < data.data.length; i++) {
                        var c = data.data[i];
                        if (!seenIds.has(c.class_id)) {
                            seenIds.add(c.class_id);
                            classes.push(c);
                        }
                    }
                }
            } catch(e) {}
            
            if (classes.length === 0) {
                for (var i = 1; i <= 12; i++) classes.push({ class_id: i, class_name: 'Class ' + i });
            }
            
            var classOptions = '';
            for (var i = 0; i < classes.length; i++) {
                classOptions += '<option value="' + classes[i].class_id + '">Class ' + classes[i].class_id + '</option>';
            }

            function generateTVApiKey(classId) {
                var randomSet1 = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
                var randomSet2 = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
                return 'TPTX-' + randomSet1 + '-' + randomSet2 + '-CL' + String(classId).padStart(2, '0');
            }

            var createFormHtml = '<form id="createTVForm" novalidate>' +
                '<div class="form-group"><label>API Key <span class="required">*</span></label><input type="text" id="tv_api_key" readonly style="background:#e9ecef; cursor:not-allowed; font-family:monospace; font-size:14px;"><small style="display:block; margin-top:5px; color:#666;">Auto-generated unique API key</small></div>' +
                '<div class="form-group"><label>Class <span class="required">*</span></label><select id="tv_class_id" required>' + classOptions + '</select><div class="error-message"></div></div>' +
                '<div class="form-group"><label>Used Status <span class="required">*</span></label><select id="tv_used_status" required><option value="N">Not Used</option><option value="Y">Used</option></select></div>' +
                '<div class="form-row"><div class="form-group"><label>Joining Date <span class="required">*</span></label><input type="date" id="tv_joining_date" required></div><div class="form-group"><label>Expiry Date <span class="required">*</span></label><input type="date" id="tv_expiry_date" required></div></div>' +
                '<button type="submit" class="btn-submit">✅ Create TV Licence</button>' +
            '</form>';
            
           window.viewTVLicence = async function(licenceId) {
    try {
        var res = await fetch(API_BASE + 'licence_list.php?school_id=' + (activeTVContext ? activeTVContext.schoolId : '') + '&_=' + Date.now());
        var data = await res.json();
        if (!data.success || !data.data || !data.data.tv) {
            toastr.error('Could not fetch licence details');
            return;
        }
        var licence = null;
        for (var i = 0; i < data.data.tv.length; i++) {
            if (data.data.tv[i].licence_id == licenceId) {
                licence = data.data.tv[i];
                break;
            }
        }
        if (!licence) {
            toastr.error('Licence not found');
            return;
        }
        var status = licence.expiry_date && new Date(licence.expiry_date) > new Date() ? 'Active' : 'Expired';
        var apiKeyValue = licence.tv_api_key || 'Not Generated Yet';
        var detailsHtml = '<div style="background:#f8f9fa; padding:20px; border-radius:8px; font-family:monospace; font-size:14px; line-height:1.8;">' +
            '<p><strong> API Key:</strong> ' + escapeHtml(apiKeyValue) + '</p>' +
            '<p><strong> Licence ID:</strong> ' + licence.licence_id + '</p>' +
            '<p><strong> Class:</strong> ' + (licence.class_id || '-') + '</p>' +
            '<p><strong> Used Status:</strong> ' + (licence.used_status === 'Y' ? '<span class="used-yes">✓ Used</span>' : '<span class="used-no">✗ Not Used</span>') + '</p>' +
            '<p><strong> Joining Date:</strong> ' + (licence.joining_date || '-') + '</p>' +
            '<p><strong> Expiry Date:</strong> ' + (licence.expiry_date || '-') + '</p>' +
            '<p><strong> Status:</strong> <span class="' + (status === 'Active' ? 'status-active' : 'status-inactive') + '">' + status + '</span></p>' +
            '</div><div style="margin-top: 20px;"><button type="button" onclick="closePanel()" class="btn-submit" style="background:#6c757d;">Close</button></div>';
        openPanel('TV Licence Details - Class ' + licence.class_id, detailsHtml, null, '550px', '👁️');
    } catch(e) {
        console.error('Error loading licence:', e);
        toastr.error('Error loading licence details');
    }
};
            
            window.editTVLicence = async function(licenceId) {
    try {
        var res = await fetch(API_BASE + 'licence_list.php?school_id=' + (activeTVContext ? activeTVContext.schoolId : '') + '&_=' + Date.now());
        var data = await res.json();
        if (!data.success || !data.data || !data.data.tv) {
            toastr.error('Could not fetch licence details');
            return;
        }
        var licence = null;
        for (var i = 0; i < data.data.tv.length; i++) {
            if (data.data.tv[i].licence_id == licenceId) {
                licence = data.data.tv[i];
                break;
            }
        }
        if (!licence) {
            toastr.error('Licence not found');
            return;
        }
        
        // Get classes list for dropdown
        var classRes = await fetch(API_BASE + 'classes_list.php?school_id=' + (activeTVContext ? activeTVContext.schoolId : ''));
        var classData = await classRes.json();
        var classList = [];
        if (classData.success && classData.data) {
            var seen = new Set();
            for (var i = 0; i < classData.data.length; i++) {
                if (!seen.has(classData.data[i].class_id)) {
                    seen.add(classData.data[i].class_id);
                    classList.push(classData.data[i]);
                }
            }
        }
        if (classList.length === 0) {
            for (var i = 1; i <= 12; i++) classList.push({ class_id: i });
        }
        
        var classOptions = '';
        for (var i = 0; i < classList.length; i++) {
            var selected = classList[i].class_id == licence.class_id ? 'selected' : '';
            classOptions += '<option value="' + classList[i].class_id + '" ' + selected + '>Class ' + classList[i].class_id + '</option>';
        }
        
        var formatDate = function(date) {
            if (!date || date === '0000-00-00') return '';
            return date;
        };
        
        var editFormHtml = `
            <form id="editTVFormModal" novalidate>
                <input type="hidden" id="edit_licence_id" value="${licence.licence_id}">
                <div class="form-group">
                    <label>API Key</label>
                    <input type="text" id="edit_tv_api_key" value="${escapeHtml(licence.tv_api_key || 'Not Generated Yet')}" readonly style="background:#e9ecef; cursor:not-allowed; font-family:monospace;">
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
        
        openPanel('Edit TV Licence #' + licenceId, editFormHtml, null, '550px', '✏️');
        
        setTimeout(function() {
            var form = document.getElementById('editTVFormModal');
            if (form) {
                form.onsubmit = async function(e) {
                    e.preventDefault();
                    
                    var updateData = {
                        licence_id: licenceId,
                        class_id: parseInt(document.getElementById('edit_tv_class_id').value),
                        used_status: document.getElementById('edit_tv_used_status').value,
                        joining_date: document.getElementById('edit_tv_joining_date').value,
                        expiry_date: document.getElementById('edit_tv_expiry_date').value
                    };
                    
                    Swal.fire({ title: 'Updating...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
                    
                    var res = await fetch(API_BASE + 'licence_update.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(updateData)
                    });
                    var result = await res.json();
                    
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
            
            async function loadTVHistory() {
                var historyContainer = document.getElementById('tvHistory');
                if (!historyContainer) return;
                historyContainer.innerHTML = '<div style="text-align:center; padding:20px;"><div class="spinner" style="margin:0 auto;"></div><p>Loading licences...</p></div>';
                try {
                    var res = await fetch(API_BASE + 'licence_list.php?school_id=' + schoolId + '&_=' + Date.now());
                    var data = await res.json();
                    var tvLicences = [];
                    if (data && data.success && data.data && data.data.tv) {
                        tvLicences = data.data.tv;
                    }
                    if (tvLicences.length > 0) {
                        var tableHtml = '<div style="overflow-x: auto;"><table style="width:100%; border-collapse: collapse; font-size:13px;"><thead><tr style="background:#f8f9fa;"><th style="padding:10px;">ID</th><th style="padding:10px;">Class</th><th style="padding:10px;">API Key</th><th style="padding:10px;">Used Status</th><th style="padding:10px;">Joining Date</th><th style="padding:10px;">Expiry Date</th><th style="padding:10px;">Status</th><th style="padding:10px;">Actions</th></tr></thead><tbody>';
                        for (var i = 0; i < tvLicences.length; i++) {
                            var l = tvLicences[i];
                            var isDeleted = l.is_deleted == 1;
                            var apiKeyDisplay = l.tv_api_key || l.api_key || 'Not Set';
                            tableHtml += '<tr' + (isDeleted ? ' style="background:#fff5f5; opacity:0.7;"' : '') + '>' +
                                '<td style="padding:8px;">' + l.licence_id + '</td>' +
                                '<td style="padding:8px;">Class ' + l.class_id + (isDeleted ? ' <span style="color:#dc3545;">(Deleted)</span>' : '') + '</td>' +
                                '<td style="padding:8px;"><code style="font-size:11px; background:#f0f0f0; padding:4px 8px; border-radius:4px;">' + escapeHtml(apiKeyDisplay) + '</code></td>' +
                                '<td style="padding:8px;">' + (l.used_status === 'Y' ? '<span class="used-yes">✓ Used</span>' : '<span class="used-no">✗ Not Used</span>') + '</td>' +
                                '<td style="padding:8px;">' + (l.joining_date || '-') + '</td>' +
                                '<td style="padding:8px;">' + (l.expiry_date || '-') + '</td>' +
                                '<td style="padding:8px;">' + (l.expiry_date && new Date(l.expiry_date) > new Date() ? '<span class="status-active">Active</span>' : '<span class="status-inactive">Expired</span>') + '</td>' +
                                '<td style="padding:8px;">' + (!isDeleted ? '<button class="btn btn-info" onclick="viewTVLicence(' + l.licence_id + ')" style="background:#17a2b8; color:white; padding:4px 8px; margin-right:4px;">👁️ View</button><button class="btn btn-warning" onclick="editTVLicence(' + l.licence_id + ')" style="background:#ffc107; color:#333; padding:4px 8px; margin-right:4px;">✏️ Edit</button><button class="btn btn-danger" onclick="deleteTVLicence(' + l.licence_id + ')" style="padding:4px 8px;">🗑️ Delete</button>' : '<span style="color:#666;">Archived</span>') + '</td>' +
                                '</tr>';
                        }
                        tableHtml += '</tbody></table></div>';
                        historyContainer.innerHTML = tableHtml;
                    } else {
                        historyContainer.innerHTML = '<p style="padding:15px; text-align:center;">No TV licences found.</p>';
                    }
                } catch(e) {
                    console.error('Error loading TV licences:', e);
                    historyContainer.innerHTML = '<p style="padding:15px; text-align:center; color:#dc3545;">Error loading licences.</p>';
                }
            }
            
            var formHtml = '<div class="toggle-container">' +
                '<button class="toggle-btn active" onclick="showTVToggleContent(\'tvCreate\')">➕ Create TV Licence</button>' +
                '<button class="toggle-btn" onclick="showTVToggleContent(\'tvHistory\')">📜 TV History</button>' +
            '</div>' +
            '<div id="tvCreate" class="toggle-content active-content">' + createFormHtml + '</div>' +
            '<div id="tvHistory" class="toggle-content">Loading...</div>';
            
            openPanel('TV Management - ' + escapeHtml(schoolName), formHtml, null, PANEL_WIDTHS.tv, '📺');
            
            window.showTVToggleContent = function(activeId) {
                var contents = document.querySelectorAll('#tvCreate, #tvHistory');
                for (var i = 0; i < contents.length; i++) contents[i].classList.remove('active-content');
                var btns = document.querySelectorAll('.toggle-btn');
                for (var i = 0; i < btns.length; i++) btns[i].classList.remove('active');
                document.getElementById(activeId).classList.add('active-content');
                if (activeId === 'tvHistory') { loadTVHistory(); }
            };
            
            setTimeout(function() {
                var today = new Date().toISOString().split('T')[0];
                var nextYear = new Date();
                nextYear.setFullYear(nextYear.getFullYear() + 1);
                var classIdEl = document.getElementById('tv_class_id');
                var apiKeyEl = document.getElementById('tv_api_key');
                if (document.getElementById('tv_joining_date')) {
                    document.getElementById('tv_joining_date').value = today;
                    document.getElementById('tv_expiry_date').value = nextYear.toISOString().split('T')[0];
                }
                if (apiKeyEl && classIdEl) {
                    var generateKeyForClass = function() { apiKeyEl.value = generateTVApiKey(classIdEl.value); };
                    generateKeyForClass();
                    classIdEl.addEventListener('change', generateKeyForClass);
                }
                var form = document.getElementById('createTVForm');
                if (form) {
                    form.onsubmit = async function(e) {
                        e.preventDefault();
                        clearAllErrors('createTVForm');
                        var isValid = true;
                        if (!validateRequired('tv_class_id', 'Class')) isValid = false;
                        if (!validateRequired('tv_used_status', 'Used Status')) isValid = false;
                        if (!validateRequired('tv_joining_date', 'Joining Date')) isValid = false;
                        if (!validateRequired('tv_expiry_date', 'Expiry Date')) isValid = false;
                        if (!isValid) { return; }
                        var confirmResult = await Swal.fire({ title: 'Confirm Creation', text: 'Create TV Licence?', icon: 'question', showCancelButton: true });
                        if (confirmResult.isConfirmed) {
                            Swal.fire({ title: 'Creating...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
                            var requestData = {
                                licence_type: 'tv',
                                school_id: schoolId,
                                class_id: document.getElementById('tv_class_id').value,
                                used_status: document.getElementById('tv_used_status').value,
                                joining_date: document.getElementById('tv_joining_date').value,
                                expiry_date: document.getElementById('tv_expiry_date').value,
                                tv_api_key: document.getElementById('tv_api_key').value
                            };
                            var tvRes = await fetch(API_BASE + 'licence_add.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(requestData) });
                            var tvResult = await tvRes.json();
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
        
        // ========== LMS SECTION (FIXED) ==========
        window.openLMSSection = async function(schoolId, schoolName) {
            activeLMSContext = { schoolId: schoolId, schoolName: schoolName };
            var classes = [];
            var batches = [];
            
            try {
                var res = await fetch(API_BASE + 'classes_list.php?school_id=' + schoolId);
                var data = await res.json();
                if (data.success && data.data) {
                    var seenIds = new Set();
                    for (var i = 0; i < data.data.length; i++) {
                        var c = data.data[i];
                        if (!seenIds.has(c.class_id)) {
                            seenIds.add(c.class_id);
                            classes.push({ class_id: c.class_id, class_name: 'Class ' + c.class_id });
                        }
                        batches.push(c);
                    }
                }
            } catch(e) { console.error('Error loading classes:', e); }
            
            if (classes.length === 0) {
                for (var i = 1; i <= 12; i++) classes.push({ class_id: i, class_name: 'Class ' + i });
            }
            
            var classOptions = '';
            for (var i = 0; i < classes.length; i++) {
                classOptions += '<option value="' + classes[i].class_id + '">Class ' + classes[i].class_id + '</option>';
            }
            
            var batchOptions = '<option value="">Select Batch/Section</option>';
            for (var i = 0; i < batches.length; i++) {
                var b = batches[i];
                batchOptions += '<option value="' + b.batch_id + '">' + (b.section || getBatchLetter(b.batch_id)) + '</option>';
            }
            
            var createFormHtml = '<form id="createLMSForm" novalidate>' +
                '<div class="form-group"><label>Class <span class="required">*</span></label><select id="lms_class_id" required>' + classOptions + '</select></div>' +
                '<div class="form-group"><label>Batch / Section</label><select id="lms_batch_id">' + batchOptions + '</select></div>' +
                '<div class="form-group"><label>Subscription Type <span class="required">*</span></label><select id="lms_subscription_type" required><option value="D">Demo</option><option value="B">Basic</option><option value="P">Premium</option></select></div>' +
                '<div class="form-row"><div class="form-group"><label>Subscription Quantity <span class="required">*</span></label><input type="number" id="lms_subscription_qty" value="1" min="1" required></div><div class="form-group"><label>Available Quantity</label><input type="number" id="lms_available_qty" min="0" step="1"><small>Default: Subscription Quantity - 1</small></div></div>' +
                '<div class="form-row"><div class="form-group"><label>Joining Date <span class="required">*</span></label><input type="date" id="lms_joining_date" required></div><div class="form-group"><label>Expiry Date <span class="required">*</span></label><input type="date" id="lms_expiry_date" required></div></div>' +
                '<button type="submit" class="btn-submit">✅ Create LMS Licence</button>' +
            '</form>';
            
            window.viewLMSLicence = async function(licenceId) {
                try {
                    var res = await fetch(API_BASE + 'licence_list.php?school_id=' + (activeLMSContext ? activeLMSContext.schoolId : '') + '&_=' + Date.now());
                    var data = await res.json();
                    if (!data.success || !data.data || !data.data.lms) {
                        toastr.error('Could not fetch licence details');
                        return;
                    }
                    var licence = null;
                    for (var i = 0; i < data.data.lms.length; i++) {
                        if (data.data.lms[i].licence_id == licenceId) {
                            licence = data.data.lms[i];
                            break;
                        }
                    }
                    if (!licence) {
                        toastr.error('Licence not found');
                        return;
                    }
                    var status = licence.expiry_date && new Date(licence.expiry_date) > new Date() ? 'Active' : 'Expired';
                    var subscriptionText = licence.subscription_type === 'D' ? 'Demo' : licence.subscription_type === 'B' ? 'Basic' : 'Premium';
                    var batchDisplay = licence.batch_id ? (licence.section || getBatchLetter(licence.batch_id)) : '-';
                    var detailsHtml = '<div style="background:#f8f9fa; padding:20px; border-radius:8px; font-family:monospace; font-size:14px; line-height:1.8;">' +
                        '<p><strong> Licence ID:</strong> ' + licence.licence_id + '</p>' +
                        '<p><strong> Class:</strong> ' + (licence.class_id || '-') + '</p>' +
                        '<p><strong> Batch/Section:</strong> ' + batchDisplay + '</p>' +
                        '<p><strong> Subscription Type:</strong> ' + subscriptionText + '</p>' +
                        '<p><strong> Subscription Quantity:</strong> ' + (licence.subscription_qty || 1) + '</p>' +
                        '<p><strong> Available Quantity:</strong> ' + (licence.available_qty || 0) + '</p>' +
                        '<p><strong> Joining Date:</strong> ' + (licence.joining_date || '-') + '</p>' +
                        '<p><strong> Expiry Date:</strong> ' + (licence.expiry_date || '-') + '</p>' +
                        '<p><strong> Status:</strong> <span class="' + (status === 'Active' ? 'status-active' : 'status-inactive') + '">' + status + '</span></p>' +
                        '</div><div style="margin-top: 20px;"><button type="button" onclick="closePanel()" class="btn-submit" style="background:#6c757d;">Close</button></div>';
                    openPanel('LMS Licence Details - Class ' + licence.class_id, detailsHtml, null, '550px', '👁️');
                } catch(e) {
                    console.error('Error loading licence:', e);
                    toastr.error('Error loading licence details');
                }
            };
            
           window.editLMSLicence = async function(licenceId) {
    try {
        var res = await fetch(API_BASE + 'licence_list.php?school_id=' + (activeLMSContext ? activeLMSContext.schoolId : '') + '&_=' + Date.now());
        var data = await res.json();
        if (!data.success || !data.data || !data.data.lms) {
            toastr.error('Could not fetch licence details');
            return;
        }
        var licence = null;
        for (var i = 0; i < data.data.lms.length; i++) {
            if (data.data.lms[i].licence_id == licenceId) {
                licence = data.data.lms[i];
                break;
            }
        }
        if (!licence) {
            toastr.error('Licence not found');
            return;
        }
        
        // Get classes and batches for dropdowns
        var classRes = await fetch(API_BASE + 'classes_list.php?school_id=' + (activeLMSContext ? activeLMSContext.schoolId : ''));
        var classData = await classRes.json();
        var classList = [];
        var batchList = [];
        
        if (classData.success && classData.data) {
            var seen = new Set();
            for (var i = 0; i < classData.data.length; i++) {
                if (!seen.has(classData.data[i].class_id)) {
                    seen.add(classData.data[i].class_id);
                    classList.push(classData.data[i]);
                }
                batchList.push(classData.data[i]);
            }
        }
        if (classList.length === 0) {
            for (var i = 1; i <= 12; i++) classList.push({ class_id: i });
        }
        
        var classOptions = '';
        for (var i = 0; i < classList.length; i++) {
            var selected = classList[i].class_id == licence.class_id ? 'selected' : '';
            classOptions += '<option value="' + classList[i].class_id + '" ' + selected + '>Class ' + classList[i].class_id + '</option>';
        }
        
        // Filter batches for the selected class
        var filteredBatches = batchList.filter(function(b) { return b.class_id == licence.class_id; });
        var batchOptions = '<option value="">Select Batch/Section</option>';
        for (var i = 0; i < filteredBatches.length; i++) {
            var b = filteredBatches[i];
            var selected = b.batch_id == licence.batch_id ? 'selected' : '';
            var displayName = b.section ? 'Section ' + b.section : 'Batch ' + b.batch_id;
            batchOptions += '<option value="' + b.batch_id + '" ' + selected + '>' + displayName + '</option>';
        }
        
        var formatDate = function(date) {
            if (!date || date === '0000-00-00') return '';
            return date;
        };
        
        var subscriptionText = '';
        if (licence.subscription_type === 'D') subscriptionText = 'Demo';
        else if (licence.subscription_type === 'B') subscriptionText = 'Basic';
        else if (licence.subscription_type === 'P') subscriptionText = 'Premium';
        
        var editFormHtml = `
            <form id="editLMSFormModal" novalidate>
                <input type="hidden" id="edit_licence_id" value="${licence.licence_id}">
                <div class="form-group">
                    <label>Class <span class="required">*</span></label>
                    <select id="edit_lms_class_id" required class="form-control" onchange="updateLMSBatches(this.value)">${classOptions}</select>
                </div>
                <div class="form-group">
                    <label>Batch / Section</label>
                    <select id="edit_lms_batch_id" class="form-control">${batchOptions}</select>
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
            <script>
                window.updateLMSBatches = async function(classId) {
                    var batchSelect = document.getElementById('edit_lms_batch_id');
                    if (!batchSelect) return;
                    var res = await fetch(API_BASE + 'classes_list.php?school_id=${activeLMSContext ? activeLMSContext.schoolId : ''}');
                    var data = await res.json();
                    if (data.success && data.data) {
                        var filtered = data.data.filter(function(b) { return b.class_id == classId; });
                        batchSelect.innerHTML = '<option value="">Select Batch/Section</option>';
                        for (var i = 0; i < filtered.length; i++) {
                            var b = filtered[i];
                            var displayName = b.section ? 'Section ' + b.section : 'Batch ' + b.batch_id;
                            batchSelect.innerHTML += '<option value="' + b.batch_id + '">' + displayName + '</option>';
                        }
                    }
                };
            <\/script>
        `;
        
        openPanel('Edit LMS Licence #' + licenceId, editFormHtml, null, '600px', '✏️');
        
        setTimeout(function() {
            var form = document.getElementById('editLMSFormModal');
            if (form) {
                form.onsubmit = async function(e) {
                    e.preventDefault();
                    
                    var updateData = {
                        licence_id: licenceId,
                        class_id: parseInt(document.getElementById('edit_lms_class_id').value),
                        batch_id: document.getElementById('edit_lms_batch_id').value || null,
                        subscription_type: document.getElementById('edit_lms_subscription_type').value,
                        subscription_qty: parseInt(document.getElementById('edit_lms_subscription_qty').value),
                        available_qty: parseInt(document.getElementById('edit_lms_available_qty').value || 0),
                        joining_date: document.getElementById('edit_lms_joining_date').value,
                        expiry_date: document.getElementById('edit_lms_expiry_date').value
                    };
                    
                    Swal.fire({ title: 'Updating...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
                    
                    var res = await fetch(API_BASE + 'licence_update.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(updateData)
                    });
                    var result = await res.json();
                    
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
            
            async function loadLMSHistory() {
                var historyContainer = document.getElementById('lmsHistory');
                if (!historyContainer) return;
                historyContainer.innerHTML = '<div style="text-align:center; padding:20px;"><div class="spinner" style="margin:0 auto;"></div><p>Loading licences...</p></div>';
                try {
                    var res = await fetch(API_BASE + 'licence_list.php?school_id=' + schoolId + '&_=' + Date.now());
                    var data = await res.json();
                    var licences = [];
                    if (data && data.success && data.data && data.data.lms) {
                        licences = data.data.lms;
                    }
                    if (licences.length > 0) {
                        var tableHtml = '<div style="overflow-x: auto;"><table style="width:100%; border-collapse: collapse; font-size:13px;"><thead><tr style="background:#f8f9fa;"><th style="padding:10px;">ID</th><th style="padding:10px;">Class</th><th style="padding:10px;">Batch</th><th style="padding:10px;">Subscription</th><th style="padding:10px;">Qty/Avail</th><th style="padding:10px;">Joining Date</th><th style="padding:10px;">Expiry Date</th><th style="padding:10px;">Status</th><th style="padding:10px;">Actions</th></tr></thead><tbody>';
                        for (var i = 0; i < licences.length; i++) {
                            var l = licences[i];
                            var batchDisplay = l.batch_id ? (l.section || getBatchLetter(l.batch_id)) : '-';
                            tableHtml += '<tr>' +
                                '<td style="padding:8px;">' + (l.licence_id || '-') + '</td>' +
                                '<td style="padding:8px;">Class ' + (l.class_id || '-') + '</td>' +
                                '<td style="padding:8px;"><span class="batch-badge">' + batchDisplay + '</span></td>' +
                                '<td style="padding:8px;">' + (l.subscription_type === 'D' ? 'Demo' : l.subscription_type === 'B' ? 'Basic' : 'Premium') + '</td>' +
                                '<td style="padding:8px;">' + (l.subscription_qty || 1) + ' / ' + (l.available_qty || 0) + '</td>' +
                                '<td style="padding:8px;">' + (l.joining_date || '-') + '</td>' +
                                '<td style="padding:8px;">' + (l.expiry_date || '-') + '</td>' +
                                '<td style="padding:8px;">' + (l.expiry_date && new Date(l.expiry_date) > new Date() ? '<span class="status-active">Active</span>' : '<span class="status-inactive">Expired</span>') + '</td>' +
                                '<td style="padding:8px;"><button class="btn btn-info" onclick="viewLMSLicence(' + l.licence_id + ')" style="background:#17a2b8; color:white; padding:4px 8px; margin-right:4px;">👁️ View</button><button class="btn btn-warning" onclick="editLMSLicence(' + l.licence_id + ')" style="background:#ffc107; color:#333; padding:4px 8px; margin-right:4px;">✏️ Edit</button><button class="btn btn-danger" onclick="deleteLMSLicence(' + l.licence_id + ')" style="padding:4px 8px;">🗑️ Delete</button></td>' +
                                '</tr>';
                        }
                        tableHtml += '</tbody></table></div>';
                        historyContainer.innerHTML = tableHtml;
                    } else {
                        historyContainer.innerHTML = '<p style="padding:15px; text-align:center;">No LMS licences found.</p>';
                    }
                } catch(e) {
                    console.error('Error loading LMS licences:', e);
                    historyContainer.innerHTML = '<p style="padding:15px; text-align:center; color:#dc3545;">Error loading licences.</p>';
                }
            }
            
            var formHtml = '<div class="toggle-container">' +
                '<button class="toggle-btn active" onclick="showLmsToggleContent(\'lmsCreate\')">➕ Create LMS Licence</button>' +
                '<button class="toggle-btn" onclick="showLmsToggleContent(\'lmsHistory\')">📜 LMS History</button>' +
            '</div>' +
            '<div id="lmsCreate" class="toggle-content active-content">' + createFormHtml + '</div>' +
            '<div id="lmsHistory" class="toggle-content">Loading...</div>';
            
            openPanel('LMS Management - ' + escapeHtml(schoolName), formHtml, null, PANEL_WIDTHS.lms, '📚');
            
            window.showLmsToggleContent = function(activeId) {
                var contents = document.querySelectorAll('#lmsCreate, #lmsHistory');
                for (var i = 0; i < contents.length; i++) contents[i].classList.remove('active-content');
                var btns = document.querySelectorAll('.toggle-btn');
                for (var i = 0; i < btns.length; i++) btns[i].classList.remove('active');
                document.getElementById(activeId).classList.add('active-content');
                if (activeId === 'lmsHistory') { loadLMSHistory(); }
            };
            
            setTimeout(function() {
                var today = new Date().toISOString().split('T')[0];
                var nextYear = new Date();
                nextYear.setFullYear(nextYear.getFullYear() + 1);
                if (document.getElementById('lms_joining_date')) {
                    document.getElementById('lms_joining_date').value = today;
                    document.getElementById('lms_expiry_date').value = nextYear.toISOString().split('T')[0];
                }
                var subscriptionQtyInput = document.getElementById('lms_subscription_qty');
                var availableQtyInput = document.getElementById('lms_available_qty');
                if (subscriptionQtyInput && availableQtyInput) {
                    subscriptionQtyInput.addEventListener('input', function() {
                        var subscriptionQty = parseInt(this.value) || 0;
                        availableQtyInput.value = subscriptionQty > 0 ? subscriptionQty - 1 : 0;
                    });
                    availableQtyInput.value = 0;
                }
                var form = document.getElementById('createLMSForm');
                if (form) {
                    form.onsubmit = async function(e) {
                        e.preventDefault();
                        clearAllErrors('createLMSForm');
                        var subscriptionQty = parseInt(document.getElementById('lms_subscription_qty').value);
                        var availableQty = parseInt(document.getElementById('lms_available_qty').value) || (subscriptionQty > 0 ? subscriptionQty - 1 : 0);
                        var formData = {
                            licence_type: 'lms',
                            school_id: parseInt(schoolId),
                            class_id: parseInt(document.getElementById('lms_class_id').value),
                            batch_id: document.getElementById('lms_batch_id').value || null,
                            subscription_type: document.getElementById('lms_subscription_type').value,
                            subscription_qty: subscriptionQty,
                            available_qty: availableQty,
                            joining_date: document.getElementById('lms_joining_date').value,
                            expiry_date: document.getElementById('lms_expiry_date').value,
                            order_id: 'ORD-' + schoolId + '-' + Date.now(),
                            amount: 0, paid_amount: 0, discount: 0, gateway_charges: 0, tax: 0, currency: 'INR', payment_method: null
                        };
                        var confirmResult = await Swal.fire({ title: 'Confirm Creation', text: 'Create LMS Licence?', icon: 'question', showCancelButton: true });
                        if (confirmResult.isConfirmed) {
                            Swal.fire({ title: 'Creating...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
                            var res = await fetch(API_BASE + 'licence_add.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(formData) });
                            var result = await res.json();
                            if (result.success) {
                                Swal.fire({ icon: 'success', title: 'LMS Licence Created!', text: result.message });
                                setTimeout(function() { loadLMSHistory(); refreshActiveLMSPanel(); }, 1000);
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
        
        // ========== MANAGE CLASSES (FIXED) ==========
        window.manageClasses = async function(schoolId, schoolName) {
            activeClassesContext = { schoolId: schoolId, schoolName: schoolName };
            var existingClasses = [];
            try {
                var res = await fetch(API_BASE + 'classes_list.php?school_id=' + schoolId + '&_=' + Date.now());
                var data = await res.json();
                if (data.success && data.data) {
                    existingClasses = data.data;
                }
            } catch(e) { console.error('Error loading classes:', e); }
            
            var classOptions = '';
            for (var i = 1; i <= 12; i++) {
                classOptions += '<option value="' + i + '">Class ' + i + '</option>';
            }
            
            var classesTableHtml = '';
            if (existingClasses.length > 0) {
                classesTableHtml = '<div style="overflow-x: auto; margin-top: 20px;"><h4>Existing Classes</h4><table style="width:100%; border-collapse: collapse; margin-top:10px;"><thead><tr><th style="padding:10px; background:#f8f9fa;">Class</th><th style="padding:10px; background:#f8f9fa;">Section</th><th style="padding:10px; background:#f8f9fa;">Board</th><th style="padding:10px; background:#f8f9fa;">Academic Year</th><th style="padding:10px; background:#f8f9fa;">Actions</th></tr></thead><tbody>';
                for (var i = 0; i < existingClasses.length; i++) {
                    var c = existingClasses[i];
                    classesTableHtml += '<tr><td style="padding:8px; border-bottom:1px solid #e9ecef;">' + c.class_id + '</td>' +
                        '<td style="padding:8px; border-bottom:1px solid #e9ecef;">' + (c.section || 'A') + '</td>' +
                        '<td style="padding:8px; border-bottom:1px solid #e9ecef;">' + (c.board_id === 'C' ? 'CBSE' : c.board_id === 'I' ? 'ICSE' : 'WBBSE') + '</td>' +
                        '<td style="padding:8px; border-bottom:1px solid #e9ecef;">' + (c.academic_year || new Date().getFullYear()) + '</td>' +
                        '<td style="padding:8px; border-bottom:1px solid #e9ecef;"><button class="btn btn-danger" onclick="deleteClass(' + (c.batch_id || c.id) + ')" style="padding:4px 12px;">🗑️ Delete</button></td></tr>';
                }
                classesTableHtml += '</tbody></table></div>';
            } else {
                classesTableHtml = '<p style="margin-top:20px;">No classes found. Add classes below.</p>';
            }
            
            var formHtml = '<form id="addClassForm" novalidate>' +
                '<div class="form-row"><div class="form-group"><label>Class Number *</label><select id="class_id">' + classOptions + '</select></div>' +
                '<div class="form-group"><label>Section *</label><input type="text" id="section" value="A" placeholder="Section" required></div></div>' +
                '<div class="form-row"><div class="form-group"><label>Board</label><select id="class_board_id"><option value="C">CBSE</option><option value="I">ICSE</option><option value="W">WBBSE</option></select></div>' +
                '<div class="form-group"><label>Academic Year</label><input type="number" id="academic_year" value="' + new Date().getFullYear() + '"></div></div>' +
                '<button type="submit" class="btn-submit">➕ Add Class</button>' +
            '</form>' + classesTableHtml;
            
            openPanel('Manage Classes - ' + escapeHtml(schoolName), formHtml, null, PANEL_WIDTHS.classes, '📚');
            
            setTimeout(function() {
                var form = document.getElementById('addClassForm');
                if (form) {
                    form.onsubmit = async function(e) {
                        e.preventDefault();
                        var classId = document.getElementById('class_id').value;
                        var section = document.getElementById('section').value.trim().toUpperCase();
                        var academicYear = document.getElementById('academic_year').value;
                        var boardId = document.getElementById('class_board_id').value;
                        
                        if (!classId || !section) {
                            toastr.error('Class and Section are required');
                            return;
                        }
                        
                        var exists = false;
                        for (var i = 0; i < existingClasses.length; i++) {
                            if (existingClasses[i].class_id == classId && existingClasses[i].section === section) {
                                exists = true;
                                break;
                            }
                        }
                        if (exists) {
                            toastr.error('Class ' + classId + ' with Section "' + section + '" already exists for this school!');
                            return;
                        }
                        
                        var formData = {
                            school_id: schoolId,
                            class_id: parseInt(classId),
                            section: section,
                            board_id: boardId,
                            academic_year: academicYear,
                            student_count: 100,
                            class_name: 'Class ' + classId + ' - Section ' + section
                        };
                        
                        Swal.fire({ title: 'Adding...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
                        
                        var res = await fetch(API_BASE + 'class_add.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(formData)
                        });
                        var result = await res.json();
                        
                        if (result.success) {
                            Swal.fire({ icon: 'success', title: 'Added!', text: result.message });
                            closePanel();
                            setTimeout(function() { manageClasses(schoolId, schoolName); }, 500);
                        } else {
                            Swal.fire({ icon: 'error', title: 'Failed', text: result.message });
                        }
                    };
                }
            }, 100);
        };
        
        window.deleteClass = async function(batchId) {
            var result = await Swal.fire({ title: 'Delete Class?', text: 'Are you sure you want to delete this class?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete!' });
            if (result.isConfirmed) {
                Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
                var res = await fetch(API_BASE + 'class_delete.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ batch_id: batchId }) });
                var data = await res.json();
                if (data.success) { 
                    Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message });
                    if (activeClassesContext) {
                        closePanel();
                        setTimeout(function() { manageClasses(activeClassesContext.schoolId, activeClassesContext.schoolName); }, 500);
                    }
                } else { 
                    Swal.fire({ icon: 'error', title: 'Failed', text: data.message }); 
                }
            }
        };
        
        // ========== CREATE SCHOOL ==========
        window.openCreateSchool = function() {
            var currentYear = new Date().getFullYear();
            var academicYearOptions = '';
            for (var i = currentYear; i <= currentYear + 10; i++) {
                academicYearOptions += '<option value="' + i + '">' + i + '</option>';
            }
            var formHtml = '<form id="createSchoolForm" novalidate>' +
                '<div class="form-row"><div class="form-group"><label>School Code *</label><input type="text" id="create_school_code" placeholder="Unique code"></div><div class="form-group"><label>School Name *</label><input type="text" id="create_school_name" placeholder="Full name"></div></div>' +
                '<div class="form-row"><div class="form-group"><label>Board *</label><select id="create_board_id"><option value="C">CBSE</option><option value="I">ICSE</option><option value="W">WBBSE</option></select></div><div class="form-group"><label>Academic Year *</label><select id="create_academic_year" required><option value="">Select Academic Year</option>' + academicYearOptions + '</select></div></div>' +
                '<div class="form-row"><div class="form-group"><label>Status *</label><select id="create_status"><option value="A">Active</option><option value="I">Inactive</option></select></div></div>' +
                '<div class="form-group"><textarea id="create_address" rows="2" placeholder="Address"></textarea></div>' +
                '<div class="form-row"><div class="form-group"><label>City *</label><input type="text" id="create_city" placeholder="City" required></div><div class="form-group"><label>State *</label><input type="text" id="create_state" placeholder="State" required></div></div>' +
                '<div class="form-row"><div class="form-group"><label>Postal Code</label><input type="text" id="create_postal_code" placeholder="Postal Code"></div><div class="form-group"><label>Country *</label><select id="create_country_code"><option value="IN">India (+91)</option><option value="US">USA (+1)</option><option value="UK">UK (+44)</option></select></div></div>' +
                '<div class="form-row"><div class="form-group"><label>Contact Person</label><input type="text" id="create_contact_person"></div><div class="form-group"><label>Contact Email</label><input type="email" id="create_contact_email"></div></div>' +
                '<div class="form-row"><div class="form-group"><label>Contact Phone</label><input type="tel" id="create_contact_phone"></div><div class="form-group"><label>GST Number</label><input type="text" id="create_gst_number"></div></div>' +
                '<button type="submit" class="btn-submit">🏫 Create School</button>' +
            '</form>';
            openPanel('Create New School', formHtml, loadSchools, PANEL_WIDTHS.school, '🏫');
            setTimeout(function() {
                document.getElementById('createSchoolForm').onsubmit = async function(e) {
                    e.preventDefault();
                    var schoolCode = document.getElementById('create_school_code').value.trim().toUpperCase();
                    var schoolName = document.getElementById('create_school_name').value.trim();
                    var boardId = document.getElementById('create_board_id').value;
                    var academicYear = document.getElementById('create_academic_year').value;
                    var city = document.getElementById('create_city').value;
                    var state = document.getElementById('create_state').value;
                    if (!schoolCode) { toastr.error('School Code is required'); return; }
                    if (!schoolName) { toastr.error('School Name is required'); return; }
                    if (!academicYear) { toastr.error('Academic Year is required'); return; }
                    if (!city) { toastr.error('City is required'); return; }
                    if (!state) { toastr.error('State is required'); return; }
                    var result = await createSchool({
                        school_code: schoolCode, school_name: schoolName, board_id: boardId, academic_year: academicYear,
                        status: document.getElementById('create_status').value, address: document.getElementById('create_address').value,
                        city: city, state: state, postal_code: document.getElementById('create_postal_code').value,
                        country_code: document.getElementById('create_country_code').value,
                        contact_person: document.getElementById('create_contact_person').value,
                        contact_email: document.getElementById('create_contact_email').value,
                        contact_phone: document.getElementById('create_contact_phone').value,
                        gst_number: document.getElementById('create_gst_number').value
                    });
                    if (result.success) { Swal.fire({ icon: 'success', title: 'Success!', text: result.message }); closePanel(); loadSchools(); } 
                    else { Swal.fire({ icon: 'error', title: 'Failed', text: result.message }); }
                };
            }, 100);
        };
        
        window.openEditSchool = async function(schoolId) {
            try {
                var res = await fetch(API_BASE + 'school_list.php');
                var result = await res.json();
                var school = null;
                for (var i = 0; i < result.data.length; i++) {
                    if (result.data[i].school_id == schoolId) {
                        school = result.data[i];
                        break;
                    }
                }
                if (!school) { toastr.error('School not found'); return; }
                var currentYear = new Date().getFullYear();
                var academicYearOptions = '';
                for (var i = currentYear; i <= currentYear + 10; i++) {
                    var selected = (school.academic_year == i) ? 'selected' : '';
                    academicYearOptions += '<option value="' + i + '" ' + selected + '>' + i + '</option>';
                }
                var formHtml = '<form id="editSchoolForm" novalidate>' +
                    '<input type="hidden" id="edit_school_id" value="' + school.school_id + '">' +
                    '<div class="form-row"><div class="form-group"><label>School Code</label><input type="text" value="' + escapeHtml(school.school_code) + '" readonly class="readonly-field"></div><div class="form-group"><label>School Name *</label><input type="text" id="edit_school_name" value="' + escapeHtml(school.school_name) + '" required></div></div>' +
                    '<div class="form-row"><div class="form-group"><label>Board</label><select id="edit_board_id"><option value="C"' + (school.board_id === 'C' ? ' selected' : '') + '>CBSE</option><option value="I"' + (school.board_id === 'I' ? ' selected' : '') + '>ICSE</option><option value="W"' + (school.board_id === 'W' ? ' selected' : '') + '>WBBSE</option></select></div><div class="form-group"><label>Academic Year *</label><select id="edit_academic_year" required><option value="">Select Academic Year</option>' + academicYearOptions + '</select></div></div>' +
                    '<div class="form-row"><div class="form-group"><label>Status</label><select id="edit_status"><option value="A"' + (school.status === 'A' ? ' selected' : '') + '>Active</option><option value="I"' + (school.status === 'I' ? ' selected' : '') + '>Inactive</option></select></div></div>' +
                    '<div class="form-group"><textarea id="edit_address" rows="2">' + escapeHtml(school.address || '') + '</textarea></div>' +
                    '<div class="form-row"><div class="form-group"><label>City</label><input type="text" id="edit_city" value="' + escapeHtml(school.city || '') + '"></div><div class="form-group"><label>State</label><input type="text" id="edit_state" value="' + escapeHtml(school.state || '') + '"></div></div>' +
                    '<div class="form-row"><div class="form-group"><label>Postal Code</label><input type="text" id="edit_postal_code" value="' + escapeHtml(school.postal_code || '') + '"></div><div class="form-group"><label>Country</label><select id="edit_country_code"><option value="IN"' + (school.country_code === 'IN' ? ' selected' : '') + '>India</option><option value="US"' + (school.country_code === 'US' ? ' selected' : '') + '>USA</option><option value="UK"' + (school.country_code === 'UK' ? ' selected' : '') + '>UK</option></select></div></div>' +
                    '<div class="form-row"><div class="form-group"><label>Contact Person</label><input type="text" id="edit_contact_person" value="' + escapeHtml(school.contact_person || '') + '"></div><div class="form-group"><label>Contact Email</label><input type="email" id="edit_contact_email" value="' + escapeHtml(school.contact_email || '') + '"></div></div>' +
                    '<div class="form-row"><div class="form-group"><label>Contact Phone</label><input type="tel" id="edit_contact_phone" value="' + escapeHtml(school.contact_phone || '') + '"></div><div class="form-group"><label>GST Number</label><input type="text" id="edit_gst_number" value="' + escapeHtml(school.gst_number || '') + '"></div></div>' +
                    '<button type="submit" class="btn-submit">✏️ Update School</button>' +
                '</form>';
                openPanel('Edit School: ' + school.school_code, formHtml, loadSchools, PANEL_WIDTHS.school, '✏️');
                setTimeout(function() {
                    document.getElementById('editSchoolForm').onsubmit = async function(e) {
                        e.preventDefault();
                        var academicYear = document.getElementById('edit_academic_year').value;
                        if (!academicYear) { toastr.error('Academic Year is required'); return; }
                        var result = await updateSchool({
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
                        if (result.success) { Swal.fire({ icon: 'success', title: 'Updated!', text: result.message }); closePanel(); loadSchools(); } 
                        else { Swal.fire({ icon: 'error', title: 'Failed', text: result.message }); }
                    };
                }, 100);
            } catch(e) { toastr.error('Error loading school'); }
        };
        
        window.openLicenceSection = async function(schoolId, schoolName) {
            var tvLicences = [], lmsLicences = [];
            try {
                var licenceRes = await fetch(API_BASE + 'licence_list.php?school_id=' + schoolId);
                var licenceData = await licenceRes.json();
                if (licenceData.success) {
                    tvLicences = licenceData.data?.tv || [];
                    lmsLicences = licenceData.data?.lms || [];
                }
            } catch(e) {}
            var tvTableHtml = '<p>No TV licences found.</p>';
            if (tvLicences.length > 0) {
                tvTableHtml = '<table style="width:100%"><thead><tr><th>Class</th><th>Used Status</th><th>Joining Date</th><th>Expiry Date</th><th>Status</th></tr></thead><tbody>';
                for (var i = 0; i < tvLicences.length; i++) {
                    var l = tvLicences[i];
                    tvTableHtml += '<tr><td>Class ' + l.class_id + '</td><td>' + (l.used_status === 'Y' ? '✓ Used' : '✗ Not Used') + '</td><td>' + (l.joining_date || '-') + '</td><td>' + (l.expiry_date || '-') + '</td><td>' + (new Date(l.expiry_date) > new Date() ? '<span style="color:green;">Active</span>' : '<span style="color:red;">Expired</span>') + '</td></tr>';
                }
                tvTableHtml += '</tbody></table>';
            }
            var lmsTableHtml = '<p>No LMS licences found.</p>';
            if (lmsLicences.length > 0) {
                lmsTableHtml = '<table style="width:100%"><thead><tr><th>Class</th><th>Batch</th><th>Subscription</th><th>Qty/Avail</th><th>Joining Date</th><th>Expiry Date</th><th>Status</th></tr></thead><tbody>';
                for (var i = 0; i < lmsLicences.length; i++) {
                    var l = lmsLicences[i];
                    var batchDisplay = l.batch_id ? (l.section || getBatchLetter(l.batch_id)) : '-';
                    lmsTableHtml += '<tr><td>Class ' + l.class_id + '</td><td><span class="batch-badge">' + batchDisplay + '</span></td><td>' + (l.subscription_type === 'D' ? 'Demo' : l.subscription_type === 'B' ? 'Basic' : 'Premium') + '</td><td>' + (l.subscription_qty || 1) + '/' + (l.available_qty || 0) + '</td><td>' + (l.joining_date || '-') + '</td><td>' + (l.expiry_date || '-') + '</td><td>' + (new Date(l.expiry_date) > new Date() ? '<span style="color:green;">Active</span>' : '<span style="color:red;">Expired</span>') + '</td></tr>';
                }
                lmsTableHtml += '</tbody></table>';
            }
            var formHtml = '<h3>📺 TV Licences</h3>' + tvTableHtml + '<hr><h3>📚 LMS Licences</h3>' + lmsTableHtml;
            openPanel('Licences - ' + escapeHtml(schoolName), formHtml, null, PANEL_WIDTHS.licence, '📜');
        };
        
        // ========== SCHOOL LIST FUNCTIONS ==========
        var allSchools = [];
        var currentFilters = { search: '', academicYear: '', board: '' };
        
        async function fetchSchoolAcademicYear(schoolId) {
            try {
                var res = await fetch(API_BASE + 'classes_list.php?school_id=' + schoolId);
                var data = await res.json();
                if (data.success && data.data && data.data.length > 0) {
                    var academicYears = [];
                    for (var i = 0; i < data.data.length; i++) {
                        if (data.data[i].academic_year) academicYears.push(data.data[i].academic_year);
                    }
                    var uniqueYears = [...new Set(academicYears)];
                    return uniqueYears.length > 0 ? uniqueYears.join(', ') : 'Not Set';
                }
                return 'Not Set';
            } catch(e) { return 'Not Set'; }
        }
        
        async function loadSchools() {
            try {
                var res = await fetch(API_BASE + 'school_list.php');
                var result = await res.json();
                if (result.success) {
                    var schoolsWithYears = [];
                    for (var i = 0; i < result.data.length; i++) {
                        var school = result.data[i];
                        var academicYear = await fetchSchoolAcademicYear(school.school_id);
                        schoolsWithYears.push({ 
                            school_id: school.school_id,
                            school_code: school.school_code,
                            school_name: school.school_name,
                            board_name: getBoardName(school.board_id),
                            status: school.status,
                            academic_year: academicYear
                        });
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
            var academicYearsSet = new Set();
            for (var i = 0; i < schools.length; i++) {
                var school = schools[i];
                if (school.academic_year && school.academic_year !== 'Not Set') {
                    var years = school.academic_year.split(', ');
                    for (var j = 0; j < years.length; j++) {
                        academicYearsSet.add(years[j]);
                    }
                }
            }
            var academicYearSelect = document.getElementById('searchAcademicYear');
            if (academicYearSelect) {
                while (academicYearSelect.options.length > 1) academicYearSelect.remove(1);
                var sortedYears = Array.from(academicYearsSet).sort();
                for (var i = 0; i < sortedYears.length; i++) {
                    var option = document.createElement('option');
                    option.value = sortedYears[i];
                    option.textContent = sortedYears[i];
                    academicYearSelect.appendChild(option);
                }
            }
        }
        
        function renderSchoolsTable(schools) {
            var tbody = document.getElementById('schoolsList');
            if (schools.length === 0) { 
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">No schools found.</td></tr>';
                return;
            }
            var html = '';
            for (var i = 0; i < schools.length; i++) {
                var school = schools[i];
                html += '<tr>' +
                    '<td>' + school.school_id + '</td>' +
                    '<td><strong>' + escapeHtml(school.school_code) + '</strong></td>' +
                    '<td>' + escapeHtml(school.school_name) + '</td>' +
                    '<td>' + escapeHtml(school.board_name) + '</td>' +
                    '<td>' + escapeHtml(school.academic_year || 'Not Set') + '</td>' +
                    '<td><span class="status-' + (school.status === 'A' ? 'active' : 'inactive') + '">' + (school.status === 'A' ? 'Active' : 'Inactive') + '</span></td>' +
                    '<td class="action-buttons">' +
                        '<button class="btn btn-secondary" onclick="openEditSchool(' + school.school_id + ')">✏️ Edit</button>' +
                        '<button class="btn btn-secondary" onclick="manageClasses(' + school.school_id + ', \'' + escapeHtml(school.school_name) + '\')">📚 Classes</button>' +
                        '<button class="btn btn-secondary" onclick="openLicenceSection(' + school.school_id + ', \'' + escapeHtml(school.school_name) + '\')">📜 Licence</button>' +
                        '<button class="btn btn-secondary" onclick="openTVSection(' + school.school_id + ', \'' + escapeHtml(school.school_name) + '\')">📺 TV</button>' +
                        '<button class="btn btn-secondary" onclick="openLMSSection(' + school.school_id + ', \'' + escapeHtml(school.school_name) + '\')">📚 LMS</button>' +
                        '<button class="btn btn-secondary" onclick="openUserManagement(' + school.school_id + ', \'' + escapeHtml(school.school_name) + '\')">👥 Users</button>' +
                    '</td>' +
                '</tr>';
            }
            tbody.innerHTML = html;
        }
        
        function applyFilters() {
            currentFilters.search = document.getElementById('searchSchool').value.toLowerCase().trim();
            currentFilters.academicYear = document.getElementById('searchAcademicYear').value;
            currentFilters.board = document.getElementById('searchBoard').value;
            var filtered = [];
            for (var i = 0; i < allSchools.length; i++) {
                var school = allSchools[i];
                if (currentFilters.search && !school.school_code.toLowerCase().includes(currentFilters.search) && !school.school_name.toLowerCase().includes(currentFilters.search)) continue;
                if (currentFilters.academicYear && (!school.academic_year || !school.academic_year.includes(currentFilters.academicYear))) continue;
                if (currentFilters.board && school.board_name !== currentFilters.board) continue;
                filtered.push(school);
            }
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
            document.getElementById('createBtn').onclick = function() { window.openCreateSchool(); };
            document.getElementById('applySearchBtn').onclick = applyFilters;
            document.getElementById('resetSearchBtn').onclick = resetFilters;
            document.getElementById('searchSchool').addEventListener('keypress', function(e) { if (e.key === 'Enter') applyFilters(); });
            loadSchools();
        });
    </script>
</body>
</html>