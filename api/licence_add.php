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

    $raw_input = file_get_contents('php://input');
    $data = json_decode($raw_input, true);

    if (!$data) {
        throw new Exception('No valid JSON data received');
    }

    $licence_type = isset($data['licence_type']) ? $data['licence_type'] : 'lms';

    // For TV licence
    if ($licence_type == 'tv') {
        if (empty($data['school_id']) || empty($data['class_id']) || empty($data['joining_date']) || empty($data['expiry_date'])) {
            throw new Exception('Missing required fields for TV licence');
        }

        $api_key = !empty($data['api_key']) ? $data['api_key'] : 'TPTX-' . date('Y') . '-' . (date('Y') + 1) . '-CL' . str_pad($data['class_id'], 2, '0', STR_PAD_LEFT);
        $school_id = intval($data['school_id']);
        $class_id = intval($data['class_id']);
        $used_status = $conn->real_escape_string($data['used_status'] ?? 'N');
        $joining_date = $conn->real_escape_string($data['joining_date']);
        $expiry_date = $conn->real_escape_string($data['expiry_date']);

        $sql = "INSERT INTO TX_SCHOOL_LICENCE (licence_type, school_id, class_id, used_status, joining_date, expiry_date, created_dtm)
                VALUES ('tv', $school_id, $class_id, '$used_status', '$joining_date', '$expiry_date', NOW())";

        if (!$conn->query($sql)) {
            throw new Exception('Database error: ' . $conn->error);
        }

        echo json_encode([
            'success' => true,
            'message' => 'TV Licence created successfully',
            'licence_id' => $conn->insert_id,
            'api_key' => $api_key
        ]);
    }
    // For LMS licence
    else {
        if (empty($data['school_id']) || empty($data['class_id']) || empty($data['subscription_type']) || empty($data['subscription_qty']) || empty($data['joining_date']) || empty($data['expiry_date'])) {
            throw new Exception('Missing required fields for LMS licence');
        }

        $school_id = intval($data['school_id']);
        $class_id = intval($data['class_id']);
        $batch_id = !empty($data['batch_id']) ? intval($data['batch_id']) : null;
        $subscription_type = $conn->real_escape_string($data['subscription_type']);
        $subscription_qty = intval($data['subscription_qty']);
        $available_qty = !empty($data['available_qty']) ? intval($data['available_qty']) : ($subscription_qty - 1);
        $joining_date = $conn->real_escape_string($data['joining_date']);
        $expiry_date = $conn->real_escape_string($data['expiry_date']);
        $order_id = 'ORD-' . time() . rand(100, 999);
        $api_key = !empty($data['api_key']) ? $data['api_key'] : 'RATX-' . date('Y') . '-' . (date('Y') + 1) . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        // Financial fields (optional)
        $currency = !empty($data['currency']) ? $conn->real_escape_string($data['currency']) : 'INR';
        $amount = !empty($data['amount']) ? floatval($data['amount']) : 0;
        $discount = !empty($data['discount']) ? floatval($data['discount']) : 0;
        $paid_amount = !empty($data['paid_amount']) ? floatval($data['paid_amount']) : ($amount - $discount);
        $payment_method = !empty($data['payment_method']) ? $conn->real_escape_string($data['payment_method']) : 'Razorpay';

        $batch_id_sql = ($batch_id === null) ? 'NULL' : $batch_id;

        // Try with all columns including financial fields
        $sql = "INSERT INTO TX_SCHOOL_LICENCE (
            licence_type, school_id, class_id, batch_id, subscription_type, subscription_qty, available_qty,
            joining_date, expiry_date, order_id, currency, amount, discount, paid_amount, payment_method, created_dtm
        ) VALUES (
            'lms', $school_id, $class_id, $batch_id_sql,
            '$subscription_type', $subscription_qty, $available_qty,
            '$joining_date', '$expiry_date', '$order_id', '$currency', $amount, $discount, $paid_amount, '$payment_method', NOW()
        )";

        if (!$conn->query($sql)) {
            // If financial columns don't exist, try without them
            $sql = "INSERT INTO TX_SCHOOL_LICENCE (
                licence_type, school_id, class_id, batch_id, subscription_type, subscription_qty, available_qty,
                joining_date, expiry_date, order_id, created_dtm
            ) VALUES (
                'lms', $school_id, $class_id, $batch_id_sql,
                '$subscription_type', $subscription_qty, $available_qty,
                '$joining_date', '$expiry_date', '$order_id', NOW()
            )";

            if (!$conn->query($sql)) {
                throw new Exception('Database error: ' . $conn->error);
            }
        }

        echo json_encode([
            'success' => true,
            'message' => 'LMS Licence created successfully',
            'licence_id' => $conn->insert_id,
            'order_id' => $order_id,
            'api_key' => $api_key
        ]);
    }

    $conn->close();

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
