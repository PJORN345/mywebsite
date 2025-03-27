<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

// Ensure only students can access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Fetch student details
$stmt = $conn->prepare("SELECT name, email FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

// Fetch last attendance record
$attendance = $conn->prepare("SELECT check_in, check_out, hours_required FROM attendance WHERE student_id = ? ORDER BY check_in DESC LIMIT 1");
$attendance->bind_param("i", $student_id);
$attendance->execute();
$last_attendance = $attendance->get_result()->fetch_assoc();

// Check if the user checked out early
$notification_message = "";
if ($last_attendance && $last_attendance['check_out']) {
    $check_in_time = strtotime($last_attendance['check_in']);
    $check_out_time = strtotime($last_attendance['check_out']);
    $hours_required = $last_attendance['hours_required'] * 3600; // Convert to seconds
    $time_spent = $check_out_time - $check_in_time;

    if ($time_spent < $hours_required) {
        $remaining_time = gmdate("H:i:s", $hours_required - $time_spent);
        $notification_message = "⚠️ You checked out early! You had $remaining_time remaining. Please complete your sessions properly.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Notifications</h2>

        <div class="card shadow-lg p-4">
            <?php if (!empty($notification_message)): ?>
                <div class="alert alert-warning">
                    <?php echo htmlspecialchars($notification_message); ?>
                </div>
            <?php else: ?>
                <div class="alert alert-success">
                    ✅ No notifications at this time.
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
