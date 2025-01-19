
<?php
session_start();
// Check if user is logged in and is a teacher
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
//     header('Location: login.php');
//     exit();
// }

// Placeholder data - in real implementation, fetch from database
$teacherStats = [
    'total_courses' => 12,
    'total_students' => 156,
    'average_rating' => 4.8,
    'total_earnings' => 2500
];

$recentCourses = [
    ['id' => 1, 'title' => 'Introduction to PHP', 'students' => 45, 'rating' => 4.9],
    ['id' => 2, 'title' => 'Advanced JavaScript', 'students' => 32, 'rating' => 4.7],
    ['id' => 3, 'title' => 'Web Development Basics', 'students' => 28, 'rating' => 4.8]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouDemy - Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #FF6B00;
            --secondary-color: #4A4A4A;
        }
        
        .navbar-brand {
            color: var(--primary-color) !important;
            font-size: 1.8rem;
            font-weight: bold;
        }
        
        .nav-link {
            color: var(--secondary-color) !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #e65c00;
            border-color: #e65c00;
        }
        
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .stats-card {
            border-left: 4px solid var(--primary-color);
        }
        
        .stats-icon {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">YouDemy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">My Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Analytics</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-link text-dark dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-lg me-2"></i>
                            <?php echo htmlspecialchars($_SESSION['name'] ?? 'Teacher'); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['name'] ?? 'Teacher'); ?>!</h2>
                <p class="text-muted">Here's an overview of your teaching performance</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stats-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted">Total Courses</h6>
                                <h3><?php echo $teacherStats['total_courses']; ?></h3>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-book-open fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted">Total Students</h6>
                                <h3><?php echo $teacherStats['total_students']; ?></h3>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted">Average Rating</h6>
                                <h3><?php echo $teacherStats['average_rating']; ?> <small class="text-warning"><i class="fas fa-star"></i></small></h3>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-star fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted">Total Earnings</h6>
                                <h3>$<?php echo number_format($teacherStats['total_earnings']); ?></h3>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-dollar-sign fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Courses -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Courses</h5>
                        <button class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Add New Course
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Course Title</th>
                                        <th>Students</th>
                                        <th>Rating</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentCourses as $course): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($course['title']); ?></td>
                                        <td><?php echo $course['students']; ?> students</td>
                                        <td>
                                            <span class="text-warning">
                                                <?php echo $course['rating']; ?>
                                                <i class="fas fa-star small"></i>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary me-1">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>