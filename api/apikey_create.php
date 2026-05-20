<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'tutorix_db';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No data received']);
    exit;
}

// Validate required fields
if (empty($data['school_id']) || empty($data['class_id']) || empty($data['validity'])) {
    echo json_encode(['success' => false, 'message' => 'School, Class, and Validity are required']);
    exit;
}

// Generate key if not provided
if (!empty($data['key'])) {
    // Remove any existing hyphens and convert to uppercase
    $api_key = strtoupper(preg_replace('/[^A-Z0-9]/', '', $data['key']));
    
    // Validate key format (should be alphanumeric only)
    if (!preg_match('/^[A-Z0-9]+$/', $api_key)) {
        echo json_encode(['success' => false, 'message' => 'API key can only contain letters and numbers']);
        exit;
    }
    
    // Format with hyphens for display (add hyphen every 4 chars)
    $formatted_key = '';
    for ($i = 0; $i < strlen($api_key); $i++) {
        if ($i > 0 && $i % 4 == 0) {
            $formatted_key .= '-';
        }
        $formatted_key .= $api_key[$i];
    }
    $api_key = $formatted_key;
} else {
    // Generate key in format: PREFIX-YYYY-YYYY-SUFFIX
    $api_key = generateApiKey($data['class_id']);
}

// Check if key already exists
$check = $conn->prepare("SELECT id FROM TX_SCHOOL_API_KEYS WHERE `key` = ?");
$check->bind_param('s', $api_key);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'API key already exists']);
    $check->close();
    $conn->close();
    exit;
}
$check->close();

$sql = "INSERT INTO TX_SCHOOL_API_KEYS (`key`, school_id, class_id, validity, created_dtm) 
        VALUES (?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param('siis', $api_key, $data['school_id'], $data['class_id'], $data['validity']);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'API key created successfully', 'key' => $api_key]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();

function generateApiKey($class_id) {
    // Prefixes
    $prefixes = ['TPTX', 'RATX', 'EDTX', 'LMTX'];
    $prefix = $prefixes[array_rand($prefixes)];
    
    // Years
    $currentYear = date('Y');
    $nextYear = $currentYear + 1;
    
    // Suffix - either class based or random
    // 70% chance to use class ID, 30% chance random
    $useClass = rand(1, 100) <= 70;
    
    if ($useClass) {
        $suffix = 'CL' . str_pad($class_id, 2, '0', STR_PAD_LEFT);
    } else {
        // Generate random 4-character suffix
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $suffix = '';
        for ($i = 0; $i < 4; $i++) {
            $suffix .= $characters[rand(0, strlen($characters) - 1)];
        }
    }
    
    // Format: PREFIX-YYYY-YYYY-SUFFIX
    return $prefix . '-' . $currentYear . '-' . $nextYear . '-' . $suffix;
}
?>