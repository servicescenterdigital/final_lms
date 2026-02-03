<?php
// templates/org_admin/index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Organization Admin - <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-info">
        <div class="container">
            <a class="navbar-brand" href="/org-admin">Org Admin</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/logout">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <h3>Organization Overview</h3>
        <p>Manage your instructors and view organization-wide analytics.</p>
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h5>Total Instructors</h5>
                        <h2>
                            <?php
                            $db = get_db();
                            $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE role = 'instructor' AND org_id = ?");
                            $stmt->execute([$_SESSION['org_id'] ?? 0]);
                            echo $stmt->fetchColumn();
                            ?>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h5>Total Students</h5>
                        <h2>
                            <?php
                            $stmt = $db->prepare("SELECT COUNT(DISTINCT e.user_id) FROM enrollments e JOIN courses c ON e.course_id = c.id WHERE c.org_id = ?");
                            $stmt->execute([$_SESSION['org_id'] ?? 0]);
                            echo $stmt->fetchColumn();
                            ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
