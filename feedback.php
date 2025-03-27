<?php
session_start();
include 'db.php';

// Ensure student is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    
    if ($action == "checkin") {
        $stmt = $conn->prepare("INSERT INTO attendance (student_id, check_in) VALUES (?, NOW())");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
    } elseif ($action == "checkout") {
        $stmt = $conn->prepare("UPDATE attendance SET check_out = NOW() WHERE student_id = ? AND check_out IS NULL");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
    } elseif ($action == "feedback") {
        $feedback = $_POST['feedback'];
        $stmt = $conn->prepare("INSERT INTO feedback (student_id, message, submitted_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("is", $student_id, $feedback);
        $stmt->execute();
    }
    
    header("Location: student_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/feedback.css">
    <title>Attendance & Feedback</title>
    <link rel="stylesheet" type="text/css" href="css/feedback.css">
</head>
<body>
    <h2>Attendance Tracking & Feedback Submission</h2>
    <form method="POST" action="">
        <button type="submit" name="action" value="checkin">Check In</button>
        <button type="submit" name="action" value="checkout">Check Out</button>
    </form>
    <br>
    <h3>Submit Feedback</h3>
    <form method="POST" action="">
        <textarea name="feedback" required placeholder="Enter your feedback here..."></textarea>
        <br>
        <button type="submit" name="action" value="feedback">Submit Feedback</button>
    </form>
    <br>
    <a href="student_dashboard.php">Back to Dashboard</a>
</body>
</html>
