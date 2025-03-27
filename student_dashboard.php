<?php
session_start();
include 'db.php';

// Ensure only students can access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Fetch student details
$stmt = $conn->prepare("SELECT name, membership_type FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/dashboard.css">

    <!-- Icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar bg-dark text-light p-3">
            <h3 class="text-center">📚 Dashboard</h3>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-light" href="?page=profile"><i class="fas fa-user"></i> Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="?page=attendance"><i class="fas fa-clock"></i> Attendance</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="?page=activities"><i class="fas fa-tasks"></i> Activities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="?page=notifications"><i class="fas fa-bell"></i> Notifications</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content flex-grow-1 p-4">
            <?php
            // Dynamic page loading based on sidebar selection
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                switch ($page) {
                    case 'profile':
                        include 'profile.php';
                        break;
                    case 'attendance':
                        include 'attendance.php';
                        break;
                    case 'activities':
                        include 'activities.php';
                        break;
                    case 'notifications':
                        include 'notifications.php';
                        break;
                    default:
                        include 'profile.php';
                }
            } else {
                include 'profile.php'; // Default page
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        // Sidebar active link styling
        document.querySelectorAll(".nav-link").forEach(link => {
            if (link.href === window.location.href) {
                link.classList.add("active", "bg-primary");
            }
        });
    </script>
</body>
</html>
