<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'tutorix_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Get the raw input
$rawInput = file_get_contents('php://input');
error_log("Raw input: " . $rawInput);

$input = json_decode($rawInput, true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
    exit;
}

// Check required fields (group_id is optional)
$required = ['school_id', 'board_id', 'class_id', 'academic_year', 'fee_name', 'amount'];
foreach ($required as $field) {
    if (empty($input[$field])) {
        echo json_encode(['success' => false, 'message' => "$field is required"]);
        exit;
    }
}

// Prepare SQL - group_id is optional
$sql = "INSERT INTO TX_SCHOOL_FEE_STRUCTURE 
        (school_id, board_id, class_id, academic_year, fee_name, amount, account_number, ifsc_code, created_datetime) 
        VALUES 
        (:school_id, :board_id, :class_id, :academic_year, :fee_name, :amount, :account_number, :ifsc_code, NOW())";

// Add group_id to SQL if provided
if (isset($input['group_id']) && $input['group_id'] != '') {
    $sql = "INSERT INTO TX_SCHOOL_FEE_STRUCTURE 
            (school_id, board_id, class_id, group_id, academic_year, fee_name, amount, account_number, ifsc_code, created_datetime) 
            VALUES 
            (:school_id, :board_id, :class_id, :group_id, :academic_year, :fee_name, :amount, :account_number, :ifsc_code, NOW())";
}

try {
    $stmt = $pdo->prepare($sql);
    
    // Bind parameters
    $stmt->bindValue(':school_id', $input['school_id']);
    $stmt->bindValue(':board_id', $input['board_id']);
    $stmt->bindValue(':class_id', $input['class_id']);
    $stmt->bindValue(':academic_year', $input['academic_year']);
    $stmt->bindValue(':fee_name', $input['fee_name']);
    $stmt->bindValue(':amount', $input['amount']);
    $stmt->bindValue(':account_number', isset($input['account_number']) ? $input['account_number'] : null);
    $stmt->bindValue(':ifsc_code', isset($input['ifsc_code']) ? $input['ifsc_code'] : null);
    
    if (isset($input['group_id']) && $input['group_id'] != '') {
        $stmt->bindValue(':group_id', $input['group_id']);
    }
    
    $stmt->execute();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Fee structure added successfully', 
        'id' => $pdo->lastInsertId()
    ]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>