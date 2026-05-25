<?php
$host = 'localhost';
$dbname = 'tutorix_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get all tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Connected successfully!',
        'database' => $dbname,
        'tables' => $tables
    ]);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Connection failed: ' . $e->getMessage(),
        'error_code' => $e->getCode()
    ]);
}
?>