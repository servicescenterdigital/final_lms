<?php
// templates/public/courses.php
$courses = get_all_published_courses();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Catalog - <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/"><?= SITE_NAME ?></a>
            <div class="ms-auto">
                <?php if (is_logged_in()): ?>
                    <a href="/<?= str_replace('_', '-', get_user_role()) ?>" class="btn btn-outline-primary">My Dashboard</a>
                <?php else: ?>
                    <a href="/login" class="btn btn-outline-primary">Login</a>
                    <a href="/register" class="btn btn-primary">Join Now</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Our Courses</h2>
        <div class="row mt-4">
            <?php foreach ($courses as $c): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($c['title']) ?></h5>
                            <p class="card-text text-muted"><?= substr(htmlspecialchars($c['description']), 0, 100) ?>...</p>
                            <p class="fw-bold">Price: <?= $c['price'] > 0 ? number_format($c['price']) . ' RWF' : 'Free' ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <a href="/course-details/<?= $c['id'] ?>" class="btn btn-outline-secondary w-100">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
