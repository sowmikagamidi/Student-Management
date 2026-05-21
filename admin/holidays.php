<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Holidays Management - Tutorix</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background: #f0f2f5; }
        
        .app-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
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
        
        table { width: 100%; background: white; border-collapse: collapse; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e9ecef; }
        th { background: #f8f9fa; font-weight: 600; color: #495057; }
        tr:hover { background: #f8f9fa; }
        
        .badge-holiday { background: #d4edda; color: #155724; padding: 4px 12px; border-radius: 20px; font-size: 11px; display: inline-block; }
        
        .loading { text-align: center; padding: 50px; }
        .spinner { border: 3px solid #f3f3f3; border-top: 3px solid #4361ee; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 15px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 13px; }
        .form-group label .required { color: #dc3545; }
        .form-group input, .form-group select { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
        .form-row { display: flex; gap: 15px; flex-wrap: wrap; }
        .form-row .form-group { flex: 1; min-width: 150px; }
        .btn-submit { width: 100%; padding: 12px; background: #4361ee; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; }
        .btn-submit:hover { background: #3a56d4; }
        
        .toggle-container { display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid #e9ecef; padding-bottom: 10px; }
        .toggle-btn { padding: 8px 20px; border: none; background: #f8f9fa; cursor: pointer; border-radius: 8px; font-size: 14px; }
        .toggle-btn.active { background: #4361ee; color: white; }
        .toggle-content { display: none; }
        .toggle-content.active-content { display: block; }
        
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .preview-table { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 15px; }
        .preview-table th, .preview-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .preview-table th { background: #f0f7ff; }
        .preview-container { max-height: 400px; overflow-y: auto; margin-top: 20px; }
        
        .btn-download { background: #28a745; color: white; padding: 8px 16px; font-size: 12px; margin-left: 10px; }
        
        .rightpanel-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            display: none;
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
        .action-buttons { display: flex; flex-wrap: wrap; gap: 5px; }
        .date-range { font-size: 12px; color: #666; }
        .single-date { font-size: 12px; color: #28a745; }
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
            flex-wrap: wrap;
            padding: 15px 0;
        }
        .pagination button {
            padding: 8px 14px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            border-radius: 6px;
            font-size: 13px;
        }
        .pagination button:hover:not(:disabled) {
            background: #4361ee;
            color: white;
        }
        .pagination button.active {
            background: #4361ee;
            color: white;
        }
        .pagination button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .page-info {
            padding: 8px 14px;
            background: #f8f9fa;
            border-radius: 6px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>🏫 Tutorix</h2>
                <p>School Management System</p>
            </div>
            <div class="nav-menu">
                <a href="school-management.php" class="nav-item">
                    <span class="icon">🏫</span>
                    <span>School Management</span>
                </a>
                <a href="holidays.php" class="nav-item active">
                    <span class="icon">📅</span>
                    <span>School Holidays</span>
                </a>
            </div>
        </div>
        
        <div class="main-content">
            <div class="container">
                <div class="header">
                    <h1>📅 School Holidays Management</h1>
                    <button class="btn btn-primary" id="createHolidayBtn">+ Create Holiday</button>
                </div>
                
                <div class="filter-section">
                    <h3>🔍 Filter Holidays</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>School</label>
                            <select id="filterSchoolId">
                                <option value="">All Schools</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Academic Year</label>
                            <select id="filterAcademicYear">
                                <option value="">All Years</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Class</label>
                            <select id="filterClassId">
                                <option value="">All Classes</option>
                                <option value="1">Class 1</option><option value="2">Class 2</option>
                                <option value="3">Class 3</option><option value="4">Class 4</option>
                                <option value="5">Class 5</option><option value="6">Class 6</option>
                                <option value="7">Class 7</option><option value="8">Class 8</option>
                                <option value="9">Class 9</option><option value="10">Class 10</option>
                                <option value="11">Class 11</option><option value="12">Class 12</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button id="applyFilterBtn" class="btn btn-primary">Apply Filter</button>
                            <button id="resetFilterBtn" class="btn btn-secondary" style="margin-left:10px;">Reset</button>
                        </div>
                    </div>
                </div>
                
                <div id="loadingDiv" class="loading">
                    <div class="spinner"></div>
                    <p>Loading holidays...</p>
                </div>
                
                <div id="tableContainer" style="display: none;">
                    <div style="overflow-x: auto;">
                        <table style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th><th>School</th><th>Academic Year</th><th>Class</th>
                                    <th>Holiday Name</th><th>Date</th><th>Type</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="holidaysTableBody"></tbody>
                        </table>
                    </div>
                    <div id="pagination"></div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/rightpanel.js"></script>
    <script>
        const API_URL = '../api/';
        let allHolidays = [];
        let currentPageNum = 1;
        const rowsPerPage = 10;
        
        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }
        
        function openRightPanel(title, html, onClose) {
            RightPanel.open({ title: title, icon: '📅', width: '600px', html: html, onClose: onClose });
        }
        
        function closeRightPanel() {
            RightPanel.closeTop();
        }
        
        async function loadSchools() {
            try {
                const res = await fetch(API_URL + 'school_list.php');
                const data = await res.json();
                if (data.success) {
                    const select = document.getElementById('filterSchoolId');
                    data.data.forEach(school => {
                        let opt = document.createElement('option');
                        opt.value = school.school_id;
                        opt.textContent = school.school_name + ' (' + school.school_code + ')';
                        select.appendChild(opt);
                    });
                }
            } catch(e) { console.log(e); }
        }
        
        function getAcademicYears() {
            let currentYear = new Date().getFullYear();
            let years = '<option value="">All Years</option>';
            for (let i = currentYear; i <= currentYear + 5; i++) {
                years += '<option value="' + i + '">' + i + '</option>';
            }
            document.getElementById('filterAcademicYear').innerHTML = years;
        }
        
        async function loadHolidays() {
            let schoolId = document.getElementById('filterSchoolId').value;
            let academicYear = document.getElementById('filterAcademicYear').value;
            let classId = document.getElementById('filterClassId').value;
            
            let url = API_URL + 'holiday_list.php?';
            if (schoolId) url += 'school_id=' + schoolId + '&';
            if (academicYear) url += 'academic_year=' + academicYear + '&';
            if (classId) url += 'class_id=' + classId + '&';
            url += '_=' + Date.now();
            
            try {
                let res = await fetch(url);
                let data = await res.json();
                
                if (data.success && data.data) {
                    // Sort by date (newest first)
                    allHolidays = data.data.sort(function(a, b) {
                        return new Date(b.holiday_date) - new Date(a.holiday_date);
                    });
                    currentPageNum = 1;
                    renderTable();
                } else {
                    allHolidays = [];
                    document.getElementById('holidaysTableBody').innerHTML = '<tr><td colspan="8" style="text-align:center;">No holidays found</td></tr>';
                    document.getElementById('pagination').innerHTML = '';
                }
                document.getElementById('loadingDiv').style.display = 'none';
                document.getElementById('tableContainer').style.display = 'block';
            } catch(e) {
                console.log(e);
                toastr.error('Error loading holidays');
            }
        }
        
        function renderTable() {
            let totalPages = Math.ceil(allHolidays.length / rowsPerPage);
            let start = (currentPageNum - 1) * rowsPerPage;
            let end = start + rowsPerPage;
            let pageData = allHolidays.slice(start, end);
            
            let html = '';
            for (let holiday of pageData) {
                let dateDisplay = '';
                if (holiday.holiday_end_date && holiday.holiday_end_date !== '0000-00-00') {
                    dateDisplay = '📅 ' + holiday.holiday_date + ' to ' + holiday.holiday_end_date;
                } else {
                    dateDisplay = '📌 ' + holiday.holiday_date;
                }
                
                let typeBadge = '<span class="badge-holiday">🎉 Holiday</span>';
                
                html += '<tr>';
                html += '<td>' + holiday.id + '</td>';
                html += '<td><strong>' + escapeHtml(holiday.school_name || 'School ' + holiday.school_id) + '</strong><br><small>' + escapeHtml(holiday.school_code || '') + '</small></td>';
                html += '<td>' + (holiday.academic_year || '-') + '</td>';
                html += '<td>' + (holiday.class_id ? 'Class ' + holiday.class_id : 'All Classes') + '</td>';
                html += '<td><strong>' + escapeHtml(holiday.holiday_name) + '</strong></td>';
                html += '<td>' + dateDisplay + '</td>';
                html += '<td>' + typeBadge + '</td>';
                html += '<td class="action-buttons">';
                html += '<button class="btn btn-secondary" onclick="viewHoliday(' + holiday.id + ')">👁️ View</button>';
                html += '<button class="btn btn-danger" onclick="deleteHoliday(' + holiday.id + ')">🗑️ Delete</button>';
                html += '</td></tr>';
            }
            
            document.getElementById('holidaysTableBody').innerHTML = html;
            
            // Render pagination
            if (totalPages > 1) {
                let paginationHtml = '<div class="pagination">';
                paginationHtml += '<button onclick="changePage(' + (currentPageNum - 1) + ')" ' + (currentPageNum === 1 ? 'disabled' : '') + '>‹ Previous</button>';
                
                for (let i = 1; i <= totalPages; i++) {
                    if (i === 1 || i === totalPages || (i >= currentPageNum - 2 && i <= currentPageNum + 2)) {
                        paginationHtml += '<button onclick="changePage(' + i + ')" class="' + (i === currentPageNum ? 'active' : '') + '">' + i + '</button>';
                    } else if (i === currentPageNum - 3 || i === currentPageNum + 3) {
                        paginationHtml += '<button disabled>...</button>';
                    }
                }
                
                paginationHtml += '<button onclick="changePage(' + (currentPageNum + 1) + ')" ' + (currentPageNum === totalPages ? 'disabled' : '') + '>Next ›</button>';
                paginationHtml += '<span class="page-info">Showing ' + (start + 1) + ' - ' + Math.min(end, allHolidays.length) + ' of ' + allHolidays.length + '</span>';
                paginationHtml += '</div>';
                document.getElementById('pagination').innerHTML = paginationHtml;
            } else {
                document.getElementById('pagination').innerHTML = '';
            }
        }
        
        function changePage(page) {
            let totalPages = Math.ceil(allHolidays.length / rowsPerPage);
            if (page < 1 || page > totalPages) return;
            currentPageNum = page;
            renderTable();
        }
        
        window.viewHoliday = async function(id) {
            let holiday = allHolidays.find(h => h.id == id);
            if (!holiday) {
                toastr.error('Holiday not found');
                return;
            }
            
            let detailsHtml = `
                <div style="background:#f8f9fa; padding:20px; border-radius:8px;">
                    <h3 style="margin-bottom:15px; color:#4361ee;">📋 Holiday Details</h3>
                    <div style="line-height:2;">
                        <p><strong>🏫 School:</strong> ${escapeHtml(holiday.school_name)} (${escapeHtml(holiday.school_code)})</p>
                        <p><strong>📅 Academic Year:</strong> ${escapeHtml(holiday.academic_year)}</p>
                        <p><strong>📚 Class:</strong> ${holiday.class_id ? 'Class ' + holiday.class_id : 'All Classes'}</p>
                        <p><strong>🎉 Holiday Name:</strong> ${escapeHtml(holiday.holiday_name)}</p>
                        <p><strong>📌 Type:</strong> ${holiday.holiday_type === 'H' ? 'Holiday' : 'Other'}</p>
                        <p><strong>📅 Start Date:</strong> ${holiday.holiday_date}</p>
                        <p><strong>📅 End Date:</strong> ${holiday.holiday_end_date && holiday.holiday_end_date !== '0000-00-00' ? holiday.holiday_end_date : 'Single Day'}</p>
                    </div>
                </div>
                <div style="margin-top:20px;">
                    <button onclick="closeRightPanel()" class="btn-submit" style="background:#6c757d;">Close</button>
                </div>
            `;
            openRightPanel('Holiday Details', detailsHtml, null);
        };
        
        window.deleteHoliday = async function(id) {
            let result = await Swal.fire({
                title: 'Delete Holiday?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete!',
                cancelButtonText: 'Cancel'
            });
            
            if (result.isConfirmed) {
                Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                
                let res = await fetch(API_URL + 'holiday_delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                });
                let data = await res.json();
                
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message });
                    loadHolidays();
                } else {
                    Swal.fire({ icon: 'error', title: 'Failed', text: data.message });
                }
            }
        };
        
        window.openCreateHoliday = async function() {
            let schoolsHtml = '<option value="">Select School</option>';
            try {
                let res = await fetch(API_URL + 'school_list.php');
                let data = await res.json();
                if (data.success) {
                    data.data.forEach(school => {
                        schoolsHtml += `<option value="${school.school_id}">${escapeHtml(school.school_name)} (${school.school_code})</option>`;
                    });
                }
            } catch(e) {}
            
            let currentYear = new Date().getFullYear();
            let yearOptions = '';
            for (let i = currentYear; i <= currentYear + 5; i++) {
                yearOptions += `<option value="${i}">${i}</option>`;
            }
            
            let formHtml = `
                <div class="toggle-container">
                    <button class="toggle-btn active" onclick="showToggle('createForm')">➕ Create Holiday</button>
                    <button class="toggle-btn" onclick="showToggle('bulkUpload')">📁 Bulk Upload CSV</button>
                </div>
                
                <div id="createForm" class="toggle-content active-content">
                    <form id="createHolidayForm">
                        <div class="form-group">
                            <label>School *</label>
                            <select id="newSchoolId" required>${schoolsHtml}</select>
                        </div>
                        <div class="form-group">
                            <label>Academic Year *</label>
                            <select id="newAcademicYear" required>${yearOptions}</select>
                        </div>
                        <div class="form-group">
                            <label>Board *</label>
                            <select id="newBoardId" required>
                                <option value="C">CBSE</option>
                                <option value="I">ICSE</option>
                                <option value="W">WBBSE</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Class (Optional)</label>
                                <select id="newClassId">
                                    <option value="">All Classes</option>
                                    <option value="1">Class 1</option><option value="2">Class 2</option>
                                    <option value="3">Class 3</option><option value="4">Class 4</option>
                                    <option value="5">Class 5</option><option value="6">Class 6</option>
                                    <option value="7">Class 7</option><option value="8">Class 8</option>
                                    <option value="9">Class 9</option><option value="10">Class 10</option>
                                    <option value="11">Class 11</option><option value="12">Class 12</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Holiday Type *</label>
                                <select id="newHolidayType" required>
                                    <option value="H">Holiday</option>
                                    <option value="W">Weekend</option>
                                    <option value="O">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Holiday Name *</label>
                            <input type="text" id="newHolidayName" placeholder="Enter holiday name" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Start Date *</label>
                                <input type="date" id="newStartDate" required>
                            </div>
                            <div class="form-group">
                                <label>End Date (Optional)</label>
                                <input type="date" id="newEndDate">
                            </div>
                        </div>
                        <button type="submit" class="btn-submit">✅ Create Holiday</button>
                    </form>
                </div>
                
                <div id="bulkUpload" class="toggle-content">
                    <div style="margin-bottom:15px;">
                        <button type="button" class="btn btn-download" onclick="downloadSampleCSV()">📥 Download Sample CSV</button>
                    </div>
                    <form id="csvUploadForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>CSV File</label>
                            <input type="file" name="csv_file" accept=".csv" id="csvFile">
                        </div>
                        <button type="submit" class="btn-submit">📤 Upload CSV</button>
                    </form>
                </div>
            `;
            
            openRightPanel('Create Holiday', formHtml, () => loadHolidays());
            
            setTimeout(() => {
                let today = new Date().toISOString().split('T')[0];
                let startDate = document.getElementById('newStartDate');
                if (startDate) startDate.value = today;
                
                let createForm = document.getElementById('createHolidayForm');
                if (createForm) {
                    createForm.onsubmit = async (e) => {
                        e.preventDefault();
                        
                        let schoolId = document.getElementById('newSchoolId').value;
                        let holidayName = document.getElementById('newHolidayName').value;
                        
                        if (!schoolId || !holidayName) {
                            toastr.error('Please fill required fields');
                            return;
                        }
                        
                        let confirm = await Swal.fire({
                            title: 'Confirm Creation',
                            text: 'Create holiday "' + holidayName + '"?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, create!',
                            cancelButtonText: 'Cancel'
                        });
                        
                        if (!confirm.isConfirmed) {
                            Swal.fire('Cancelled', '', 'info');
                            return;
                        }
                        
                        Swal.fire({ title: 'Creating...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                        
                        let formData = {
                            school_id: schoolId,
                            academic_year: document.getElementById('newAcademicYear').value,
                            board_id: document.getElementById('newBoardId').value,
                            class_id: document.getElementById('newClassId').value || null,
                            holiday_name: holidayName,
                            holiday_date: document.getElementById('newStartDate').value,
                            holiday_end_date: document.getElementById('newEndDate').value || null,
                            holiday_type: document.getElementById('newHolidayType').value
                        };
                        
                        let res = await fetch(API_URL + 'holiday_add.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(formData)
                        });
                        let result = await res.json();
                        
                        if (result.success) {
                            Swal.fire({ icon: 'success', title: 'Created!', text: result.message });
                            closeRightPanel();
                            loadHolidays();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Failed', text: result.message });
                        }
                    };
                }
                
                let csvForm = document.getElementById('csvUploadForm');
                if (csvForm) {
                    csvForm.onsubmit = async (e) => {
                        e.preventDefault();
                        let fileInput = document.getElementById('csvFile');
                        if (!fileInput.files || !fileInput.files[0]) {
                            toastr.error('Please select a CSV file');
                            return;
                        }
                        
                        let confirm = await Swal.fire({
                            title: 'Confirm Upload',
                            text: 'Upload this CSV file?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, upload!',
                            cancelButtonText: 'Cancel'
                        });
                        
                        if (!confirm.isConfirmed) {
                            Swal.fire('Cancelled', '', 'info');
                            return;
                        }
                        
                        Swal.fire({ title: 'Uploading...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                        
                        let formData = new FormData();
                        formData.append('csv_file', fileInput.files[0]);
                        
                        let res = await fetch(API_URL + 'holiday_csv_upload.php', { method: 'POST', body: formData });
                        let result = await res.json();
                        
                        if (result.success) {
                            Swal.fire({ icon: 'success', title: 'Upload Complete!', html: '✅ ' + result.success_count + ' added<br>❌ ' + result.failed_count + ' failed' });
                            closeRightPanel();
                            loadHolidays();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Failed', text: result.message });
                        }
                    };
                }
            }, 100);
        };
        
        window.downloadSampleCSV = function() {
            let sample = "school_id,academic_year,class_id,holiday_name,holiday_type,holiday_date,holiday_end_date\n";
            sample += "9,2025,6,Summer Vacation,H,2025-06-01,2025-06-30\n";
            sample += "9,2025,,Republic Day,H,2025-01-26,\n";
            sample += "8,2025,,Puja Holidays,H,2025-10-01,2025-10-15";
            
            let blob = new Blob([sample], { type: 'text/csv' });
            let url = URL.createObjectURL(blob);
            let a = document.createElement('a');
            a.href = url;
            a.download = 'sample_holidays.csv';
            a.click();
            URL.revokeObjectURL(url);
            toastr.success('Sample CSV downloaded');
        };
        
        window.showToggle = function(id) {
            document.querySelectorAll('.toggle-content').forEach(c => c.classList.remove('active-content'));
            document.querySelectorAll('.toggle-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(id).classList.add('active-content');
            if (event && event.target) event.target.classList.add('active');
        };
        
        document.getElementById('createHolidayBtn').onclick = () => window.openCreateHoliday();
        document.getElementById('applyFilterBtn').onclick = () => { currentPageNum = 1; loadHolidays(); };
        document.getElementById('resetFilterBtn').onclick = () => {
            document.getElementById('filterSchoolId').value = '';
            document.getElementById('filterAcademicYear').value = '';
            document.getElementById('filterClassId').value = '';
            currentPageNum = 1;
            loadHolidays();
            toastr.info('Filters cleared');
        };
        
        getAcademicYears();
        loadSchools();
        loadHolidays();
    </script>
</body>
</html>