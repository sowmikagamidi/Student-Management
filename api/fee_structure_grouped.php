<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$host = 'localhost';
$dbname = 'tutorix_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $school_id = isset($_GET['school_id']) ? $_GET['school_id'] : 0;
    
    if (!$school_id) {
        echo json_encode(['success' => false, 'message' => 'School ID is required', 'data' => [], 'total_amount' => 0]);
        exit;
    }
    
    // Get fee structures grouped by group_id
    $sql = "SELECT group_id, fee_name, amount, class_id, academic_year
            FROM tx_school_fee_structure
            WHERE school_id = :school_id
            ORDER BY group_id ASC, id ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':school_id' => $school_id]);
    $fees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Group fees by group_id
    $groupedFees = [];
    $totalAmount = 0;
    
    foreach ($fees as $fee) {
        $groupId = $fee['group_id'] ?: 1;
        $groupName = "Group " . $groupId;
        
        if (!isset($groupedFees[$groupId])) {
            $groupedFees[$groupId] = [
                'group_id' => $groupId,
                'group_name' => $groupName,
                'fees' => []
            ];
        }
        
        $groupedFees[$groupId]['fees'][] = [
            'fee_name' => $fee['fee_name'],
            'amount' => floatval($fee['amount']),
            'class_id' => $fee['class_id'],
            'academic_year' => $fee['academic_year']
        ];
        
        $totalAmount += floatval($fee['amount']);
    }
    
    $result = array_values($groupedFees);
    
    echo json_encode([
        'success' => true,
        'data' => $result,
        'total_amount' => $totalAmount,
        'total_fees' => count($fees)
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . $e->getMessage(),
        'data' => [],
        'total_amount' => 0
    ]);
}
?>