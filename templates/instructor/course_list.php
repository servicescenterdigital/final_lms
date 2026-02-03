<?php
// templates/instructor/course_list.php
$db = get_db();
$stmt = $db->prepare("SELECT * FROM courses WHERE instructor_id = ?");
$stmt->execute([get_logged_in_user_id()]);
$courses = $stmt->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>My Courses</h3>
    <a href="/instructor/ai-generator" class="btn btn-primary">+ Create with AI</a>
</div>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Title</th>
            <th>Price</th>
            <th>Status</th>
            <th>Enrolled</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($courses as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['title']) ?></td>
                <td><?= $c['price'] ?> RWF</td>
                <td><span class="badge bg-<?= $c['status'] === 'published' ? 'success' : 'secondary' ?>"><?= ucfirst($c['status']) ?></span></td>
                <td>
                    <?php
                    $s = $db->prepare("SELECT COUNT(*) FROM enrollments WHERE course_id = ?");
                    $s->execute([$c['id']]);
                    echo $s->fetchColumn();
                    ?>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-primary">Edit</button>
                    <?php if ($c['status'] === 'draft'): ?>
                        <button class="btn btn-sm btn-success">Publish</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
