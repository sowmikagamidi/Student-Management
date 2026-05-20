<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'tutorix_db';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] != 0) {
    echo json_encode(['success' => false, 'message' => 'Please upload a CSV file']);
    exit;
}

$school_id = $_POST['school_id'] ?? 0;
if (!$school_id) {
    echo json_encode(['success' => false, 'message' => 'School ID required']);
    exit;
}

// Verify school exists
$schoolCheck = $conn->prepare("SELECT school_id FROM TX_SCHOOL_DETAILS WHERE school_id = ?");
$schoolCheck->bind_param('i', $school_id);
$schoolCheck->execute();
$schoolCheck->store_result();
if ($schoolCheck->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid School ID']);
    $schoolCheck->close();
    $conn->close();
    exit;
}
$schoolCheck->close();

$file = fopen($_FILES['csv_file']['tmp_name'], 'r');
if (!$file) {
    echo json_encode(['success' => false, 'message' => 'Unable to read uploaded CSV file']);
    exit;
}

$headers = fgetcsv($file);
if (!$headers) {
    fclose($file);
    echo json_encode(['success' => false, 'message' => 'CSV file is empty']);
    exit;
}

// Clean headers
$headers = array_map(function($header) {
    return strtolower(trim($header));
}, $headers);

// Expected headers for CSV
$expectedHeaders = ['full_name', 'email', 'mobile', 'academic_year', 'board', 'class_id', 'batch_id', 'subscription_type', 'joining_date', 'expiry_date'];

// Map headers to indices
$headerMap = [];
foreach ($expectedHeaders as $expectedHeader) {
    $headerMap[$expectedHeader] = array_search($expectedHeader, $headers);
    if ($headerMap[$expectedHeader] === false) {
        echo json_encode(['success' => false, 'message' => "Missing header: $expectedHeader"]);
        fclose($file);
        $conn->close();
        exit;
    }
}

$success_count = 0;
$failed_count = 0;
$errors = [];

while (($row = fgetcsv($file)) !== false) {
    // Extract data using header mapping
    $full_name = trim($row[$headerMap['full_name']] ?? '');
    $email = trim($row[$headerMap['email']] ?? '');
    $mobile = trim($row[$headerMap['mobile']] ?? '');
    $academic_year = trim($row[$headerMap['academic_year']] ?? '');
    $board_id = strtoupper(trim($row[$headerMap['board']] ?? 'C'));
    $class_id = trim($row[$headerMap['class_id']] ?? '');
    $batch_id = trim($row[$headerMap['batch_id']] ?? '');
    $subscription_type = trim($row[$headerMap['subscription_type']] ?? '');
    $joining_date = trim($row[$headerMap['joining_date']] ?? '');
    $expiry_date = trim($row[$headerMap['expiry_date']] ?? '');
    
    // Validate required fields
    if (empty($full_name) || empty($email)) {
        $failed_count++;
        $errors[] = "Missing name or email for row";
        continue;
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $failed_count++;
        $errors[] = "Invalid email format: $email";
        continue;
    }

    if (!in_array($board_id, ['C', 'I', 'W'])) {
        $failed_count++;
        $errors[] = "Invalid board for $email. Use C, I, or W";
        continue;
    }
    
    // Check if email exists
    $check = $conn->prepare("SELECT user_id FROM USERS WHERE email_id = ?");
    $check->bind_param('s', $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $failed_count++;
        $errors[] = "Email already exists: $email";
        $check->close();
        continue;
    }
    $check->close();
    
    // Generate username from full name
    $base_username = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', str_replace(' ', '.', $full_name)));
    $username = $base_username;
    $counter = 1;
    
    // Make username unique
    $checkUser = $conn->prepare("SELECT user_id FROM USERS WHERE user_name = ?");
    $checkUser->bind_param('s', $username);
    $checkUser->execute();
    $checkUser->store_result();
    while ($checkUser->num_rows > 0) {
        $username = $base_username . $counter;
        $checkUser->bind_param('s', $username);
        $checkUser->execute();
        $checkUser->store_result();
        $counter++;
    }
    $checkUser->close();
    
    // Insert user with correct column names
    $role = 'student';
    $user_type = 'SU';
    $password = md5('password123');
    $user_status = 'A';
    
    $sql = "INSERT INTO USERS (school_id, full_name, user_name, email_id, mobile_number, password, user_status, user_type, created_dtm) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isssssss', $school_id, $full_name, $username, $email, $mobile, $password, $user_status, $user_type);
    
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        
        // Create enrollment for student
        if (!empty($class_id)) {
            $sub_type = !empty($subscription_type) ? $subscription_type : 'D';
            $joining = !empty($joining_date) ? $joining_date : date('Y-m-d');
            $expiry = !empty($expiry_date) ? $expiry_date : date('Y-m-d', strtotime('+1 year'));
            
            $sql2 = "INSERT INTO TX_STUDENT_ENROLLMENT (student_id, subscription_type, class_id, board_id, school_id, joining_date, expiry_date, created_dtm) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param('isissis', $user_id, $sub_type, $class_id, $board_id, $school_id, $joining, $expiry);
            
            if ($stmt2->execute()) {
                $success_count++;
                
                // If batch_id provided, add to batch map
                if (!empty($batch_id)) {
                    $sql3 = "INSERT INTO TX_STUDENT_BATCH_MAP (student_id, batch_id, created_dtm) VALUES (?, ?, NOW())";
                    $stmt3 = $conn->prepare($sql3);
                    $stmt3->bind_param('ii', $user_id, $batch_id);
                    $stmt3->execute();
                    $stmt3->close();
                }
            } else {
                $failed_count++;
                $errors[] = "Failed to create enrollment for: $email - " . $stmt2->error;
            }
            $stmt2->close();
        } else {
            $success_count++;
        }
        $stmt->close();
    } else {
        $failed_count++;
        $errors[] = "Failed to insert user: $email - " . $stmt->error;
    }
}

fclose($file);
$conn->close();

echo json_encode([
    'success' => true,
    'message' => "$success_count users added successfully, $failed_count failed",
    'success_count' => $success_count,
    'failed_count' => $failed_count,
    'errors' => $errors
]);
?>