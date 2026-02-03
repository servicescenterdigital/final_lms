<?php
// templates/platform_admin/index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Platform Admin - <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand" href="/platform-admin">Platform Admin</a>
            <div class="ms-auto"><a href="/logout" class="nav-link text-white">Logout</a></div>
        </div>
    </nav>
    <div class="container mt-4">
        <h1>System Control Panel</h1>
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card p-3 shadow-sm">
                    <h6>Total Revenue</h6>
                    <h3>
                        <?php
                        $db = get_db();
                        echo number_format($db->query("SELECT SUM(amount) FROM payments WHERE status = 'completed'")->fetchColumn()) . ' RWF';
                        ?>
                    </h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 shadow-sm">
                    <h6>Total Users</h6>
                    <h3><?= $db->query("SELECT COUNT(*) FROM users")->fetchColumn() ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 shadow-sm">
                    <h6>Active Courses</h6>
                    <h3><?= $db->query("SELECT COUNT(*) FROM courses WHERE status = 'published'")->fetchColumn() ?></h3>
                </div>
            </div>
        </div>

        <h4 class="mt-5">Audit Logs</h4>
        <table class="table table-sm mt-3">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $logs = $db->query("SELECT a.*, u.name FROM audit_logs a JOIN users u ON a.user_id = u.id ORDER BY a.created_at DESC LIMIT 10")->fetchAll();
                foreach ($logs as $log):
                ?>
                    <tr>
                        <td><?= $log['created_at'] ?></td>
                        <td><?= htmlspecialchars($log['name']) ?></td>
                        <td><?= htmlspecialchars($log['action']) ?></td>
                        <td><?= htmlspecialchars($log['details']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
