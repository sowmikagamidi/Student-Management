<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'tutorix_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
        exit;
    }
    
    $school_id = isset($input['school_id']) ? (int)$input['school_id'] : 0;
    $class_id = isset($input['class_id']) ? (int)$input['class_id'] : 0;
    $section = isset($input['section']) ? strtoupper(trim($input['section'])) : 'A';
    $board_id = isset($input['board_id']) ? $input['board_id'] : 'C';
    $academic_year = isset($input['academic_year']) ? $input['academic_year'] : date('Y');
    $class_name = isset($input['class_name']) ? $input['class_name'] : "Class $class_id - Section $section";
    $student_count = isset($input['student_count']) ? (int)$input['student_count'] : 0;
    
    if (!$school_id || !$class_id) {
        echo json_encode(['success' => false, 'message' => 'School ID and Class ID are required']);
        exit;
    }
    
    // Check if class with same class_id and section already exists for this school
    $checkSql = "SELECT COUNT(*) as count FROM tx_class_batches WHERE school_id = :school_id AND class_id = :class_id AND section = :section";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([
        ':school_id' => $school_id,
        ':class_id' => $class_id,
        ':section' => $section
    ]);
    $exists = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($exists['count'] > 0) {
        echo json_encode(['success' => false, 'message' => "Class $class_id with Section \"$section\" already exists for this school!"]);
        exit;
    }
    
    // Insert new class using correct column names
    $sql = "INSERT INTO tx_class_batches (school_id, class_id, section, board_id, academic_year, class_name, student_count, created_dtm) 
            VALUES (:school_id, :class_id, :section, :board_id, :academic_year, :class_name, :student_count, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':school_id' => $school_id,
        ':class_id' => $class_id,
        ':section' => $section,
        ':board_id' => $board_id,
        ':academic_year' => $academic_year,
        ':class_name' => $class_name,
        ':student_count' => $student_count
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Class added successfully',
        'batch_id' => $pdo->lastInsertId()
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>