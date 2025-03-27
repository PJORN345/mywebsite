<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Fetch student details including membership type
$stmt = $conn->prepare("SELECT name, email, membership_type FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

// Fetch attendance data for charts
$attendance_data = [];
$completed = 0;
$incomplete = 0;

$query = $conn->prepare("SELECT activity, COUNT(*) as count, SUM(CASE WHEN check_out IS NOT NULL THEN 1 ELSE 0 END) AS completed FROM attendance WHERE student_id = ? GROUP BY activity");
$query->bind_param("i", $student_id);
$query->execute();
$result = $query->get_result();

while ($row = $result->fetch_assoc()) {
    $attendance_data[] = $row;
    $completed += $row['completed'];
    $incomplete += ($row['count'] - $row['completed']);
}

// Convert data to JSON for JavaScript
$activities = json_encode(array_column($attendance_data, 'activity'));
$attendance_counts = json_encode(array_column($attendance_data, 'count'));
$completed_checkouts = json_encode(array_column($attendance_data, 'completed'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile & Attendance Overview</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Profile & Attendance Overview</h2>

        <!-- Profile Section -->
        <div class="card mb-4">
            <div class="card-body text-center">
                <h4><?php echo htmlspecialchars($student['name']); ?></h4>
                <p>Email: <?php echo htmlspecialchars($student['email']); ?></p>
                <p>Membership Type: <strong><?php echo htmlspecialchars($student['membership_type']); ?></strong></p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <div class="col-md-6">
                <canvas id="lineChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="pieChart"></canvas>
            </div>
        </div>

        <!-- Attendance Summary -->
        <div class="mt-4 text-center">
            <h4>Attendance Summary</h4>
            <p><strong>Completed Checkouts:</strong> <?php echo $completed; ?></p>
            <p><strong>Incomplete Checkouts:</strong> <?php echo $incomplete; ?></p>
        </div>
    </div>

    <script>
        const activities = <?php echo $activities; ?>;
        const attendanceCounts = <?php echo $attendance_counts; ?>;
        const completedCheckouts = <?php echo $completed_checkouts; ?>;
        const incompleteCheckouts = attendanceCounts.map((total, index) => total - completedCheckouts[index]);

        // Line Chart - Activity vs Attendance
        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: activities,
                datasets: [{
                    label: 'Total Attendance',
                    data: attendanceCounts,
                    borderColor: 'blue',
                    fill: false
                }, {
                    label: 'Completed Checkouts',
                    data: completedCheckouts,
                    borderColor: 'green',
                    fill: false
                }]
            }
        });

        // Pie Chart - Completed vs Incomplete Checkouts
        new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: ['Completed Checkouts', 'Incomplete Checkouts'],
                datasets: [{
                    data: [<?php echo $completed; ?>, <?php echo $incomplete; ?>],
                    backgroundColor: ['green', 'red']
                }]
            }
        });
    </script>
</body>
</html>
