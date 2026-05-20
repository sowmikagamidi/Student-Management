// API Base URL - Adjusted for your folder structure
const API_BASE = '../api/';

// Toast function
function showToast(message, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
    
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `<span>${type === 'success' ? '✓' : '✗'}</span><span>${message}</span>`;
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Load schools
async function loadSchools() {
    console.log('Loading schools...');
    try {
        const response = await fetch(API_BASE + 'school_list.php');
        const result = await response.json();
        console.log('Schools loaded:', result);
        
        if (result.success) {
            const tbody = document.getElementById('schoolsList');
            const schools = result.data;
            
            if (schools.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">No schools found. Click "Create School" to add one. </td>\n</tr>';
            } else {
                tbody.innerHTML = schools.map(school => `
                    <tr>
                        <td><strong>${escapeHtml(school.school_code)}</strong></td>
                        <td>${escapeHtml(school.school_name)}</td>
                        <td>${escapeHtml(school.board_name)}</td>
                        <td>${escapeHtml(school.city)}</td>
                        <td>${escapeHtml(school.contact_person || '-')}</td>
                        <td><span class="status-${school.status === 'A' ? 'active' : 'inactive'}">${school.status === 'A' ? 'Active' : 'Inactive'}</span></td>
                        <td class="action-buttons">
                            <button class="btn btn-secondary" onclick="openEditSchool(${school.school_id})">✏️ Edit</button>
                             <button class="btn btn-secondary" onclick="manageClasses(${school.school_id}, '${escapeHtml(school.school_name)}')">📚 Classes</button>
                            <button class="btn btn-secondary" onclick="viewLicences(${school.school_id}, '${escapeHtml(school.school_name)}')">📜 Licences</button>
                            <button class="btn btn-secondary" onclick="openTVLicence(${school.school_id}, '${escapeHtml(school.school_name)}')">📺 Add TV</button>
                            <button class="btn btn-secondary" onclick="openLMSLicence(${school.school_id}, '${escapeHtml(school.school_name)}')">📚 Add LMS</button>
                            <button class="btn btn-secondary" onclick="showToast('User Management - Coming soon', 'info')">👤 User</button>
                            <button class="btn btn-secondary" onclick="showToast('API Keys - Coming soon', 'info')">🔑 Keys</button>
                        </td>
                    </tr>
                `).join('');
            }
            document.getElementById('loadingDiv').style.display = 'none';
            document.getElementById('tableContainer').style.display = 'block';
        }
    } catch(error) {
        console.error('Load error:', error);
        showToast('Error loading schools', 'error');
    }
}

function renderSchoolsTable(schools) {
    const tbody = document.getElementById('schoolsList');
    if (!schools || schools.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">No schools found. Click "Create School" to add one.</td></tr>';
        return;
    }
    
    tbody.innerHTML = schools.map(school => `
        <tr>
            <td><strong>${escapeHtml(school.school_code)}</strong></td>
            <td>${escapeHtml(school.school_name)}</td>
            <td>${escapeHtml(school.board_name)}</td>
            <td>${escapeHtml(school.city)}</td>
            <td>${escapeHtml(school.contact_person || '-')}</td>
            <td><span class="status-${school.status === 'A' ? 'active' : 'inactive'}">${school.status === 'A' ? 'Active' : 'Inactive'}</span></td>
            <td class="action-buttons">
                <button class="btn btn-secondary" onclick="openEditSchool(${school.school_id})">✏️ Edit</button>
                <button class="btn btn-secondary" onclick="viewLicences(${school.school_id}, '${escapeHtml(school.school_name)}')">📜 Licences</button>
                <button class="btn btn-secondary" onclick="openTVLicence(${school.school_id}, '${escapeHtml(school.school_name)}')">📺 Add TV</button>
                <button class="btn btn-secondary" onclick="openLMSLicence(${school.school_id}, '${escapeHtml(school.school_name)}')">📚 Add LMS</button>
                <button class="btn btn-secondary" onclick="showToast('User Management - Coming soon', 'info')">👤 User</button>
                <button class="btn btn-secondary" onclick="showToast('API Keys - Coming soon', 'info')">🔑 Keys</button>
            </td>
        </tr>
    `).join('');
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}
// ========== PLACEHOLDER FUNCTIONS FOR LICENCES ==========
// These will be replaced with actual functions in Phase 2

window.viewLicences = function(schoolId, schoolName) {
    showToast(`Viewing licences for ${schoolName} - Coming in Phase 2`, 'info');
};

window.openTVLicence = function(schoolId, schoolName) {
    showToast(`TV Licence for ${schoolName} - Coming in Phase 2`, 'info');
};

window.openLMSLicence = function(schoolId, schoolName) {
    showToast(`LMS Licence for ${schoolName} - Coming in Phase 2`, 'info');
};


// Check school code uniqueness
async function checkSchoolCodeUnique(code) {
    if (!code) return true;
    try {
        const response = await fetch(API_BASE + `school_check_code.php?code=${encodeURIComponent(code)}`);
        const result = await response.json();
        const msgSpan = document.getElementById('code-check-message');
        if (msgSpan) {
            msgSpan.style.display = 'block';
            if (result.exists) {
                msgSpan.style.color = 'red';
                msgSpan.innerHTML = '✗ This school code is already taken';
                return false;
            } else {
                msgSpan.style.color = 'green';
                msgSpan.innerHTML = '✓ School code is available';
                return true;
            }
        }
        return !result.exists;
    } catch (error) {
        return true;
    }
}

// Open create form
function openCreateSchoolForm() {
    RightPanel.open({
        title: 'Create School',
        icon: '🏫',
        width: '560px',
        html: document.getElementById('tpl-create-school').innerHTML,
        onClose: () => loadSchools()
    });
    
    setTimeout(() => {
        const form = document.getElementById('create-school-form');
        if (form) {
            const codeInput = document.getElementById('school_code');
            if (codeInput) {
                codeInput.onblur = () => checkSchoolCodeUnique(codeInput.value);
            }
            form.onsubmit = async (e) => {
                e.preventDefault();
                await submitCreateSchool();
            };
        }
    }, 100);
}

// Submit create
async function submitCreateSchool() {
    const formData = {
        school_code: document.getElementById('school_code')?.value.trim().toUpperCase(),
        school_name: document.getElementById('school_name')?.value.trim(),
        board_id: document.getElementById('board_id')?.value,
        address: document.getElementById('address')?.value,
        city: document.getElementById('city')?.value,
        state: document.getElementById('state')?.value,
        postal_code: document.getElementById('postal_code')?.value,
        country_code: document.getElementById('country_code')?.value,
        contact_person: document.getElementById('contact_person')?.value,
        contact_email: document.getElementById('contact_email')?.value,
        contact_phone: document.getElementById('contact_phone')?.value,
        gst_number: document.getElementById('gst_number')?.value,
        status: document.getElementById('status')?.value
    };
    
    if (!formData.school_code) {
        showToast('School code is required', 'error');
        return;
    }
    if (!formData.school_name) {
        showToast('School name is required', 'error');
        return;
    }
    
    const isUnique = await checkSchoolCodeUnique(formData.school_code);
    if (!isUnique) {
        showToast('School code already exists', 'error');
        return;
    }
    
    try {
        const response = await fetch(API_BASE + 'school_create.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });
        const result = await response.json();
        
        if (result.success) {
            showToast(result.message, 'success');
            RightPanel.closeTop();
            loadSchools();
        } else {
            showToast(result.message, 'error');
        }
    } catch (error) {
        showToast('An error occurred', 'error');
    }
}

// Open edit form
async function openEditSchool(schoolId) {
    try {
        const response = await fetch(API_BASE + 'school_list.php');
        const result = await response.json();
        
        if (result.success) {
            const school = result.data.find(s => s.school_id == schoolId);
            if (school) {
                RightPanel.open({
                    title: `Edit School: ${school.school_code}`,
                    icon: '✏️',
                    width: '560px',
                    html: document.getElementById('tpl-edit-school').innerHTML,
                    onClose: () => loadSchools()
                });
                
                setTimeout(() => {
                    document.getElementById('edit_school_id').value = school.school_id;
                    document.getElementById('edit_school_code').value = school.school_code;
                    document.getElementById('edit_school_name').value = school.school_name || '';
                    document.getElementById('edit_board_id').value = school.board_id || 'C';
                    document.getElementById('edit_status').value = school.status || 'A';
                    document.getElementById('edit_address').value = school.address || '';
                    document.getElementById('edit_city').value = school.city || '';
                    document.getElementById('edit_state').value = school.state || '';
                    document.getElementById('edit_postal_code').value = school.postal_code || '';
                    document.getElementById('edit_country_code').value = school.country_code || 'IN';
                    document.getElementById('edit_contact_person').value = school.contact_person || '';
                    document.getElementById('edit_contact_email').value = school.contact_email || '';
                    document.getElementById('edit_contact_phone').value = school.contact_phone || '';
                    document.getElementById('edit_gst_number').value = school.gst_number || '';
                    
                    const form = document.getElementById('edit-school-form');
                    if (form) {
                        form.onsubmit = async (e) => {
                            e.preventDefault();
                            await submitEditSchool();
                        };
                    }
                }, 100);
            }
        }
    } catch (error) {
        showToast('Failed to load school data', 'error');
    }
}

// Submit edit
async function submitEditSchool() {
    const formData = {
        school_id: document.getElementById('edit_school_id')?.value,
        school_name: document.getElementById('edit_school_name')?.value.trim(),
        board_id: document.getElementById('edit_board_id')?.value,
        address: document.getElementById('edit_address')?.value,
        city: document.getElementById('edit_city')?.value,
        state: document.getElementById('edit_state')?.value,
        postal_code: document.getElementById('edit_postal_code')?.value,
        country_code: document.getElementById('edit_country_code')?.value,
        contact_person: document.getElementById('edit_contact_person')?.value,
        contact_email: document.getElementById('edit_contact_email')?.value,
        contact_phone: document.getElementById('edit_contact_phone')?.value,
        gst_number: document.getElementById('edit_gst_number')?.value,
        status: document.getElementById('edit_status')?.value
    };
    
    if (!formData.school_name) {
        showToast('School name is required', 'error');
        return;
    }
    
    try {
        const response = await fetch(API_BASE + 'school_update.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });
        const result = await response.json();
        
        if (result.success) {
            showToast(result.message, 'success');
            RightPanel.closeTop();
            loadSchools();
        } else {
            showToast(result.message, 'error');
        }
    } catch (error) {
        showToast('An error occurred', 'error');
    }
}

// Placeholder functions
function openTVLicence(schoolId) { showToast(`TV Licence for school ${schoolId} - Coming soon`, 'info'); }
function openLMSLicence(schoolId) { showToast(`LMS Licence for school ${schoolId} - Coming soon`, 'info'); }
function openAddUser(schoolId) { showToast(`Add User for school ${schoolId} - Coming soon`, 'info'); }
function openAPIKeys(schoolId) { showToast(`API Keys for school ${schoolId} - Coming soon`, 'info'); }

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    console.log('Page loaded, fetching schools...');
    loadSchools();
});