<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

$host = 'localhost';
$dbname = 'tutorix_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (empty($input['school_id']) || empty($input['class_id']) || empty($input['component_id']) || empty($input['amount'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    $sql = "INSERT INTO TX_STUDENT_FEE_DETAILS 
            (school_id, class_id, group_id, component_id, amount, academic_year, term, payment_status, created_datetime) 
            VALUES 
            (:school_id, :class_id, :group_id, :component_id, :amount, :academic_year, :term, :payment_status, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':school_id' => $input['school_id'],
        ':class_id' => $input['class_id'],
        ':group_id' => $input['group_id'] ?? null,
        ':component_id' => $input['component_id'],
        ':amount' => $input['amount'],
        ':academic_year' => $input['academic_year'],
        ':term' => $input['term'] ?? null,
        ':payment_status' => $input['payment_status'] ?? 'P'
    ]);
    
    echo json_encode(['success' => true, 'message' => 'Fee assigned successfully', 'id' => $pdo->lastInsertId()]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>