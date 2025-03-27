<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    echo "Unauthorized access.";
    exit();
}

$student_id = $_SESSION['user_id'];
$admin_email = "admin@gym254.com";

// Fetch last attendance record
$attendance = $conn->prepare("SELECT id, check_in, check_out, hours_required FROM attendance WHERE student_id = ? ORDER BY check_in DESC LIMIT 1");
$attendance->bind_param("i", $student_id);
$attendance->execute();
$last_attendance = $attendance->get_result()->fetch_assoc();

// Handle Check-In
if (isset($_POST['check_in'])) {
    $activity = $_POST['activity'];
    $hours_required = $_POST['hours_required'];

    $stmt = $conn->prepare("INSERT INTO attendance (student_id, activity, check_in, hours_required) VALUES (?, ?, NOW(), ?)");
    $stmt->bind_param("isi", $student_id, $activity, $hours_required);

    if ($stmt->execute()) {
        echo "Check-In successful!";
    } else {
        echo "Error during Check-In.";
    }
    exit();
}

// Handle Check-Out
if (isset($_POST['check_out']) && $last_attendance && !$last_attendance['check_out']) {
    $attendance_id = $last_attendance['id'];
    $check_in_time = strtotime($last_attendance['check_in']);
    $hours_required = $last_attendance['hours_required'] * 3600;
    $current_time = time();
    $time_spent = $current_time - $check_in_time;

    // Check if the student left early
    if ($time_spent < $hours_required) {
        $remaining_time = gmdate("H:i:s", $hours_required - $time_spent);

        // Notify student
        $student_message = "Dear " . htmlspecialchars($student['name']) . ",\n\nYou checked out before completing your required activity time. You had $remaining_time remaining.\nPlease ensure you complete your sessions properly.";
        mail($student['email'], "Early Check-Out Alert", $student_message, "From: no-reply@gym254.com");

        // Notify admin
        $admin_message = "Admin Alert:\n\nStudent " . htmlspecialchars($student['name']) . " checked out early. Remaining time: $remaining_time.\nPlease take appropriate action.";
        mail($admin_email, "Student Early Check-Out Alert", $admin_message, "From: no-reply@gym254.com");

        echo "You checked out early! You have $remaining_time remaining. An admin has been notified.";
    } else {
        echo "Check-Out successful!";
    }

    // Update check-out time in the database
    $stmt = $conn->prepare("UPDATE attendance SET check_out = NOW() WHERE id = ?");
    $stmt->bind_param("i", $attendance_id);
    $stmt->execute();

    exit();
}
?>
