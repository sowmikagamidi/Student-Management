<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'tutorix_db';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

$sql = "SELECT 
            school_id, 
            school_code, 
            school_name, 
            board_id, 
            address,
            city, 
            state, 
            postal_code,
            country_code,
            contact_person,
            contact_email,
            contact_phone,
            gst_number,
            status,
            created_dtm
        FROM TX_SCHOOL_DETAILS 
        ORDER BY school_id DESC";

$result = $conn->query($sql);

$schools = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $boardNames = ['C' => 'CBSE', 'I' => 'ICSE', 'W' => 'WBBSE'];
        $row['board_name'] = $boardNames[$row['board_id']] ?? $row['board_id'];
        $row['status_text'] = ($row['status'] == 'A') ? 'Active' : 'Inactive';
        $row['status_class'] = ($row['status'] == 'A') ? 'status-active' : 'status-inactive';
        $schools[] = $row;
    }
}

echo json_encode(['success' => true, 'data' => $schools]);

$conn->close();
?>