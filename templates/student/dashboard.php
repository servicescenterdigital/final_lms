<?php
// templates/student/dashboard.php
$db = get_db();
$user_id = get_logged_in_user_id();

$stmt = $db->prepare("SELECT c.*, e.progress, e.status as enrollment_status 
                      FROM courses c 
                      JOIN enrollments e ON c.id = e.course_id 
                      WHERE e.user_id = ?");
$stmt->execute([$user_id]);
$my_courses = $stmt->fetchAll();
?>
<h3>Welcome, <?= htmlspecialchars($_SESSION['name']) ?>!</h3>
<div class="row mt-4">
    <?php if (empty($my_courses)): ?>
        <div class="col-12">
            <div class="alert alert-info">
                You are not enrolled in any courses yet. <a href="/courses">Browse Catalog</a>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($my_courses as $c): ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($c['title']) ?></h5>
                        <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar" style="width: <?= $c['progress'] ?>%;" aria-valuenow="<?= $c['progress'] ?>" aria-valuemin="0" aria-valuemax="100"><?= $c['progress'] ?>%</div>
                        </div>
                        <a href="/student/course/<?= $c['id'] ?>" class="btn btn-primary">Continue Learning</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
