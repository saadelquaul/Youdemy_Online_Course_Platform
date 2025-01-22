<?php
// session_start();
require '../includes/session.php';
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}
$user = getUser();
if ($user->getRole() !== 'teacher') {
    header('Location: index.php');
    exit;
}
$courses = $user->viewMyCourses();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_course_id'])) {
    $course = new Course('', '', '', 0, $teacher_id);
    $course->setID($_POST['delete_course_id']);
    if ($user->manageCourse('delete', $course)) {
        header('Location: teacher-dashboard.php?message=Course deleted successfully');
    } else {
        header('Location: teacher-dashboard.php?error=Failed to delete course');
    };
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_course_id'])) {
    $course_id = $_POST['edit_course_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    $tags = isset($_POST['tags']) ? $_POST['tags'] : [];

    $course = new Course($title, $description, $content, $category_id, $teacher_id);
    $course->setID($course_id);
    $course->addTags($tags);
    if ($user->manageCourse('update', $course)) {
        header('Location: teacher-dashboard.php?message=Course updated successfully');
    } else {
        header('Location: teacher-dashboard.php?error=Failed to update course');
    };
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_course'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    $tags = isset($_POST['tags']) ? $_POST['tags'] : [];
    $course = new Course($title, $description, $content, $category_id, $user->getID());
    $course->addTags($tags);
    if($user->addCourse($course, $user->getID())){
        header('Location: teacher-dashboard.php?message=Course added successfully');
    } else {
        header('Location: teacher-dashboard.php?error=Failed to add course');
    };
}
function convertToEmbedURL($url) {
    if (strpos($url, 'youtube.com/watch?v=') !== false) {
        $url = str_replace('watch?v=', 'embed/', $url);
    } elseif (strpos($url, 'youtu.be/') !== false) {
        $url = str_replace('youtu.be/', 'youtube.com/embed/', $url);
    }
    return $url;
}
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
            <a class="navbar-brand" href="../index.php">YouDemy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Dashboard</a>
                    </li>
                    

                </ul>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-link text-dark dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-lg me-2"></i>

                            <?php echo htmlspecialchars($user->getName() ?? 'Teacher'); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
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
                <h2>Welcome back, <?php echo htmlspecialchars($user->getName() ?? 'Teacher'); ?>!</h2>
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
                                <h3><?php echo $user->getTotalCourses(); ?></h3>
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
                                <h3><?php echo $user->getTotalStudents(); ?></h3>
                            </div>
                            <div class="stats-icon">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="container">
            <h1>Teacher Dashboard</h1>
            <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">My Courses</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                <i class="fas fa-plus me-1"></i> Add Course
            </button>
        </div>
        <div class="row">
            <?php foreach ($courses as $course): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card course-card h-100">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0"><?php echo htmlspecialchars($course->getTitle()); ?></h5>
                        </div>
                        <div class="card-body">
                            <iframe class="video-iframe" src="<?php echo htmlspecialchars(convertToEmbedURL($course->getContent())); ?>" allowfullscreen></iframe>
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-user me-2"></i><?php echo htmlspecialchars($user->getName()); ?>
                                </small>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-folder me-2"></i><?php echo htmlspecialchars(Category::getCategoryById($course->getCategoryID())->getName()); ?>
                                </small>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-users me-2"></i><?php echo $course->getTotalEnrollments(); ?> students
                                </small>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-tags me-2"></i><?php echo htmlspecialchars(implode(', ', $course->getTags())); ?>
                                </small>
                            </div>
                            <p class="card-text"><?php echo substr(htmlspecialchars($course->getDescription()), 0, 100); ?>...</p>
                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editCourseModal<?php echo $course->getID(); ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="delete_course_id" value="<?php echo $course->getID(); ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                    <!-- Edit Course Modal -->
                    <div class="modal fade" id="editCourseModal<?php echo $course->getID(); ?>" tabindex="-1" aria-labelledby="editCourseModalLabel<?php echo $course->getID(); ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editCourseModalLabel<?php echo $course->getID(); ?>">Edit Course</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST">
                                <div class="modal-body">
                                    <input type="hidden" name="edit_course_id" value="<?php echo $course->getID(); ?>">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($course->getTitle()); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($course->getDescription()); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="video_url" class="form-label">Content:Video URL</label>
                                        <input type="url" class="form-control" id="video_url" name="content" value="<?php echo htmlspecialchars($course->getContent()); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select class="form-select" id="category_id" name="category_id" required>
                                            <!-- Populate categories dynamically -->
                                            <?php
                                            $categories = Category::getAllCategories();
                                            foreach ($categories as $category) {
                                                echo '<option value="' . $category->getCategoryId() . '"' . ($category->getCategoryId() == $course->getCategoryID() ? ' selected' : '') . '>' . htmlspecialchars($category->getName()) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tags" class="form-label">Tags</label>
                                        <select class="form-select" id="tags" name="tags[]" multiple required>
                                            <!-- Populate tags dynamically -->
                                            <?php
                                            $tags = Tag::getAllTags();
                                            foreach ($tags as $tag) {
                                                echo '<option value="' . $tag->getTagId() . '"' . (in_array($tag->getTagId(), $course->getTags()) ? ' selected' : '') . '>' . htmlspecialchars($tag->getName()) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCourseModalLabel">Add Course</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="add_course" value="1">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="video_url" class="form-label">Content:Video URL</label>
                                <input type="url" class="form-control" id="video_url" name="content" required>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <?php
                                    $categories = Category::getAllCategories();
                                    foreach ($categories as $category) {
                                        echo '<option value="' . $category->getCategoryId() . '">' . htmlspecialchars($category->getName()) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tags" class="form-label">Tags</label>
                                <select class="form-select" id="tags" name="tags[]" multiple required>
                                    <?php
                                    $tags = Tag::getAllTags();
                                    foreach ($tags as $tag) {
                                        echo '<option value="' . $tag->getTagId() . '">' . htmlspecialchars($tag->getName()) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Course</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>