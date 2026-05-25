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

$sql = "DELETE FROM TX_SCHOOL_FEE_STRUCTURE WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $input['id']]);

echo json_encode(['success' => true, 'message' => 'Fee structure deleted successfully']);
?>