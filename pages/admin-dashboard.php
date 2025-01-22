<?php
require '../includes/session.php';


if (isLoggedIn()) {
    $user = getUser();
}
if (!isset($user) || $user->getRole() !== 'Admin') {

    header('Location: login.php');
    exit();
}


$adminStats = $user->viewGlobalStatistics();

$usersPerPage = 10;
$totalUsers = $adminStats['total_users'];
$totalPages = ceil($totalUsers / $usersPerPage);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, min($page, $totalPages));

$offset = ($page - 1) * $usersPerPage;
$users = $user->getUsersOffSet($offset, $usersPerPage);

$pendingTeachers = $user->getPendingTeachers();

$categories = Category::getAllCategories();
$tags = Tag::getAllTags();
$courses = Course::getAllCourses();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouDemy - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #FF6B00;
            --sidebar-width: 250px;
            --sidebar-bg: #2C3E50;
            --sidebar-hover: #34495E;
        }

        body {
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: var(--sidebar-bg);
            padding-top: 1rem;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        #sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.8rem 1.5rem;
            transition: all 0.3s ease;
        }

        #sidebar .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: white;
        }

        #sidebar .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }

        #main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
        }

        .stats-card {
            border-left: 4px solid var(--primary-color);
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .icon-user {
            background-color: #2ECC71;
        }

        .icon-course {
            background-color: #3498DB;
        }

        .icon-enrollment {
            background-color: #F1C40F;
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }

            #sidebar.active {
                margin-left: 0;
            }

            #main-content {
                margin-left: 0;
            }

            #main-content.active {
                margin-left: var(--sidebar-width);
            }
        }

        .navbar-brand {
            color: white !important;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            padding: 0.25rem 0.5rem;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            font-size: 0.75rem;
        }

        .table-action-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .search-box {
            max-width: 300px;
        }

        .status-badge {
            padding: 0.25em 0.6em;
            font-size: 0.75rem;
            border-radius: 0.25rem;
        }

        .status-active {
            background-color: #28a745;
            color: white;
        }

        .status-suspended {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>

<body>
    <nav id="sidebar">
        <div class="px-3 mb-4">
            <a class="navbar-brand" href="#">YouDemy</a>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#dashboard">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#ManageUsers">
                    <i class="fas fa-users me-2"></i>Manage Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#courses">
                    <i class="fas fa-book me-2"></i>Manage Courses
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#categories">
                    <i class="fas fa-folder me-2"></i>Manage Categories
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#tags">
                    <i class="fas fa-tags me-2"></i>Manage Tags
                </a>
            </li>
            <li class="nav-item mt-auto">
                <a class="nav-link text-danger" href="logout.php">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </li>
        </ul>
    </nav>

    <div id="main-content">
        <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow-sm mb-4">
            <div class="container-fluid">

                <div class="ms-auto d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-lg me-2"></i>
                            <?php echo htmlspecialchars($user->getFirstName() ?? 'Admin'); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#profile">
                                    <i class="fas fa-user me-2"></i>Profile</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <div class="dashboard-section">
            <div class="row mb-4">
                <div class="col-12">
                    <h2>Welcome back, <?php echo htmlspecialchars($user->getFirstName() ?? 'Admin'); ?>!</h2>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card stats-card h-100">
                        <div class="card-body">
                            <h6 class="text-muted">Total Users</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="mb-0"><?php echo $adminStats['total_users']; ?></h3>
                                <i class="fas fa-users fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card h-100">
                        <div class="card-body">
                            <h6 class="text-muted">Total Courses</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="mb-0"><?php echo $adminStats['total_courses']; ?></h3>
                                <i class="fas fa-book fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card h-100">
                        <div class="card-body">
                            <h6 class="text-muted">Total Enrollments</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="mb-0"><?php echo $adminStats['total_enrollments']; ?></h3>
                                <i class="fas fa-user-graduate fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card h-100">
                        <div class="card-body">
                            <h6 class="text-muted">Most Popular Course</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><?php echo htmlspecialchars($adminStats['most_popular_course']); ?></h6>
                                <i class="fas fa-crown fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card-body p-0">
                <div class="col">
                    <h5 class="mb-0">Teacher Applications</h5>
                </div>
                <?php if (empty($pendingTeachers)): ?>
                    <div class="alert alert-info m-4">
                        No pending teacher applications at the moment.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">Name</th>
                                    <th>Specialty</th>
                                    <th>Email</th>
                                    <th>Applied Date</th>
                                    <th class="text-end px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendingTeachers as $teacher): ?>
                                    <tr>
                                        <td class="px-4">
                                            <?php echo htmlspecialchars($teacher['firstname'] . " " . $teacher['lastname']); ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <?php echo htmlspecialchars($teacher['specialtyName']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($teacher['email']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($teacher['applied'])); ?></td>
                                        <td class="text-end px-4">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailsModal<?php echo $teacher['teacherID']; ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="detailsModal<?php echo $teacher['teacherID']; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Application Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="fw-bold mb-2">Personal Information</label>
                                                        <p class="mb-1">
                                                            <i class="fas fa-user text-muted me-2"></i>
                                                            <?php echo htmlspecialchars($teacher['firstname'] . " " . $teacher['lastname']); ?>
                                                        </p>
                                                        <p class="mb-1">
                                                            <i class="fas fa-envelope text-muted me-2"></i>
                                                            <?php echo htmlspecialchars($teacher['email']); ?>
                                                        </p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="fw-bold mb-2">Specialty</label>
                                                        <p class="mb-1">
                                                            <i class="fas fa-graduation-cap text-muted me-2"></i>
                                                            <?php echo htmlspecialchars($teacher['specialtyName']); ?>
                                                        </p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="fw-bold mb-2">Description</label>
                                                        <p class="mb-1">
                                                            <?php echo htmlspecialchars($teacher['description']); ?>
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <label class="fw-bold mb-2">Application Date</label>
                                                        <p class="mb-1">
                                                            <i class="fas fa-calendar text-muted me-2"></i>
                                                            <?php echo date('M d, Y', strtotime($teacher['applied'])); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="approve_teacher.php" method='post'>
                                                        <input type="hidden" name="teacherID" value="<?php echo $teacher['teacherID']; ?>">
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="fas fa-check me-2"></i>Approve
                                                        </button>
                                                    </form>
                                                    <form action="reject_teacher.php" method='post'>
                                                        <input type="hidden" name="teacherID" value="<?php echo $teacher['teacherID']; ?>">
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-times me-2"></i>Reject
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>




        <!-- User Management -->
        <div class="container-fluid py-4" id="ManageUsers">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">User Management</h5>
                            <div class="d-flex gap-2">
                                <input type="search" class="form-control" placeholder="Search users...">
                                <button class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($user['userID']); ?></td>
                                                <td><?php echo htmlspecialchars($user['firstName'] . " " . $user['lastName']); ?></td>
                                                <td><?php echo htmlspecialchars($user['Email']); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo $user['role'] === 'Admin' ? 'danger' : ($user['role'] === 'teacher' ? 'primary' : 'success'); ?>">
                                                        <?php echo htmlspecialchars($user['role']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="userId" value="<?php echo $user['userID']; ?>">
                                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                            <option value="active" <?php echo $user['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                                            <option value="suspended" <?php echo $user['status'] === 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                                                            <option value="inactive" <?php echo $user['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                                        </select>
                                                        <input type="hidden" name="updateStatus" value="1">
                                                    </form>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" onclick="viewUser(<?php echo $user['userID']; ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>


                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <nav class="mt-4">
                                <ul class="pagination justify-content-center">
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?php echo $page === $i ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- User View Modal -->
        <div class="modal fade userDetailsModal" id="userModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userDetailsModalLabel">User Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- User details will be loaded here -->
                        <div id="userDetailsContent"></div>

                    </div>

                </div>
            </div>

        </div>
        <!-- <div class="container-fluid py-4"> -->
        <!-- Filters Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" name="category">
                            <option value="">All Categories</option>
                            <!-- Add categories dynamically -->
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" placeholder="Search courses...">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Courses Grid -->
        <div class="row" id="courses">
            <?php foreach ($courses as $course): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card course-card h-100">
                        <div class="card-header bg-white">
                            
                            <h5 class="card-title mb-0"><?php echo htmlspecialchars($course->getTitle()); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-user me-2"></i><?php echo htmlspecialchars($course->getTeacherNameById()); ?>
                                </small>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-folder me-2"></i><?php echo htmlspecialchars(Category::getCategoryById($course->getCategoryID())->getName()); ?>
                                </small>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-users me-2"></i><?php echo $course->getTotalEnrollments(); ?> students
                                </small>
                            </div>

                            <p class="card-text"><?php echo substr(htmlspecialchars($course->getDescription()), 0, 100); ?>...</p>

                            
                            </div> 
                        </div>
                    </div>
                    <?php  endforeach;?>
                </div>
            
        
        
        <div class="container-fluid py-4">
            <div class="row">
                <!-- Categories Section -->
                <div class="col-md-6 mb-4" id="categories">
                    <div class="card">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Categories</h5>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                <i class="fas fa-plus me-2"></i>Add Category
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Courses</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($categories as $category): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($category->getName()); ?></td>
                                                <td>
                                                    <?php
                                                    echo $category->totalCourses();
                                                    ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" onclick='editCategory("<?php echo $category->getCategoryId(); ?>","<?php echo $category->getName(); ?>")'>
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <form action="delete_category.php" method="get" style="display:inline;">
                                                        <input type="hidden" name="category_id" value="<?php echo $category->getCategoryId(); ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tags Section -->
                <div class="col-md-6 mb-4">
                    <div class="card" id="tags">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Tags</h5>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTagsModal">
                                <i class="fas fa-plus me-2"></i>Add Tags
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="d-flex flex-wrap gap-2">
                                    <?php foreach ($tags as $tag): ?>
                                        <span class="badge bg-primary p-2">
                                            <?php echo htmlspecialchars($tag->getName()); ?>
                                            <form action="delete_tag.php" method="post" style="display:inline;">
                                                <input type="hidden" name="tag_id" value="<?php echo $tag->getTagId(); ?>">
                                                <button type="submit" class="btn btn-sm text-white">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="edit_category.php" method="POST">
                            <input type="hidden" id="editCategoryId" name="category_id">
                            <div class="mb-3">
                                <label for="editCategoryName" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="editCategoryName" name="name" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addCategoryModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="add_category.php">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Category Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Category</button>
                            <input type="hidden" name="action" value="addCategory">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addTagsModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="add_tag.php" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Tags</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tag</label>
                        <input type="text" class="form-control" name="tag" placeholder="Enter tag" required>
                    </div>
                </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Tag</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
                        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewUser(userID) {

            // Fetch user details from the server
            fetch('../getUserDetails.php?userID=' + userID)
                .then(response => response.json())
                .then(data => {
                    console.log(data);

                    // Populate the modal with user details
                    const userDetailsContent = document.getElementById('userDetailsContent');
                    userDetailsContent.innerHTML = `
                <p><strong>ID:</strong> ${data.userID}</p>
                <p><strong>Firstname:</strong> ${data.firstName}</p>
                <p><strong>Lastname:</strong> ${data.lastName}</p>
                <p><strong>Email:</strong> ${data.Email}</p>
                <p><strong>Role:</strong> ${data.role}</p>
                <p><strong>Status:</strong> ${data.status}</p>
                <p><strong>Uploaded courses:</strong> ${data.uploaded_courses ?? 0} </p>
                <p><strong>Total enrollments:</strong> ${data.total_enrollments ?? 0} </p>
                <form action="delete_user.php" method='post'>
                <input type="hidden" name="userID" value="${data.userID}">
                <button type="submit" class="btn btn-danger">
                <i class="fas fa-times me-2"></i>Delete
                </button>
                </form>
            `;
                    // Show the modal
                    const userDetailsModal = new bootstrap.Modal(document.querySelector('.userDetailsModal'));
                    userDetailsModal.show();
                })
                .catch(error => {
                    console.error('Error fetching user details:', error);
                });
        }

        function editCategory(categoryId, categoryName) {
            document.getElementById('editCategoryId').value = categoryId;
            document.getElementById('editCategoryName').value = categoryName;
            const editCategoryModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
            editCategoryModal.show();
        }
    </script>
</body>

</html>