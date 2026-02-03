<?php
// templates/instructor/index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Portal - <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/instructor">Instructor Dashboard</a>
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
                    <a href="/instructor" class="list-group-item list-group-item-action active">My Courses</a>
                    <a href="/instructor/ai-generator" class="list-group-item list-group-item-action">AI Course Generator</a>
                    <a href="/instructor/analytics" class="list-group-item list-group-item-action">Analytics</a>
                </div>
            </div>
            <div class="col-md-9">
                <?php
                $subpage = $parts[1] ?? 'courses';
                switch ($subpage) {
                    case 'ai-generator':
                        require_once __DIR__ . '/ai_generator.php';
                        break;
                    case 'analytics':
                        require_once __DIR__ . '/analytics.php';
                        break;
                    default:
                        require_once __DIR__ . '/course_list.php';
                        break;
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
