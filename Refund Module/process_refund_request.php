<?php
session_start();
require_once 'php/includes/auth.php';
require_once 'php/includes/db.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form values
        $refund_id = $_POST['refund_id'];
        $amount_requested = $_POST['amount_requested'];
        $date_requested = $_POST['date_requested'];
        $reason = $_POST['reason'];

        // Get student ID from session
        $student_id = $_SESSION['user_id'];

        // Default status
        $status = 'pending';

        // Insert query
        $query = "INSERT INTO refund_requests (refund_id, student_id, amount_requested, reason, status, date_requested)
                  VALUES (:refund_id, :student_id, :amount_requested, :reason, :status, :date_requested)";

        $stmt = $db->prepare($query);

        $stmt->bindParam(':refund_id', $refund_id);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':amount_requested', $amount_requested);
        $stmt->bindParam(':reason', $reason);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':date_requested', $date_requested);

        if ($stmt->execute()) {
            // Redirect correctly with underscore
            header('Location: refund_request.php?submitted=1');
            exit();
        } else {
            echo "Error processing refund request: " . implode(", ", $stmt->errorInfo());
        }

    } catch (PDOException $e) {
        error_log("Error processing refund request: " . $e->getMessage());
        echo "Error processing refund request: " . $e->getMessage();
    }
}
?>
