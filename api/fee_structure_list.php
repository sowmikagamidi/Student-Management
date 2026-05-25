<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

error_reporting(0);
ini_set('display_errors', 0);

$host = 'localhost';
$dbname = 'tutorix_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = ($page - 1) * $limit;

$where = [];
$params = [];

if (isset($_GET['school_id']) && $_GET['school_id'] != '') {
    $where[] = "fs.school_id = :school_id";
    $params[':school_id'] = $_GET['school_id'];
}
if (isset($_GET['academic_year']) && $_GET['academic_year'] != '') {
    $where[] = "fs.academic_year = :academic_year";
    $params[':academic_year'] = $_GET['academic_year'];
}
if (isset($_GET['board_id']) && $_GET['board_id'] != '') {
    // Convert board name to code if needed
    $boardValue = $_GET['board_id'];
    if ($boardValue == 'CBSE') $boardValue = 'C';
    if ($boardValue == 'ICSE') $boardValue = 'I';
    if ($boardValue == 'WBBSE') $boardValue = 'W';
    $where[] = "fs.board_id = :board_id";
    $params[':board_id'] = $boardValue;
}
if (isset($_GET['class_id']) && $_GET['class_id'] != '') {
    $where[] = "fs.class_id = :class_id";
    $params[':class_id'] = $_GET['class_id'];
}

$whereClause = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

try {
    // Get total count
    $countQuery = "SELECT COUNT(*) as total FROM tx_school_fee_structure fs $whereClause";
    $stmt = $pdo->prepare($countQuery);
    $stmt->execute($params);
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $limit);
    
    // First try to get data without join to avoid errors
    $query = "SELECT fs.* FROM tx_school_fee_structure fs $whereClause ORDER BY fs.id DESC LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Add school names by fetching separately
    foreach ($data as &$row) {
        if ($row['school_id']) {
            try {
                // Try different possible column names for school table
                $schoolStmt = $pdo->prepare("SELECT school_name, school_code FROM tx_school_details WHERE school_id = :id");
                $schoolStmt->execute([':id' => $row['school_id']]);
                $school = $schoolStmt->fetch(PDO::FETCH_ASSOC);
                if ($school) {
                    $row['school_name'] = $school['school_name'];
                    $row['school_code'] = $school['school_code'];
                } else {
                    // Try with 'id' column
                    $schoolStmt2 = $pdo->prepare("SELECT school_name, school_code FROM tx_school_details WHERE id = :id");
                    $schoolStmt2->execute([':id' => $row['school_id']]);
                    $school2 = $schoolStmt2->fetch(PDO::FETCH_ASSOC);
                    if ($school2) {
                        $row['school_name'] = $school2['school_name'];
                        $row['school_code'] = $school2['school_code'];
                    } else {
                        $row['school_name'] = 'School ID: ' . $row['school_id'];
                        $row['school_code'] = '';
                    }
                }
            } catch(Exception $e) {
                $row['school_name'] = 'School ID: ' . $row['school_id'];
                $row['school_code'] = '';
            }
        } else {
            $row['school_name'] = 'Unknown';
            $row['school_code'] = '';
        }
    }
    
    echo json_encode([
        'success' => true,
        'data' => $data,
        'total_rows' => (int)$totalRows,
        'total_pages' => (int)$totalPages,
        'current_page' => $page,
        'limit' => $limit
    ]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Query failed: ' . $e->getMessage()]);
}
?>