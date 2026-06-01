<?php
/**
 * Database Migration: Student Fee Terms and Details Tables
 *
 * This script creates the required tables for the term-based student fee system.
 * Run this only if tables don't already exist.
 */

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create tx_fee_groups table if not exists
$sql1 = "CREATE TABLE IF NOT EXISTS tx_fee_groups (
    id INT PRIMARY KEY AUTO_INCREMENT,
    school_id INT NOT NULL,
    group_name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_deleted INT DEFAULT 0,
    FOREIGN KEY (school_id) REFERENCES tx_school(school_id)
)";

// Create tx_student_fee_terms table if not exists
$sql2 = "CREATE TABLE IF NOT EXISTS tx_student_fee_terms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    school_id INT NOT NULL,
    term_name VARCHAR(100) NOT NULL,
    term_amount DECIMAL(10,2) NOT NULL,
    academic_year INT,
    due_date DATE,
    payment_status ENUM('pending', 'partial', 'paid') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES tx_users(user_id),
    FOREIGN KEY (school_id) REFERENCES tx_school(school_id),
    INDEX idx_student_school (student_id, school_id),
    INDEX idx_academic_year (academic_year)
)";

// Create tx_student_fee_details table if not exists
$sql3 = "CREATE TABLE IF NOT EXISTS tx_student_fee_details (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    school_id INT NOT NULL,
    term_id INT NOT NULL,
    group_id INT,
    fee_name VARCHAR(255) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    paid_amount DECIMAL(10,2) DEFAULT 0,
    academic_year INT,
    due_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES tx_users(user_id),
    FOREIGN KEY (school_id) REFERENCES tx_school(school_id),
    FOREIGN KEY (term_id) REFERENCES tx_student_fee_terms(id) ON DELETE CASCADE,
    INDEX idx_student_term (student_id, term_id),
    INDEX idx_school_year (school_id, academic_year)
)";

// Create tx_student_fee_payments table for tracking payments
$sql4 = "CREATE TABLE IF NOT EXISTS tx_student_fee_payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    school_id INT NOT NULL,
    term_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'card', 'upi', 'bank_transfer') DEFAULT 'cash',
    transaction_id VARCHAR(255),
    payment_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES tx_users(user_id),
    FOREIGN KEY (school_id) REFERENCES tx_school(school_id),
    FOREIGN KEY (term_id) REFERENCES tx_student_fee_terms(id),
    INDEX idx_payment_date (payment_date),
    INDEX idx_student_term_payment (student_id, term_id)
)";

$tables = [
    'tx_fee_groups' => $sql1,
    'tx_student_fee_terms' => $sql2,
    'tx_student_fee_details' => $sql3,
    'tx_student_fee_payments' => $sql4
];

$created = [];
$failed = [];

foreach ($tables as $table_name => $sql) {
    if ($conn->query($sql) === TRUE) {
        $created[] = $table_name;
    } else {
        if (strpos($conn->error, "already exists") === false) {
            $failed[] = "$table_name: " . $conn->error;
        } else {
            $created[] = "$table_name (already exists)";
        }
    }
}

$conn->close();

// Display results
$response = [
    'success' => count($failed) === 0,
    'created_tables' => $created,
    'failed' => $failed,
    'message' => count($failed) === 0 ? 'All tables created successfully!' : 'Some tables failed to create'
];

header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);
?>
