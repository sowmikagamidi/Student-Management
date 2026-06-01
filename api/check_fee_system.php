<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_management";

$checks = [
    'database' => false,
    'tables' => [],
    'files' => [],
    'api_endpoints' => []
];

// Check database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}
$checks['database'] = true;

// Check required tables
$required_tables = [
    'tx_school_fee_structure',
    'tx_fee_groups',
    'tx_student_fee_terms',
    'tx_student_fee_details',
    'tx_student_fee_payments'
];

foreach ($required_tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    $checks['tables'][$table] = $result && $result->num_rows > 0;
}

// Check file existence
$base_path = dirname(__FILE__);
$required_files = [
    'admin/student_fee_details.php',
    'api/fee_structure_grouped.php',
    'api/student_fee_assign_terms.php',
    'api/student_term_payment.php',
    'api/student_fee_list_grouped.php'
];

foreach ($required_files as $file) {
    $full_path = str_replace('api', 'admin', $base_path) . '/../' . $file;
    $checks['files'][$file] = file_exists($full_path);
}

// Test API endpoints
$test_school_id = 1;
$api_endpoints = [
    'fee_structure_grouped' => "api/fee_structure_grouped.php?school_id=$test_school_id",
    'classes_list' => "api/classes_list.php?school_id=$test_school_id",
    'school_list' => "api/school_list.php"
];

foreach ($api_endpoints as $name => $endpoint) {
    $checks['api_endpoints'][$name] = file_exists(dirname(__FILE__) . '/' . $endpoint);
}

// Get sample data
$sample_data = [
    'schools' => 0,
    'fee_structures' => 0,
    'fee_groups' => 0,
    'students_with_fees' => 0
];

$schoolResult = $conn->query("SELECT COUNT(*) as count FROM tx_school");
if ($schoolResult) {
    $row = $schoolResult->fetch_assoc();
    $sample_data['schools'] = $row['count'];
}

$feeResult = $conn->query("SELECT COUNT(*) as count FROM tx_school_fee_structure WHERE is_deleted = 0 OR is_deleted IS NULL");
if ($feeResult) {
    $row = $feeResult->fetch_assoc();
    $sample_data['fee_structures'] = $row['count'];
}

$groupResult = $conn->query("SELECT COUNT(*) as count FROM tx_fee_groups");
if ($groupResult) {
    $row = $groupResult->fetch_assoc();
    $sample_data['fee_groups'] = $row['count'];
}

$conn->close();

echo json_encode([
    'success' => true,
    'status' => 'System Check Complete',
    'checks' => $checks,
    'sample_data' => $sample_data,
    'next_steps' => [
        1 => 'Verify all tables are created (run migrate_fee_system.php if needed)',
        2 => 'Check that schools are created in database',
        3 => 'Create fee structures for your schools',
        4 => 'Visit admin/student_fee_details.php to assign fees'
    ]
], JSON_PRETTY_PRINT);
?>
