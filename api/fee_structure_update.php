<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$host = 'localhost';
$dbname = 'tutorix_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$sql = "UPDATE TX_SCHOOL_FEE_STRUCTURE SET 
        school_id = :school_id,
        board_id = :board_id,
        class_id = :class_id,
        academic_year = :academic_year,
        fee_name = :fee_name,
        amount = :amount,
        account_number = :account_number,
        ifsc_code = :ifsc_code,
        updated_datetime = NOW()
        WHERE id = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $input['id'],
    ':school_id' => $input['school_id'],
    ':board_id' => $input['board_id'],
    ':class_id' => $input['class_id'],
    ':academic_year' => $input['academic_year'],
    ':fee_name' => $input['fee_name'],
    ':amount' => $input['amount'],
    ':account_number' => $input['account_number'] ?? null,
    ':ifsc_code' => $input['ifsc_code'] ?? null
]);

echo json_encode(['success' => true, 'message' => 'Fee structure updated successfully']);
?>