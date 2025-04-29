<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/db.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $refund_id = $_POST['refund_id'] ?? '';
    $status = $_POST['status'] ?? '';

    if (!in_array($status, ['Pending', 'Approved', 'Denied'])) {
        http_response_code(400);
        echo 'Invalid status.';
        exit;
    }

    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo 'Unauthorized.';
        exit;
    }

    try {
        $stmt = $db->prepare("UPDATE refund_requests SET status = :status WHERE refund_id = :refund_id");
        $stmt->execute([
            ':status' => $status,
            ':refund_id' => $refund_id
        ]);
        echo 'Success';
    } catch (PDOException $e) {
        error_log("Refund Update Error: " . $e->getMessage());
        http_response_code(500);
        echo 'Database error.';
    }
} else {
    http_response_code(405);
    echo 'Method Not Allowed';
}
?>
