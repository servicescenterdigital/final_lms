<?php
// templates/public/home.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= SITE_NAME ?> - Modern Learning Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero { background: #f8f9fa; padding: 100px 0; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/"><?= SITE_NAME ?></a>
            <div class="ms-auto">
                <a href="/courses" class="btn btn-link text-dark text-decoration-none">Courses</a>
                <a href="/login" class="btn btn-outline-primary ms-2">Login</a>
                <a href="/register" class="btn btn-primary ms-2">Get Started</a>
            </div>
        </div>
    </nav>

    <header class="hero text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Master New Skills with AI-Generated Courses</h1>
            <p class="lead text-muted">A production-ready LMS with automated assessments, certificates, and seamless payments.</p>
            <div class="mt-4">
                <a href="#programs" class="btn btn-primary btn-lg px-5">Browse Courses</a>
                <a href="/verify-certificate" class="btn btn-outline-secondary btn-lg px-5 ms-3">Verify Certificate</a>
            </div>
        </div>
    </header>

    <section id="programs" class="courses section py-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-12">
                    <div class="section-title bg text-center mb-5">
                        <h2>Our <span>Programs</span></h2>
                        <p>Explore our skill-building courses and unlock real job opportunities with Digital Services Center. Learn, commit, and grow with SchoolDream+.</p>
                        <div class="icon"><i class="fa fa-clone"></i></div>
                    </div>
                </div>
            </div>
            <div class="row" id="course-list">
                <?php
                $db = get_db();
                $stmt = $db->query("SELECT * FROM courses WHERE status = 'published' ORDER BY created_at DESC LIMIT 6");
                $courses = $stmt->fetchAll();
                
                foreach ($courses as $course): 
                ?>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="single-course card mb-4">
                        <div class="course-body card-body mt-3">
                            <div class="name-price d-flex justify-content-between mb-3">
                                <span class="price badge bg-success"><?= number_format($course['price']) ?> RWF</span>
                                <span><i class="fa fa-users"></i> Enrolled</span>
                            </div>
                            <h4 class="c-title card-title"><a href="/course-details?id=<?= $course['id'] ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($course['title']) ?></a></h4>
                            <p class="card-text" style="min-height: 70px;"><?= htmlspecialchars(substr($course['description'], 0, 100)) ?>...</p>
                            <a href="/course-details?id=<?= $course['id'] ?>" class="btn btn-primary w-100">Explore Course</a>
                        </div>
                        <div class="course-meta card-footer bg-white">
                            <ul class="rattings list-inline mb-0">
                                <li class="list-inline-item"><i class="fa fa-star text-warning"></i></li>
                                <li class="list-inline-item"><i class="fa fa-star text-warning"></i></li>
                                <li class="list-inline-item"><i class="fa fa-star text-warning"></i></li>
                                <li class="list-inline-item"><i class="fa fa-star text-warning"></i></li>
                                <li class="list-inline-item"><i class="fa fa-star text-warning"></i></li>
                                <li class="point list-inline-item"><span>5.0</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <footer class="py-4 bg-light border-top mt-5">
        <div class="container text-center">
            <p class="text-muted mb-0">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
