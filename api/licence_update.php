<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'tutorix_db';

    $conn = new mysqli($host, $user, $pass, $dbname);

    if ($conn->connect_error) {
        throw new Exception('Database connection failed: ' . $conn->connect_error);
    }

    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || empty($data['licence_id'])) {
        throw new Exception('Invalid data or missing licence_id');
    }

    $licence_id = intval($data['licence_id']);

    // Fetch existing licence to determine type
    $result = $conn->query("SELECT licence_type FROM TX_SCHOOL_LICENCE WHERE licence_id = $licence_id");
    if (!$result || $result->num_rows === 0) {
        throw new Exception('Licence not found');
    }

    $licenceRow = $result->fetch_assoc();
    $licence_type = $licenceRow['licence_type'];

    if ($licence_type === 'tv') {
        // For TV licence
        $class_id = intval($data['class_id']);
        $used_status = $conn->real_escape_string($data['used_status']);
        $joining_date = $conn->real_escape_string($data['joining_date']);
        $expiry_date = $conn->real_escape_string($data['expiry_date']);

        $sql = "UPDATE TX_SCHOOL_LICENCE SET
            class_id = $class_id,
            used_status = '$used_status',
            joining_date = '$joining_date',
            expiry_date = '$expiry_date',
            updated_dtm = NOW()
        WHERE licence_id = $licence_id";
    } else {
        // For LMS licence
        $class_id = intval($data['class_id']);
        $batch_id = !empty($data['batch_id']) ? intval($data['batch_id']) : null;
        $subscription_type = $conn->real_escape_string($data['subscription_type']);
        $subscription_qty = intval($data['subscription_qty']);
        $available_qty = intval($data['available_qty']);
        $joining_date = $conn->real_escape_string($data['joining_date']);
        $expiry_date = $conn->real_escape_string($data['expiry_date']);

        $batch_id_sql = ($batch_id === null) ? 'NULL' : $batch_id;

        // Financial fields (optional)
        $currency = !empty($data['currency']) ? $conn->real_escape_string($data['currency']) : 'INR';
        $amount = !empty($data['amount']) ? floatval($data['amount']) : 0;
        $discount = !empty($data['discount']) ? floatval($data['discount']) : 0;
        $paid_amount = !empty($data['paid_amount']) ? floatval($data['paid_amount']) : ($amount - $discount);
        $payment_method = !empty($data['payment_method']) ? $conn->real_escape_string($data['payment_method']) : 'Razorpay';

        $sql = "UPDATE TX_SCHOOL_LICENCE SET
            class_id = $class_id,
            batch_id = $batch_id_sql,
            subscription_type = '$subscription_type',
            subscription_qty = $subscription_qty,
            available_qty = $available_qty,
            joining_date = '$joining_date',
            expiry_date = '$expiry_date',
            currency = '$currency',
            amount = $amount,
            discount = $discount,
            paid_amount = $paid_amount,
            payment_method = '$payment_method',
            updated_dtm = NOW()
        WHERE licence_id = $licence_id";
    }

    if (!$conn->query($sql)) {
        throw new Exception('Database error: ' . $conn->error);
    }

    echo json_encode(['success' => true, 'message' => 'Licence updated successfully']);
    $conn->close();

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
