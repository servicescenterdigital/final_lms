<?php
// templates/student/index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/student"><?= SITE_NAME ?></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/logout">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="/student" class="list-group-item list-group-item-action active">My Courses</a>
                    <a href="/student/certificates" class="list-group-item list-group-item-action">My Certificates</a>
                    <a href="/student/tutoring" class="list-group-item list-group-item-action">1-to-1 Tutoring</a>
                </div>
            </div>
            <div class="col-md-9" id="main-content">
                <?php
                $subpage = $parts[1] ?? 'dashboard';
                switch ($subpage) {
                    case 'course':
                        require_once __DIR__ . '/course_view.php';
                        break;
                    case 'certificates':
                        require_once __DIR__ . '/certificates.php';
                        break;
                    case 'tutoring':
                        require_once __DIR__ . '/tutoring.php';
                        break;
                    default:
                        require_once __DIR__ . '/dashboard.php';
                        break;
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
</body>
</html>
