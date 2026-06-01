<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'tutorix_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $school_id = isset($_GET['school_id']) ? (int)$_GET['school_id'] : 0;
    
    $sql = "SELECT batch_id, school_id, class_id, section, board_id, academic_year, class_name, student_count, created_dtm 
            FROM tx_class_batches 
            WHERE 1=1";
    
    if ($school_id > 0) {
        $sql .= " AND school_id = :school_id";
    }
    $sql .= " ORDER BY class_id, section";
    
    $stmt = $pdo->prepare($sql);
    if ($school_id > 0) {
        $stmt->execute([':school_id' => $school_id]);
    } else {
        $stmt->execute();
    }
    
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format the response
    $formattedData = [];
    foreach ($data as $row) {
        $formattedData[] = [
            'batch_id' => $row['batch_id'],
            'class_id' => $row['class_id'],
            'section' => $row['section'],
            'board_id' => $row['board_id'],
            'academic_year' => $row['academic_year'],
            'class_name' => $row['class_name'],
            'student_count' => $row['student_count'],
            'created_dtm' => $row['created_dtm']
        ];
    }
    
    echo json_encode(['success' => true, 'data' => $formattedData]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage(), 'data' => []]);
}
?>