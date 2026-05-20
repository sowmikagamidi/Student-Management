<?php
/**
 * User Add API
 * Creates a new user (student, teacher, mentor, or admin)
 */

// Error reporting for production - set to 0 in production
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tutorix_db');

/**
 * Send JSON response and exit
 */
function sendResponse($success, $message, $data = []) {
    $response = ['success' => $success, 'message' => $message] + $data;
    echo json_encode($response);
    exit;
}

/**
 * Validate email format
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate mobile number (10 digits)
 */
function isValidMobile($mobile) {
    return preg_match('/^[0-9]{10}$/', $mobile);
}

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    sendResponse(false, 'Database connection failed: ' . $conn->connect_error);
}

// Get and decode JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !is_array($data)) {
    sendResponse(false, 'Invalid or no data received');
}

// ========== VALIDATION ==========

// Required fields
$requiredFields = [
    'school_id' => 'School ID',
    'full_name' => 'Full name',
    'email_id' => 'Email ID',
    'mobile_number' => 'Mobile number',
    'role' => 'Role'
];

foreach ($requiredFields as $field => $label) {
    if (empty($data[$field])) {
        sendResponse(false, "$label is required");
    }
}

// Email validation
if (!isValidEmail($data['email_id'])) {
    sendResponse(false, 'Invalid email format');
}

// Mobile validation
if (!isValidMobile($data['mobile_number'])) {
    sendResponse(false, 'Mobile number must be 10 digits');
}

// Role validation
$validRoles = ['student', 'teacher', 'mentor', 'admin'];
if (!in_array($data['role'], $validRoles)) {
    sendResponse(false, 'Invalid role. Allowed: student, teacher, mentor, admin');
}

// Set defaults
$data['user_status'] = $data['user_status'] ?? 'A';
$data['country_code'] = $data['country_code'] ?? '+91';
$data['gender'] = $data['gender'] ?? null;

// ========== LMS LICENSE VALIDATION FOR STUDENTS ==========

$licence_id = null;
$licenseInfo = null;

if ($data['role'] === 'student') {
    $stmt = $conn->prepare("
        SELECT licence_id, available_qty, subscription_type, class_id, joining_date, expiry_date 
        FROM TX_SCHOOL_LICENCE
        WHERE school_id = ? 
            AND licence_type = 'lms' 
            AND expiry_date >= CURDATE() 
            AND (is_deleted = 0 OR is_deleted IS NULL) 
            AND available_qty > 0
        ORDER BY licence_id ASC
        LIMIT 1
    ");
    
    if (!$stmt) {
        sendResponse(false, 'Database error: ' . $conn->error);
    }
    
    $stmt->bind_param('i', $data['school_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $stmt->close();
        sendResponse(false, 'No active LMS license with available slots found. Please add an LMS license first.');
    }
    
    $licenseInfo = $result->fetch_assoc();
    $licence_id = $licenseInfo['licence_id'];
    
    if ($licenseInfo['available_qty'] <= 0) {
        $stmt->close();
        sendResponse(false, 'License quota exceeded. No available slots.');
    }
    
    $stmt->close();
    
    // Auto-populate student fields from license
    $data['subscription_type'] = $data['subscription_type'] ?? $licenseInfo['subscription_type'];
    $data['class_id'] = $data['class_id'] ?? $licenseInfo['class_id'];
    $data['joining_date'] = $data['joining_date'] ?? $licenseInfo['joining_date'];
    $data['expiry_date'] = $data['expiry_date'] ?? $licenseInfo['expiry_date'];
}

// ========== CHECK FOR EXISTING EMAIL ==========

$stmt = $conn->prepare("SELECT user_id FROM USERS WHERE email_id = ?");
if (!$stmt) {
    sendResponse(false, 'Database error: ' . $conn->error);
}

$stmt->bind_param('s', $data['email_id']);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    sendResponse(false, 'Email already exists');
}
$stmt->close();

// ========== GENERATE UNIQUE USERNAME ==========

if (empty($data['user_name'])) {
    // Generate base username from full name
    $base = strtolower(preg_replace('/[^a-z0-9]/i', '', str_replace(' ', '.', $data['full_name'])));
    
    if (empty($base)) {
        $base = 'user' . time();
    }
    
    $data['user_name'] = $base;
    
    // Ensure uniqueness
    $counter = 1;
    $stmt = $conn->prepare("SELECT user_id FROM USERS WHERE user_name = ?");
    
    while (true) {
        $stmt->bind_param('s', $data['user_name']);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows === 0) {
            break;
        }
        
        $data['user_name'] = $base . $counter;
        $counter++;
    }
    $stmt->close();
}

// ========== MAP ROLE TO USER_TYPE ==========

$roleToType = [
    'student' => 'SU',
    'teacher' => 'T',
    'mentor' => 'M',
    'admin' => 'A'
];
$user_type = $roleToType[$data['role']];

// ========== INSERT USER ==========

$password = md5('password123'); // Default password

$sql = "INSERT INTO USERS (
    school_id, full_name, user_name, email_id, country_code, mobile_number,
    password, current_class, user_status, user_type, gender, created_dtm
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    sendResponse(false, 'Database error: ' . $conn->error);
}

$current_class = $data['class_id'] ?? null;

$stmt->bind_param(
    'issssssisis',
    $data['school_id'],
    $data['full_name'],
    $data['user_name'],
    $data['email_id'],
    $data['country_code'],
    $data['mobile_number'],
    $password,
    $current_class,
    $data['user_status'],
    $user_type,
    $data['gender']
);

if (!$stmt->execute()) {
    sendResponse(false, 'Failed to create user: ' . $stmt->error);
}

$user_id = $stmt->insert_id;
$stmt->close();

// ========== UPDATE LICENSE AVAILABLE QUANTITY ==========

if ($data['role'] === 'student' && $licence_id) {
    $stmt = $conn->prepare("
        UPDATE TX_SCHOOL_LICENCE 
        SET available_qty = available_qty - 1 
        WHERE licence_id = ? AND available_qty > 0
    ");
    
    if ($stmt) {
        $stmt->bind_param('i', $licence_id);
        $stmt->execute();
        $stmt->close();
    }
}

// ========== CREATE STUDENT ENROLLMENT ==========

if ($data['role'] === 'student' && !empty($data['class_id'])) {
    // Check if enrollment table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'TX_STUDENT_ENROLLMENT'");
    
    if ($tableCheck && $tableCheck->num_rows > 0) {
        $subscription_type = $data['subscription_type'] ?? 'D';
        $board_id = $data['board_id'] ?? 'C';
        $joining_date = $data['joining_date'] ?? date('Y-m-d');
        $expiry_date = $data['expiry_date'] ?? date('Y-m-d', strtotime('+1 year'));
        
        $stmt = $conn->prepare("
            INSERT INTO TX_STUDENT_ENROLLMENT 
            (student_id, subscription_type, class_id, board_id, school_id, joining_date, expiry_date, created_dtm) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        if ($stmt) {
            $stmt->bind_param('isissis', $user_id, $subscription_type, $data['class_id'], $board_id, $data['school_id'], $joining_date, $expiry_date);
            $stmt->execute();
            $stmt->close();
        }
    }
    
    // Add to batch if provided
    if (!empty($data['batch_id'])) {
        $tableCheck = $conn->query("SHOW TABLES LIKE 'TX_STUDENT_BATCH_MAP'");
        
        if ($tableCheck && $tableCheck->num_rows > 0) {
            $stmt = $conn->prepare("
                INSERT INTO TX_STUDENT_BATCH_MAP (student_id, batch_id, created_dtm) 
                VALUES (?, ?, NOW())
            ");
            
            if ($stmt) {
                $stmt->bind_param('ii', $user_id, $data['batch_id']);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
}

// ========== CREATE TEACHER/MENTOR BATCH MAPPING ==========

if (($data['role'] === 'teacher' || $data['role'] === 'mentor') && !empty($data['batch_id'])) {
    $tableCheck = $conn->query("SHOW TABLES LIKE 'TX_MENTOR_BATCH_MAP'");
    
    if ($tableCheck && $tableCheck->num_rows > 0) {
        $board_id = $data['board_id'] ?? 'C';
        $subject_id = $data['subject_id'] ?? null;
        
        $stmt = $conn->prepare("
            INSERT INTO TX_MENTOR_BATCH_MAP (batch_id, board_id, mentor_id, subject_id, school_id, created_dtm) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        
        if ($stmt) {
            $stmt->bind_param('isisi', $data['batch_id'], $board_id, $user_id, $subject_id, $data['school_id']);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// ========== SUCCESS RESPONSE ==========

sendResponse(true, 'User created successfully', [
    'user_id' => $user_id,
    'user_name' => $data['user_name']
]);

$conn->close();
?>