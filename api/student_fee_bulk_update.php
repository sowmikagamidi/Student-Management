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
    $student_id = isset($input['student_id']) ? (int)$input['student_id'] : 0;
    $updates = isset($input['updates']) ? $input['updates'] : [];
    
    if (!$student_id || empty($updates)) {
        echo json_encode(['success' => false, 'message' => 'No updates to apply']);
        exit;
    }
    
    $updated_count = 0;
    
    foreach ($updates as $update) {
        if ($update['type'] === 'due_date') {
            // Update due date for all fees in this term
            $sql = "UPDATE tx_student_fee_details SET due_date = :due_date WHERE student_id = :student_id AND term = :term";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':due_date' => $update['due_date'],
                ':student_id' => $student_id,
                ':term' => $update['term_name']
            ]);
            $updated_count += $stmt->rowCount();
        } elseif ($update['type'] === 'amount') {
            $sql = "UPDATE tx_student_fee_details SET amount = :amount WHERE id = :fee_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':amount' => $update['amount'],
                ':fee_id' => $update['fee_id']
            ]);
            $updated_count += $stmt->rowCount();
        }
    }
    
    echo json_encode(['success' => true, 'message' => "$updated_count updates applied successfully"]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>