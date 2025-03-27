<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

$response = ['status' => 'error', 'message' => 'Invalid request.'];

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

$student_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';
$activity = $_POST['activity'] ?? '';
$hours_required = $_POST['hours_required'] ?? '';

if ($action === "check_in") {
    // Check if the student already has an open session
    $check_existing = $conn->prepare("SELECT id FROM attendance WHERE student_id = ? AND check_out IS NULL");
    $check_existing->bind_param("i", $student_id);
    $check_existing->execute();
    $check_existing->store_result();

    if ($check_existing->num_rows > 0) {
        $response['message'] = "You must check out before checking in again.";
    } else {
        $stmt = $conn->prepare("INSERT INTO attendance (student_id, activity, check_in, hours_required) VALUES (?, ?, NOW(), ?)");
        $stmt->bind_param("isi", $student_id, $activity, $hours_required);
        if ($stmt->execute()) {
            $response = ['status' => 'success', 'message' => 'Check-in successful.'];
        } else {
            $response['message'] = "Check-in failed. Please try again.";
        }
    }
} elseif ($action === "check_out") {
    // Check if there's an open session
    $check_attendance = $conn->prepare("SELECT id FROM attendance WHERE student_id = ? AND check_out IS NULL ORDER BY check_in DESC LIMIT 1");
    $check_attendance->bind_param("i", $student_id);
    $check_attendance->execute();
    $result = $check_attendance->get_result();

    if ($row = $result->fetch_assoc()) {
        $attendance_id = $row['id'];
        $update_stmt = $conn->prepare("UPDATE attendance SET check_out = NOW() WHERE id = ?");
        $update_stmt->bind_param("i", $attendance_id);
        if ($update_stmt->execute()) {
            $response = ['status' => 'success', 'message' => 'Check-out successful.'];
        } else {
            $response['message'] = "Check-out failed. Please try again.";
        }
    } else {
        $response['message'] = "No active check-in found.";
    }
}

echo json_encode($response);
?>
