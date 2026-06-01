<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_management";

$diagnostics = [
    'database' => false,
    'tables' => [],
    'sample_data' => [],
    'errors' => []
];

// Test database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    $diagnostics['errors'][] = "Connection Error: " . $conn->connect_error;
    http_response_code(500);
    echo json_encode($diagnostics);
    exit;
}

$diagnostics['database'] = true;

// Check table existence
$tables_to_check = [
    'tx_school' => 'Schools table',
    'tx_school_fee_structure' => 'Fee structure table',
    'tx_fee_groups' => 'Fee groups table',
    'tx_users' => 'Users table',
    'tx_student_fee_terms' => 'Student fee terms table',
    'tx_student_fee_details' => 'Student fee details table',
    'tx_student_fee_payments' => 'Student fee payments table'
];

foreach ($tables_to_check as $table => $desc) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    $exists = $result && $result->num_rows > 0;
    $diagnostics['tables'][$table] = [
        'exists' => $exists,
        'description' => $desc
    ];
    if (!$exists) {
        $diagnostics['errors'][] = "Missing table: $table";
    }
}

// Check school with ID 9
$school_check = $conn->query("SELECT id, school_name FROM tx_school WHERE id = 9 LIMIT 1");
if ($school_check && $school_check->num_rows > 0) {
    $school = $school_check->fetch_assoc();
    $diagnostics['sample_data']['school_9'] = $school;
} else {
    $diagnostics['errors'][] = "School with ID 9 not found";
}

// Check fee structures for school 9
$fee_check = $conn->query("SELECT COUNT(*) as count FROM tx_school_fee_structure WHERE school_id = 9");
if ($fee_check) {
    $fee_count = $fee_check->fetch_assoc();
    $diagnostics['sample_data']['fee_structures_school_9'] = $fee_count['count'];
    if ($fee_count['count'] == 0) {
        $diagnostics['errors'][] = "No fee structures found for school ID 9";
    }
}

// Get all schools
$schools = $conn->query("SELECT id, school_name FROM tx_school LIMIT 5");
if ($schools) {
    $diagnostics['sample_data']['sample_schools'] = [];
    while ($row = $schools->fetch_assoc()) {
        $diagnostics['sample_data']['sample_schools'][] = $row;
    }
}

$conn->close();

http_response_code(200);
echo json_encode($diagnostics, JSON_PRETTY_PRINT);
?>
