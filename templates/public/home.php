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
                <a href="/courses" class="btn btn-primary btn-lg px-5">Browse Courses</a>
                <a href="/verify-certificate" class="btn btn-outline-secondary btn-lg px-5 ms-3">Verify Certificate</a>
            </div>
        </div>
    </header>

    <section class="py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4">
                    <h3>Paid & Free</h3>
                    <p>Flexible enrollment options for everyone.</p>
                </div>
                <div class="col-md-4">
                    <h3>Automated Grading</h3>
                    <p>Instant feedback on quizzes and exams.</p>
                </div>
                <div class="col-md-4">
                    <h3>Certifications</h3>
                    <p>Earn verified certificates upon completion.</p>
                </div>
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
