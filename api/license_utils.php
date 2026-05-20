<?php
/**
 * License Utilities
 * Shared functions for LMS and TV license validation
 */

/**
 * Check if a class has active LMS license with available slots
 */
function checkClassLMSLicense($conn, $school_id, $class_id) {
    $stmt = $conn->prepare("
        SELECT licence_id, available_qty, subscription_type, joining_date, expiry_date
        FROM TX_SCHOOL_LICENCE
        WHERE school_id = ?
            AND class_id = ?
            AND licence_type = 'lms'
            AND expiry_date >= CURDATE()
            AND (is_deleted = 0 OR is_deleted IS NULL)
            AND available_qty > 0
        ORDER BY licence_id ASC
        LIMIT 1
    ");

    if (!$stmt) {
        return ['success' => false, 'error' => 'Database error: ' . $conn->error];
    }

    $stmt->bind_param('ii', $school_id, $class_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows === 0) {
        return ['success' => false, 'error' => "No active LMS license with available slots for this class"];
    }

    $licenseInfo = $result->fetch_assoc();

    if ($licenseInfo['available_qty'] <= 0) {
        return ['success' => false, 'error' => 'License quota exceeded for this class. No available slots.'];
    }

    return ['success' => true, 'license' => $licenseInfo];
}

/**
 * Check if class exists and is active
 */
function checkClassExists($conn, $school_id, $class_id) {
    $stmt = $conn->prepare("
        SELECT batch_id, class_id, class_name, section
        FROM TX_CLASS_BATCHES
        WHERE school_id = ? AND class_id = ?
        LIMIT 1
    ");

    if (!$stmt) {
        return ['success' => false, 'error' => 'Database error: ' . $conn->error];
    }

    $stmt->bind_param('ii', $school_id, $class_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows === 0) {
        return ['success' => false, 'error' => 'Class does not exist'];
    }

    return ['success' => true, 'class' => $result->fetch_assoc()];
}

/**
 * Get all classes with their LMS license status
 */
function getClassesWithLicenseStatus($conn, $school_id) {
    $stmt = $conn->prepare("
        SELECT
            c.batch_id,
            c.class_id,
            c.class_name,
            c.section,
            c.academic_year,
            c.board_id,
            COALESCE(l.licence_id, 0) as has_lms_license,
            COALESCE(l.available_qty, 0) as available_slots,
            COALESCE(l.expiry_date, NULL) as license_expiry,
            CASE WHEN l.licence_id IS NOT NULL AND l.expiry_date >= CURDATE() AND l.available_qty > 0 THEN 1 ELSE 0 END as lms_active
        FROM TX_CLASS_BATCHES c
        LEFT JOIN TX_SCHOOL_LICENCE l ON c.school_id = l.school_id
            AND c.class_id = l.class_id
            AND l.licence_type = 'lms'
            AND l.expiry_date >= CURDATE()
            AND (l.is_deleted = 0 OR l.is_deleted IS NULL)
        WHERE c.school_id = ?
        ORDER BY c.class_id, c.section
    ");

    if (!$stmt) {
        return ['success' => false, 'error' => 'Database error: ' . $conn->error];
    }

    $stmt->bind_param('i', $school_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $classes = [];
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }

    return ['success' => true, 'classes' => $classes];
}

/**
 * Reduce license available quantity after user creation
 */
function reduceLicenseQuantity($conn, $licence_id) {
    $stmt = $conn->prepare("
        UPDATE TX_SCHOOL_LICENCE
        SET available_qty = available_qty - 1
        WHERE licence_id = ? AND available_qty > 0
    ");

    if (!$stmt) {
        return ['success' => false, 'error' => 'Database error: ' . $conn->error];
    }

    $stmt->bind_param('i', $licence_id);
    $result = $stmt->execute();
    $stmt->close();

    return ['success' => $result];
}

/**
 * Increase license available quantity (when user is deleted)
 */
function increaseLicenseQuantity($conn, $licence_id) {
    $stmt = $conn->prepare("
        UPDATE TX_SCHOOL_LICENCE
        SET available_qty = available_qty + 1
        WHERE licence_id = ?
    ");

    if (!$stmt) {
        return ['success' => false, 'error' => 'Database error: ' . $conn->error];
    }

    $stmt->bind_param('i', $licence_id);
    $result = $stmt->execute();
    $stmt->close();

    return ['success' => $result];
}
?>
