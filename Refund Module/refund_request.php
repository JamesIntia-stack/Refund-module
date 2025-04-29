<?php
session_start();
require_once 'php/includes/auth.php';
require_once 'php/includes/db.php';

requireLogin();

$user_id = $_SESSION['user_id'];
$username = htmlspecialchars($_SESSION['username']); // safe output
$user_role = $_SESSION['user_role'];

// Get the next refund_id based on the current count of refund requests
try {
    $query = "SELECT MAX(refund_id) AS max_refund_id FROM refund_requests";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $next_refund_id = $result['max_refund_id'] + 1; // Increment the max refund_id by 1
} catch (PDOException $e) {
    error_log("Error fetching max refund ID: " . $e->getMessage());
    $next_refund_id = 1; // Start from 1 if no records exist
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/fms-style.css">
    <link rel="shortcut icon" href="./assets/favicon/SIS.ico" type="image/x-icon">
    <title>Request Refund</title>
</head>

<body>
    <header>
        <div class="header-content">
            <img src="./assets/img/SIS-logo.png" alt="Student Information System Logo">
            <div>
                <h1>Student Information System</h1>
                <h2>Finance</h2>
            </div>
        </div>
    </header>
    <main class="main">
        <!-- Sidebar Section -->
        <aside class="sidebar">
            <nav aria-label="Main navigation">
                <ul>
                    <li><a href="student-fees.php"><span class="mdi mdi-account-school-outline"></span><span>Student Fees</span></a></li>
                    <li><a href="billing-invoicing.php"><span class="mdi mdi-invoice-list-outline"></span><span>Billing Invoicing</span></a></li>
                    <li><a href="scholarship.php"><span class="mdi mdi-certificate-outline"></span><span>Scholarship</span></a></li>
                    <li><a href="<?php echo ($user_role === 'admin') ? 'refund.php' : 'refund_request.php'; ?>"><span class="mdi mdi-cash-refund"></span><span>Refund</span></a></li>

                    <!-- Admin-only Modules -->
                    <?php if ($user_role === 'admin'): ?>
                        <li><a href="financial-report.php"><span class="mdi mdi-finance"></span><span>Financial Report</span></a></li>
                        <li><a href="audit-trail.php"><span class="mdi mdi-monitor-eye"></span><span>Audit Trail</span></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <nav aria-label="User options">
                <ul>
                    <li><a href="./php/logout.php"><span class="mdi mdi-logout"></span> <span>Logout</span></a></li>
                </ul>
            </nav>
        </aside>

        <!-- Content Section -->
        <section class="content">
            <div class="content-header">
                <button class="js-sidenav-toggle" aria-label="Toggle navigation menu">
                    <span class="mdi mdi-menu"></span>
                </button>
                <h3>Request a Refund</h3>
            </div>
            <article class="module-content">
    <div class="refund-request">
        <form action="process_refund_request.php" method="POST">
            <table class="refund-form-table">
                <tr>
                    <td><label for="refund_id">Refund ID:</label></td>
                    <td><input type="text" name="refund_id" id="refund_id" value="<?php echo $next_refund_id; ?>" readonly></td>
                </tr>

                <tr>
                    <td><label for="student_name">Student Name:</label></td>
                    <td><input type="text" name="student_name" id="student_name" value="<?php echo $username; ?>" readonly></td>
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                </tr>

                <tr>
                    <td><label for="amount_requested">Amount Requested:</label></td>
                    <td><input type="number" name="amount_requested" id="amount_requested" step="0.01" required></td>
                </tr>

                <tr>
                    <td><label for="date_requested">Date Requested:</label></td>
                    <td><input type="text" name="date_requested" id="date_requested" value="<?php echo date('Y-m-d'); ?>" readonly></td>
                </tr>

                <tr>
                    <td><label for="reason">Reason for Refund:</label></td>
                    <td><textarea name="reason" id="reason" rows="4" required></textarea></td>
                </tr>

                <tr>
                    <td colspan="2" class="submit-btn-cell">
                        <button type="submit">Submit Refund Request</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</article>


        </section>
    </main>

    <footer>
        <address>
            <p>For inquiries please contact 000-0000<br>
                Email: sisfinance3220@gmail.com</p>
        </address>
        <p>&copy; 2025 Student Information System<br>All Rights Reserved</p>
    </footer>

    <script src="./assets/js/fms-script.js"></script>
</body>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('submitted')) {
        alert("Request Submitted");
    }
});
</script>


</html>

