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
        
        /* Sidebar Styles */
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
        .btn-warning { background: #ffc107; color: #333; }
        .btn-warning:hover { background: #e0a800; }
        .btn-success { background: #28a745; color: white; }
        .btn-success:hover { background: #218838; }
        
        table { width: 100%; background: white; border-collapse: collapse; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e9ecef; }
        th { background: #f8f9fa; font-weight: 600; color: #495057; }
        tr:hover { background: #f8f9fa; }
        
        .badge-holiday { background: #d4edda; color: #155724; padding: 4px 12px; border-radius: 20px; font-size: 11px; display: inline-block; }
        .badge-weekend { background: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 20px; font-size: 11px; display: inline-block; }
        .badge-other { background: #d1ecf1; color: #0c5460; padding: 4px 12px; border-radius: 20px; font-size: 11px; display: inline-block; }
        
        .loading { text-align: center; padding: 50px; }
        .spinner { border: 3px solid #f3f3f3; border-top: 3px solid #4361ee; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 15px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 13px; }
        .form-group label .required { color: #dc3545; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; transition: all 0.2s; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #4361ee; box-shadow: 0 0 0 3px rgba(67,97,238,0.1); }
        .form-group input.error, .form-group select.error { border: 2px solid #dc3545; background: #fff0f0; }
        .error-message { color: #dc3545; font-size: 12px; margin-top: 5px; display: none; }
        .form-row { display: flex; gap: 15px; flex-wrap: wrap; }
        .form-row .form-group { flex: 1; min-width: 150px; }
        .btn-submit { width: 100%; padding: 12px; background: #4361ee; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 500; transition: all 0.2s; }
        .btn-submit:hover { background: #3a56d4; }
        
        .toggle-container { display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid #e9ecef; padding-bottom: 10px; flex-wrap: wrap; }
        .toggle-btn { padding: 8px 20px; border: none; background: #f8f9fa; cursor: pointer; border-radius: 8px; font-size: 14px; transition: all 0.3s; }
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
        
        .csv-example { background: #f8f9fa; padding: 15px; border-radius: 8px; font-size: 12px; margin-bottom: 15px; }
        .csv-example code { background: #e9ecef; display: block; padding: 10px; margin-top: 8px; white-space: pre-wrap; }
        
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
        .action-buttons { display: flex; flex-wrap: wrap; gap: 5px; }
        .date-range { font-size: 12px; color: #666; }
        .single-date { font-size: 12px; color: #28a745; }
        
        /* Pagination Styles */
        .pagination-container {
            margin-top: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .pagination-btn {
            padding: 8px 14px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
            min-width: 40px;
            text-align: center;
        }
        .pagination-btn:hover:not(:disabled):not(.active) {
            background: #4361ee;
            color: white;
            border-color: #4361ee;
        }
        .pagination-btn.active {
            background: #4361ee;
            color: white;
            border-color: #4361ee;
            cursor: default;
        }
        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .pagination-ellipsis {
            padding: 8px 5px;
            color: #666;
        }
        .per-page-select {
            margin-left: 15px;
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid #ddd;
            cursor: pointer;
        }
        .pagination-info {
            text-align: center;
            margin-top: 15px;
            color: #666;
            font-size: 13px;
        }
        
        /* Ensure SweetAlert appears above panel */
        .swal2-container {
            z-index: 20000 !important;
        }
        
        /* Error highlight for form fields */
        .form-group input.error-field, 
        .form-group select.error-field {
            border: 2px solid #dc3545 !important;
            background: #fff0f0 !important;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Tutorix</h2>
            </div>
            <div class="nav-menu">
                <a href="school-management.php" class="nav-item">
                    <span class="icon">🏫</span>
                    <span>School Management</span>
                </a>
                <a href="school-holidays.php" class="nav-item active">
                    <span class="icon">📅</span>
                    <span>School Holidays</span>
                </a>
                <a href="school_fee_structure.php" class="nav-item">
                    <span class="icon">💰</span>
                    <span>School Fee Structure</span>
                </a>
                <a href="student_fee_details.php" class="nav-item ">
                    <span class="icon">👨‍🎓</span>
                    <span>Student Fee Details</span>
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="container">
                <div class="header">
                    <h1>📅 School Holidays Management</h1>
                    <button class="btn btn-primary" id="createHolidayBtn">+ Create Holiday</button>
                </div>
                
                <!-- Filter Section -->
                <div class="filter-section">
                    <h3 style="margin-bottom: 15px;">🔍 Filter Holidays</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>School Name / Code</label>
                            <select id="filter_school_id" class="filter-select">
                                <option value="">All Schools</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Academic Year</label>
                            <select id="filter_academic_year" class="filter-select">
                                <option value="">All Years</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Board</label>
                            <select id="filter_board_id" class="filter-select">
                                <option value="">All Boards</option>
                                <option value="C">CBSE</option>
                                <option value="I">ICSE</option>
                                <option value="W">WBBSE</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Class</label>
                            <select id="filter_class_id" class="filter-select">
                                <option value="">All Classes</option>
                                <option value="1">Class 1</option>
                                <option value="2">Class 2</option>
                                <option value="3">Class 3</option>
                                <option value="4">Class 4</option>
                                <option value="5">Class 5</option>
                                <option value="6">Class 6</option>
                                <option value="7">Class 7</option>
                                <option value="8">Class 8</option>
                                <option value="9">Class 9</option>
                                <option value="10">Class 10</option>
                                <option value="11">Class 11</option>
                                <option value="12">Class 12</option>
                            </select>
                        </div>
                        <div class="form-group" style="display: flex; align-items: flex-end;">
                            <button id="applyFilterBtn" class="btn btn-primary" style="padding: 10px 25px;">Apply Filter</button>
                            <button id="resetFilterBtn" class="btn btn-primary" style="padding: 10px 25px; margin-left: 10px; background: #6c757d;">Reset</button>
                        </div>
                    </div>
                </div>
                
                <!-- Loading Spinner -->
                <div id="loadingDiv" class="loading">
                    <div class="spinner"></div>
                    <p>Loading holidays...</p>
                </div>
                
                <!-- Holidays Table -->
                <div id="tableContainer" style="display: none;">
                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>School</th>
                                    <th>Academic Year</th>
                                    <th>Board</th>
                                    <th>Class</th>
                                    <th>Holiday Name</th>
                                    <th>Date / Range</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="holidaysList"></tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-info" id="paginationInfo"></div>
                    <div class="pagination-container" id="paginationContainer"></div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/rightpanel.js"></script>
    <script>
        toastr.options = { 
            closeButton: true, 
            progressBar: true, 
            positionClass: "toast-top-right", 
            timeOut: "4000" 
        };
        
        const API_BASE = 'http://localhost/school_management/api/';
        
        // Global pagination state
        let currentPage = 1;
        let totalPages = 1;
        let perPage = 10;
        let totalRows = 0;
        
        function escapeHtml(str) { 
            if (!str) return ''; 
            return String(str).replace(/[&<>]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[m])); 
        }
        
        function openPanel(title, contentHtml, onClose, width = '600px', icon = '📄') {
            return RightPanel.open({ title, icon, width, html: contentHtml, onClose });
        }
        
        function closePanel() { RightPanel.closeTop(); }
        
        // Generate academic year options (current year + next 5 years)
        function generateAcademicYearOptions() {
            const currentYear = new Date().getFullYear();
            let options = '<option value="">All Years</option>';
            for (let i = currentYear; i <= currentYear + 5; i++) {
                options += `<option value="${i}">${i}</option>`;
            }
            return options;
        }
        
        // Load schools for filter dropdown
        async function loadSchools() {
            try {
                const res = await fetch(API_BASE + 'school_list.php');
                const data = await res.json();
                if (data.success) {
                    const select = document.getElementById('filter_school_id');
                    if (select) {
                        select.innerHTML = '<option value="">All Schools</option>';
                        data.data.forEach(school => {
                            const option = document.createElement('option');
                            option.value = school.school_id;
                            option.textContent = `${school.school_name} (${school.school_code})`;
                            select.appendChild(option);
                        });
                    }
                }
            } catch(e) { console.error('Error loading schools:', e); }
        }
        
        // Load holidays list with pagination
        async function loadHolidays(page = 1) {
            currentPage = page;
            const schoolId = document.getElementById('filter_school_id')?.value || '';
            const academicYear = document.getElementById('filter_academic_year')?.value || '';
            const boardId = document.getElementById('filter_board_id')?.value || '';
            const classId = document.getElementById('filter_class_id')?.value || '';
            
            let url = `${API_BASE}holiday_list.php?page=${page}&limit=${perPage}`;
            if (schoolId) url += `&school_id=${schoolId}`;
            if (academicYear) url += `&academic_year=${encodeURIComponent(academicYear)}`;
            if (boardId) url += `&board_id=${boardId}`;
            if (classId) url += `&class_id=${classId}`;
            
            try {
                const res = await fetch(url);
                const data = await res.json();
                console.log('Pagination data:', {
                    total_pages: data.total_pages,
                    total_rows: data.total_rows,
                    current_page: data.current_page,
                    data_length: data.data.length
                });
                const tbody = document.getElementById('holidaysList');
                const loadingDiv = document.getElementById('loadingDiv');
                const tableContainer = document.getElementById('tableContainer');
                
                if (data.success && data.data && data.data.length > 0) {
                    totalPages = data.total_pages;
                    totalRows = data.total_rows;
                    
                    tbody.innerHTML = data.data.map(holiday => {
                        let dateDisplay = '';
                        if (holiday.holiday_end_date && holiday.holiday_end_date !== '0000-00-00' && holiday.holiday_end_date !== '') {
                            dateDisplay = `<span class="date-range"> ${holiday.holiday_date} → ${holiday.holiday_end_date}</span>`;
                        } else {
                            dateDisplay = `<span class="single-date"> ${holiday.holiday_date}</span>`;
                        }
                        
                        let typeBadge = '';
                        if (holiday.holiday_type === 'H') typeBadge = '<span class="badge-holiday"> Holiday</span>';
                        else if (holiday.holiday_type === 'W') typeBadge = '<span class="badge-weekend"> Weekend/Off</span>';
                        else typeBadge = '<span class="badge-other"> Other</span>';
                        
                        return `
                            <tr>
                                <td>${holiday.id}</td>
                                <td><strong>${escapeHtml(holiday.school_name || 'School ID: ' + holiday.school_id)}</strong><br><small style="color:#666;">${escapeHtml(holiday.school_code || '')}</small></td>
                                <td>${escapeHtml(holiday.academic_year || '-')}</td>
                                <td>${escapeHtml(holiday.board_id)}</td>
                                <td>${holiday.class_id ? 'Class ' + holiday.class_id : 'All Classes'}</td>
                                <td><strong>${escapeHtml(holiday.holiday_name)}</strong></td>
                                <td>${dateDisplay}</td>
                                <td>${typeBadge}</td>
                                <td class="action-buttons">
                                    <button class="btn btn-secondary" onclick="editHoliday(${holiday.id})" style="background:#6c757d; color:white;">✏️ Edit</button>
                                    <button class="btn btn-secondary" onclick="deleteHoliday(${holiday.id})" style="background:#dc3545; color:white;">🗑️ Delete</button>
                                </td>
                            </tr>
                        `;
                    }).join('');
                    
                    const start = (data.current_page - 1) * perPage + 1;
                    const end = Math.min(data.current_page * perPage, totalRows);
                    const paginationInfo = document.getElementById('paginationInfo');
                    if (paginationInfo) {
                        paginationInfo.innerHTML = `Showing ${start} to ${end} of ${totalRows} holidays`;
                    }
                    
                    renderPagination(data.current_page, data.total_pages);
                } else {
                    if (tbody) tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;">No holidays found. Click "Create Holiday" to add one.</td></tr>';
                    totalPages = 0;
                    totalRows = 0;
                    const paginationInfo = document.getElementById('paginationInfo');
                    if (paginationInfo) paginationInfo.innerHTML = '';
                    renderPagination(1, 0);
                }
                if (loadingDiv) loadingDiv.style.display = 'none';
                if (tableContainer) tableContainer.style.display = 'block';
            } catch(e) {
                console.error('Error loading holidays:', e);
                toastr.error('Error loading holidays');
            }
        }
        
        // Render pagination buttons with page numbers (1, 2, 3...)
        function renderPagination(currentPageNum, totalPagesNum) {
            const container = document.getElementById('paginationContainer');
            if (!container) return;
            
            if (!totalPagesNum || totalPagesNum <= 1) {
                container.innerHTML = '';
                return;
            }
            
            let html = '';
            
            // Previous button
            html += `<button class="pagination-btn" onclick="changePage(${currentPageNum - 1})" ${currentPageNum === 1 ? 'disabled' : ''}>← Prev</button>`;
            
            // Page numbers - show all pages
            for (let i = 1; i <= totalPagesNum; i++) {
                html += `<button class="pagination-btn ${i === currentPageNum ? 'active' : ''}" onclick="changePage(${i})">${i}</button>`;
            }
            
            // Next button
            html += `<button class="pagination-btn" onclick="changePage(${currentPageNum + 1})" ${currentPageNum === totalPagesNum ? 'disabled' : ''}>Next →</button>`;
            
            // Per page selector
            html += `<select class="per-page-select" onchange="changePerPage(this.value)">
                        <option value="10" ${perPage === 10 ? 'selected' : ''}>10 per page</option>
                        <option value="25" ${perPage === 25 ? 'selected' : ''}>25 per page</option>
                        <option value="50" ${perPage === 50 ? 'selected' : ''}>50 per page</option>
                    </select>`;
            
            container.innerHTML = html;
        }
        
        function changePage(page) {
            if (page >= 1 && page <= totalPages) {
                loadHolidays(page);
            }
        }
        
        function changePerPage(value) {
            perPage = parseInt(value);
            loadHolidays(1);
        }
        
        // Function to validate create holiday form
        function validateCreateHolidayForm() {
            let isValid = true;
            const errors = [];
            
            // Get form elements
            const schoolId = document.getElementById('holiday_school_id');
            const academicYear = document.getElementById('holiday_academic_year');
            const boardId = document.getElementById('holiday_board_id');
            const holidayName = document.getElementById('holiday_name');
            const startDate = document.getElementById('holiday_start_date');
            const holidayType = document.getElementById('holiday_type');
            
            // Remove existing error classes
            document.querySelectorAll('.error-field').forEach(el => {
                el.classList.remove('error-field');
            });
            
            // Validate School
            if (!schoolId || !schoolId.value) {
                isValid = false;
                errors.push('School is required');
                if (schoolId) schoolId.classList.add('error-field');
                toastr.error('Please select a School');
            }
            
            // Validate Academic Year
            if (!academicYear || !academicYear.value) {
                isValid = false;
                errors.push('Academic Year is required');
                if (academicYear) academicYear.classList.add('error-field');
                toastr.error('Please select an Academic Year');
            }
            
            // Validate Board
            if (!boardId || !boardId.value) {
                isValid = false;
                errors.push('Board is required');
                if (boardId) boardId.classList.add('error-field');
                toastr.error('Please select a Board');
            }
            
            // Validate Holiday Name
            if (!holidayName || !holidayName.value.trim()) {
                isValid = false;
                errors.push('Holiday Name is required');
                if (holidayName) holidayName.classList.add('error-field');
                toastr.error('Please enter a Holiday Name');
            }
            
            // Validate Start Date
            if (!startDate || !startDate.value) {
                isValid = false;
                errors.push('Start Date is required');
                if (startDate) startDate.classList.add('error-field');
                toastr.error('Please select a Start Date');
            }
            
            // Validate Holiday Type
            if (!holidayType || !holidayType.value) {
                isValid = false;
                errors.push('Holiday Type is required');
                if (holidayType) holidayType.classList.add('error-field');
                toastr.error('Please select a Holiday Type');
            }
            
            return isValid;
        }
        
        // Create Holiday Form
        window.openCreateHoliday = async function() {
            // Load schools for dropdown
            let schoolsHtml = '<option value="">Select School</option>';
            try {
                const res = await fetch(API_BASE + 'school_list.php');
                const data = await res.json();
                if (data.success) {
                    data.data.forEach(school => {
                        schoolsHtml += `<option value="${school.school_id}" data-board="${school.board_id}">${escapeHtml(school.school_name)} (${school.school_code}) - Board: ${school.board_id}</option>`;
                    });
                }
            } catch(e) {}
            
            const currentYear = new Date().getFullYear();
            let academicYearOptions = '';
            for (let i = currentYear; i <= currentYear + 5; i++) {
                academicYearOptions += `<option value="${i}">${i}</option>`;
            }
            
            const createFormHtml = `
                <div class="toggle-container">
                    <button class="toggle-btn active" onclick="showHolidayToggle('createForm')">➕ Create Holiday</button>
                    <button class="toggle-btn" onclick="showHolidayToggle('bulkUpload')">📁 Bulk Upload CSV</button>
                </div>
                
                <div id="createForm" class="toggle-content active-content">
                    <form id="createHolidayForm" novalidate>
                        <div class="form-group">
                            <label>School <span class="required">*</span></label>
                            <select id="holiday_school_id" required>${schoolsHtml}</select>
                            <small style="color:#666; display:block; margin-top:5px;">School's board will be auto-detected</small>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Academic Year <span class="required">*</span></label>
                                <select id="holiday_academic_year" required>
                                    <option value="">Select Year</option>
                                    ${academicYearOptions}
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Board <span class="required">*</span></label>
                                <select id="holiday_board_id" required>
                                    <option value="">Select Board</option>
                                    <option value="C">CBSE</option>
                                    <option value="I">ICSE</option>
                                    <option value="W">WBBSE</option>
                                </select>
                                <small style="color:#dc3545;" id="boardWarning"></small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Class (Optional - leave empty for all classes)</label>
                                <select id="holiday_class_id">
                                    <option value="">All Classes</option>
                                    ${[1,2,3,4,5,6,7,8,9,10,11,12].map(c => `<option value="${c}">Class ${c}</option>`).join('')}
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Holiday Type <span class="required">*</span></label>
                                <select id="holiday_type" required>
                                    <option value="">Select Type</option>
                                    <option value="H">Holiday</option>
                                    <option value="W">Weekend / Custom-off</option>
                                    <option value="O">Other Non-working</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Holiday Name <span class="required">*</span></label>
                            <input type="text" id="holiday_name" placeholder="e.g., Summer Vacation, Diwali Break, Republic Day" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Start Date <span class="required">*</span></label>
                                <input type="date" id="holiday_start_date" required>
                                <small>Single day or start of vacation</small>
                            </div>
                            <div class="form-group">
                                <label>End Date (Optional)</label>
                                <input type="date" id="holiday_end_date">
                                <small>Leave empty for single day holiday</small>
                            </div>
                        </div>
                        <button type="submit" class="btn-submit">✅ Create Holiday</button>
                    </form>
                </div>
                
                <div id="bulkUpload" class="toggle-content">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h4>Bulk CSV Upload</h4>
                        <button type="button" class="btn btn-download" onclick="downloadSampleCSV()">📥 Download Sample CSV</button>
                    </div>
                    
                    <form id="csvUploadForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>CSV File *</label>
                            <input type="file" name="csv_file" accept=".csv" id="csvFileInput">
                        </div>
                        <button type="button" class="btn btn-secondary" onclick="previewCSV()" style="margin-bottom:10px;">👁️ Preview CSV Data</button>
                        <div id="csvPreviewContainer" class="preview-container" style="display:none;"></div>
                        <button type="submit" class="btn-submit">📤 Upload CSV</button>
                    </form>
                </div>
            `;
            
            openPanel('Create Holiday', createFormHtml, () => loadHolidays(currentPage), '680px', '📅');
            
            setTimeout(() => {
                const today = new Date().toISOString().split('T')[0];
                const startDateInput = document.getElementById('holiday_start_date');
                if (startDateInput) startDateInput.value = today;
                
                // Board validation on school select
                const schoolSelect = document.getElementById('holiday_school_id');
                const boardSelect = document.getElementById('holiday_board_id');
                const boardWarning = document.getElementById('boardWarning');
                
                if (schoolSelect) {
                    schoolSelect.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        const schoolBoard = selectedOption.getAttribute('data-board');
                        if (schoolBoard && boardSelect) {
                            let mappedBoard = '';
                            if (schoolBoard === 'C' || schoolBoard === 'CBSE') mappedBoard = 'C';
                            else if (schoolBoard === 'I' || schoolBoard === 'ICSE') mappedBoard = 'I';
                            else if (schoolBoard === 'W' || schoolBoard === 'WBBSE') mappedBoard = 'W';
                            else mappedBoard = schoolBoard;
                            
                            if (mappedBoard && boardSelect.value && boardSelect.value !== mappedBoard) {
                                if (boardWarning) boardWarning.innerHTML = ` This school has board ${schoolBoard}. Please select matching board.`;
                            } else {
                                if (boardWarning) boardWarning.innerHTML = '';
                            }
                        }
                    });
                }
                
                const createForm = document.getElementById('createHolidayForm');
                if (createForm) {
                    createForm.onsubmit = async (e) => {
                        e.preventDefault();
                        
                        // Validate form before submission
                        if (!validateCreateHolidayForm()) {
                            return;
                        }
                        
                        const schoolId = document.getElementById('holiday_school_id')?.value;
                        const selectedOption = document.getElementById('holiday_school_id')?.options[document.getElementById('holiday_school_id').selectedIndex];
                        const schoolBoard = selectedOption?.getAttribute('data-board');
                        let mappedBoard = '';
                        if (schoolBoard === 'C' || schoolBoard === 'CBSE') mappedBoard = 'C';
                        else if (schoolBoard === 'I' || schoolBoard === 'ICSE') mappedBoard = 'I';
                        else if (schoolBoard === 'W' || schoolBoard === 'WBBSE') mappedBoard = 'W';
                        else mappedBoard = schoolBoard;
                        
                        const selectedBoard = document.getElementById('holiday_board_id')?.value;
                        
                        if (mappedBoard && selectedBoard !== mappedBoard) {
                            toastr.error(`Board mismatch! This school is associated with ${schoolBoard} board. Please change the board to match the school.`);
                            return;
                        }
                        
                        const formData = {
                            school_id: schoolId,
                            academic_year: document.getElementById('holiday_academic_year')?.value,
                            board_id: selectedBoard,
                            class_id: document.getElementById('holiday_class_id')?.value || null,
                            holiday_name: document.getElementById('holiday_name')?.value,
                            holiday_date: document.getElementById('holiday_start_date')?.value,
                            holiday_end_date: document.getElementById('holiday_end_date')?.value || null,
                            holiday_type: document.getElementById('holiday_type')?.value
                        };
                        
                        Swal.fire({ 
                            title: 'Creating...', 
                            allowOutsideClick: false, 
                            didOpen: () => Swal.showLoading()
                        });
                        
                        const res = await fetch(API_BASE + 'holiday_add.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(formData)
                        });
                        const result = await res.json();
                        
                        if (result.success) {
                            Swal.fire({ icon: 'success', title: 'Created!', text: result.message });
                            closePanel();
                            loadHolidays(1);
                        } else {
                            Swal.fire({ icon: 'error', title: 'Failed', text: result.message });
                        }
                    };
                }
            }, 100);
        };
        
        // Preview CSV - Remove double quotes from display
        window.previewCSV = function() {
            const fileInput = document.getElementById('csvFileInput');
            const previewContainer = document.getElementById('csvPreviewContainer');
            
            if (!fileInput.files || !fileInput.files[0]) {
                toastr.error('Please select a CSV file');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                let content = e.target.result;
                content = content.replace(/"/g, '');
                const rows = content.split(/\r?\n/);
                const headers = rows[0] ? rows[0].split(',').map(h => h.trim()) : [];
                
                let previewHtml = '<h4>📊 CSV Data Preview (First 10 rows)</h4>';
                previewHtml += '<table class="preview-table"><thead><tr>';
                headers.forEach(h => previewHtml += `<th>${escapeHtml(h)}</th>`);
                previewHtml += '</thead><tbody>';
                
                for (let i = 1; i < Math.min(rows.length, 11); i++) {
                    if (rows[i].trim()) {
                        const cols = rows[i].split(',').map(c => c.trim());
                        previewHtml += '<tr>';
                        for (let j = 0; j < headers.length; j++) {
                            previewHtml += `<td>${escapeHtml(cols[j] || '-')}</td>`;
                        }
                        previewHtml += '</tr>';
                    }
                }
                previewHtml += '</tbody></table>';
                previewHtml += `<p><strong>Total rows:</strong> ${rows.length - 1}</p>`;
                
                if (previewContainer) {
                    previewContainer.innerHTML = previewHtml;
                    previewContainer.style.display = 'block';
                }
            };
            reader.readAsText(fileInput.files[0]);
        };
        
        // Download Sample CSV (without double quotes)
        window.downloadSampleCSV = function() {
            const sampleData = [
                ['school_id', 'academic_year', 'board_id', 'class_id', 'holiday_date', 'holiday_end_date', 'holiday_name', 'holiday_type'],
                ['9', '2025', 'C', '6', '2025-06-01', '2025-06-30', 'Summer Vacation', 'H'],
                ['9', '2025', 'C', '', '2025-01-26', '', 'Republic Day', 'H'],
                ['9', '2025', 'C', '', '2025-08-15', '', 'Independence Day', 'H'],
                ['8', '2025', 'W', '', '2025-10-01', '2025-10-15', 'Puja Holidays', 'H']
            ];
            const csvContent = sampleData.map(row => row.join(',')).join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'sample_holidays.csv';
            a.click();
            URL.revokeObjectURL(url);
            toastr.success('Sample CSV downloaded (without quotes)');
        };
        
        // Edit Holiday
        window.editHoliday = async function(id) {
            try {
                const res = await fetch(API_BASE + `holiday_list.php?limit=1000`);
                const data = await res.json();
                const holiday = data.data.find(h => h.id == id);
                if (!holiday) { toastr.error('Holiday not found'); return; }
                
                // Load schools
                let schoolsHtml = '<option value="">Select School</option>';
                const schoolRes = await fetch(API_BASE + 'school_list.php');
                const schoolData = await schoolRes.json();
                if (schoolData.success) {
                    schoolData.data.forEach(school => {
                        schoolsHtml += `<option value="${school.school_id}" ${school.school_id == holiday.school_id ? 'selected' : ''} data-board="${school.board_id}">${escapeHtml(school.school_name)} (${school.school_code})</option>`;
                    });
                }
                
                const currentYear = new Date().getFullYear();
                let academicYearOptions = '';
                for (let i = currentYear; i <= currentYear + 5; i++) {
                    academicYearOptions += `<option value="${i}" ${holiday.academic_year == i ? 'selected' : ''}>${i}</option>`;
                }
                
                const editFormHtml = `
                    <form id="editHolidayForm" novalidate>
                        <input type="hidden" id="edit_id" value="${holiday.id}">
                        <div class="form-group">
                            <label>School <span class="required">*</span></label>
                            <select id="edit_school_id" disabled style="background:#e9ecef;">${schoolsHtml}</select>
                            <small>School cannot be changed</small>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Academic Year <span class="required">*</span></label>
                                <select id="edit_academic_year" required>
                                    ${academicYearOptions}
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Board <span class="required">*</span></label>
                                <select id="edit_board_id" required>
                                    <option value="C" ${holiday.board_id == 'C' ? 'selected' : ''}>CBSE</option>
                                    <option value="I" ${holiday.board_id == 'I' ? 'selected' : ''}>ICSE</option>
                                    <option value="W" ${holiday.board_id == 'W' ? 'selected' : ''}>WBBSE</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Class (Optional)</label>
                                <select id="edit_class_id">
                                    <option value="">All Classes</option>
                                    ${[1,2,3,4,5,6,7,8,9,10,11,12].map(c => `<option value="${c}" ${holiday.class_id == c ? 'selected' : ''}>Class ${c}</option>`).join('')}
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Holiday Type <span class="required">*</span></label>
                                <select id="edit_holiday_type" required>
                                    <option value="H" ${holiday.holiday_type == 'H' ? 'selected' : ''}>Holiday</option>
                                    <option value="W" ${holiday.holiday_type == 'W' ? 'selected' : ''}>Weekend / Custom-off</option>
                                    <option value="O" ${holiday.holiday_type == 'O' ? 'selected' : ''}>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Holiday Name <span class="required">*</span></label>
                            <input type="text" id="edit_holiday_name" value="${escapeHtml(holiday.holiday_name)}" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Start Date <span class="required">*</span></label>
                                <input type="date" id="edit_start_date" value="${holiday.holiday_date}" required>
                            </div>
                            <div class="form-group">
                                <label>End Date (Optional)</label>
                                <input type="date" id="edit_end_date" value="${holiday.holiday_end_date && holiday.holiday_end_date !== '0000-00-00' ? holiday.holiday_end_date : ''}">
                            </div>
                        </div>
                        <button type="submit" class="btn-submit">💾 Save Changes</button>
                    </form>
                `;
                
                openPanel('Edit Holiday', editFormHtml, () => loadHolidays(currentPage), '600px', '✏️');
                
                setTimeout(() => {
                    const editForm = document.getElementById('editHolidayForm');
                    if (editForm) {
                        editForm.onsubmit = async (e) => {
                            e.preventDefault();
                            
                            const updateData = {
                                id: parseInt(document.getElementById('edit_id')?.value),
                                academic_year: document.getElementById('edit_academic_year')?.value,
                                board_id: document.getElementById('edit_board_id')?.value,
                                class_id: document.getElementById('edit_class_id')?.value || null,
                                holiday_name: document.getElementById('edit_holiday_name')?.value,
                                holiday_date: document.getElementById('edit_start_date')?.value,
                                holiday_end_date: document.getElementById('edit_end_date')?.value || null,
                                holiday_type: document.getElementById('edit_holiday_type')?.value
                            };
                            
                            Swal.fire({ 
                                title: 'Updating...', 
                                allowOutsideClick: false, 
                                didOpen: () => Swal.showLoading()
                            });
                            
                            const res = await fetch(API_BASE + 'holiday_update.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify(updateData)
                            });
                            const result = await res.json();
                            
                            if (result.success) {
                                Swal.fire({ icon: 'success', title: 'Updated!', text: result.message });
                                closePanel();
                                loadHolidays(currentPage);
                            } else {
                                Swal.fire({ icon: 'error', title: 'Failed', text: result.message });
                            }
                        };
                    }
                }, 100);
            } catch(e) {
                console.error('Error:', e);
                toastr.error('Error loading holiday details');
            }
        };
        
        // Delete Holiday
        window.deleteHoliday = async function(id) {
            console.log("Delete clicked - ID received:", id);
            
            const holidayId = parseInt(id);
            
            if (isNaN(holidayId) || holidayId <= 0) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Invalid holiday ID: ' + id });
                return;
            }
            
            const result = await Swal.fire({
                title: 'Delete Holiday?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete!'
            });
            
            if (result.isConfirmed) {
                Swal.fire({ 
                    title: 'Deleting...', 
                    allowOutsideClick: false, 
                    didOpen: () => Swal.showLoading()
                });
                
                const requestData = { id: holidayId };
                
                try {
                    const res = await fetch(API_BASE + 'holiday_delete.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(requestData)
                    });
                    
                    const data = await res.json();
                    
                    if (data.success) {
                        Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message });
                        loadHolidays(currentPage);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Failed', text: data.message });
                    }
                } catch (error) {
                    console.error("Delete error:", error);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Network error: ' + error.message });
                }
            }
        };
        
        // Toggle content
        window.showHolidayToggle = function(activeId) {
            document.querySelectorAll('.toggle-content').forEach(c => c.classList.remove('active-content'));
            document.querySelectorAll('.toggle-btn').forEach(b => b.classList.remove('active'));
            const activeContent = document.getElementById(activeId);
            if (activeContent) activeContent.classList.add('active-content');
            if (event && event.target) event.target.classList.add('active');
        };
        
        // CSV Upload
        const csvForm = document.getElementById('csvUploadForm');
        if (csvForm) {
            csvForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const fileInput = document.getElementById('csvFileInput');
                if (!fileInput.files || !fileInput.files[0]) {
                    toastr.error('Please select a CSV file');
                    return;
                }
                
                Swal.fire({ 
                    title: 'Uploading...', 
                    allowOutsideClick: false, 
                    didOpen: () => Swal.showLoading()
                });
                
                const formData = new FormData();
                formData.append('csv_file', fileInput.files[0]);
                
                const res = await fetch(API_BASE + 'holiday_csv_upload.php', { method: 'POST', body: formData });
                const result = await res.json();
                
                if (result.success) {
                    let message = `✅ ${result.success_count} added<br>❌ ${result.failed_count} failed`;
                    if (result.errors && result.errors.length > 0) {
                        message += `<br><br><strong>Errors:</strong><br>${result.errors.slice(0, 5).join('<br>')}`;
                        if (result.errors.length > 5) message += `<br>... and ${result.errors.length - 5} more`;
                    }
                    Swal.fire({ icon: 'success', title: 'Upload Complete!', html: message });
                    closePanel();
                    loadHolidays(1);
                } else {
                    Swal.fire({ icon: 'error', title: 'Failed', text: result.message });
                }
            });
        }
        
        // Set academic year options in filter
        const filterAcademicYear = document.getElementById('filter_academic_year');
        if (filterAcademicYear) {
            filterAcademicYear.innerHTML = generateAcademicYearOptions();
        }
        
        // Initialize
        const createBtn = document.getElementById('createHolidayBtn');
        if (createBtn) createBtn.onclick = () => window.openCreateHoliday();
        
        const applyBtn = document.getElementById('applyFilterBtn');
        if (applyBtn) applyBtn.onclick = () => loadHolidays(1);
        
        const resetBtn = document.getElementById('resetFilterBtn');
        if (resetBtn) {
            resetBtn.onclick = () => {
                const schoolFilter = document.getElementById('filter_school_id');
                const academicFilter = document.getElementById('filter_academic_year');
                const boardFilter = document.getElementById('filter_board_id');
                const classFilter = document.getElementById('filter_class_id');
                
                if (schoolFilter) schoolFilter.value = '';
                if (academicFilter) academicFilter.value = '';
                if (boardFilter) boardFilter.value = '';
                if (classFilter) classFilter.value = '';
                loadHolidays(1);
            };
        }
        
        loadSchools();
        loadHolidays(1);
    </script>
</body>
</html>