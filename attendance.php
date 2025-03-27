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
$attendance = $conn->prepare("SELECT id, activity, check_in, check_out, hours_required FROM attendance WHERE student_id = ? ORDER BY check_in DESC LIMIT 1");
$attendance->bind_param("i", $student_id);
$attendance->execute();
$last_attendance = $attendance->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Attendance System</h2>
        <div class="card shadow-lg p-4">
            <h4>Check-in / Check-out</h4>

            <?php if ($last_attendance): ?>
                <p><strong>Activity:</strong> <?php echo htmlspecialchars($last_attendance['activity']); ?></p>
                <p><strong>Check-In Time:</strong> <?php echo date("Y-m-d H:i:s", strtotime($last_attendance['check_in'])); ?></p>
                <p><strong>Check-Out Time:</strong> 
                    <?php echo $last_attendance['check_out'] ? date("Y-m-d H:i:s", strtotime($last_attendance['check_out'])) : "<span style='color:red;'>Not Checked Out</span>"; ?>
                </p>
                <p><strong>Hours Required:</strong> <?php echo $last_attendance['hours_required']; ?> hours</p>
            <?php else: ?>
                <p style="color: red;">No attendance records found.</p>
            <?php endif; ?>

            <form id="attendanceForm">
                <label for="activity">Select Activity:</label>
                <select name="activity" class="form-select mb-3" required>
                    <?php 
                    $activity_records = $conn->query("SELECT activity, hours_required FROM weekly_activities");
                    while ($row = $activity_records->fetch_assoc()) { ?>
                        <option value="<?php echo htmlspecialchars($row['activity']); ?>" data-hours="<?php echo $row['hours_required']; ?>">
                            <?php echo htmlspecialchars($row['activity']); ?> (<?php echo $row['hours_required']; ?> hrs)
                        </option>
                    <?php } ?>
                </select>
                <input type="hidden" name="hours_required" id="hours_required" value="">

                <button type="button" id="checkInBtn" class="btn btn-success me-2">Check In</button>
                <button type="button" id="checkOutBtn" class="btn btn-danger" <?php echo (!$last_attendance || $last_attendance['check_out']) ? 'disabled' : ''; ?>>Check Out</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            function updateHoursRequired() {
                let selectedActivity = $("select[name='activity'] option:selected");
                $("#hours_required").val(selectedActivity.data("hours"));
            }

            $("select[name='activity']").on("change", updateHoursRequired);
            updateHoursRequired(); // Set default value on load

            $("#checkInBtn, #checkOutBtn").click(function () {
                let action = $(this).attr("id") === "checkInBtn" ? "check_in" : "check_out";
                let activity = $("select[name='activity']").val();
                let hoursRequired = $("#hours_required").val();

                $.ajax({
                    url: "ajax_attendance.php",
                    type: "POST",
                    data: { action: action, activity: activity, hours_required: hoursRequired },
                    dataType: "json",
                    success: function (response) {
                        alert(response.message);

                        if (response.status === "success") {
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        }
                    },
                    error: function () {
                        alert("An error occurred. Please try again.");
                    }
                });
            });
        });
    </script>
</body>
</html>
