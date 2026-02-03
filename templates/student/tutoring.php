<?php
// templates/student/tutoring.php
?>
<h3>1-to-1 Tutoring Sessions</h3>
<p>Book a private session with an expert tutor.</p>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Book a New Session</h5>
                <form id="book-session-form">
                    <div class="mb-3">
                        <label class="form-label">Select Tutor</label>
                        <select class="form-select" name="tutor_id" required>
                            <?php
                            $db = get_db();
                            $tutors = $db->query("SELECT id, name FROM users WHERE role = 'instructor'")->fetchAll();
                            foreach ($tutors as $t):
                            ?>
                                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date and Time</label>
                        <input type="datetime-local" class="form-control" name="scheduled_at" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Duration (Minutes)</label>
                        <select class="form-select" name="duration" required>
                            <option value="30">30 Minutes</option>
                            <option value="60">60 Minutes</option>
                            <option value="90">90 Minutes</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Book Session</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Your Sessions</h5>
                <div id="session-list">
                    <?php
                    $stmt = $db->prepare("SELECT s.*, u.name as tutor_name FROM tutoring_sessions s JOIN users u ON s.tutor_name = u.id WHERE s.student_id = ? ORDER BY s.scheduled_at DESC");
                    // Wait, join on tutor_id
                    $stmt = $db->prepare("SELECT s.*, u.name as tutor_name FROM tutoring_sessions s JOIN users u ON s.tutor_id = u.id WHERE s.student_id = ? ORDER BY s.scheduled_at DESC");
                    $stmt->execute([get_logged_in_user_id()]);
                    $sessions = $stmt->fetchAll();
                    if (empty($sessions)):
                    ?>
                        <p class="text-muted">No sessions booked yet.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($sessions as $s): ?>
                                <li class="list-group-item">
                                    <strong><?= htmlspecialchars($s['tutor_name']) ?></strong><br>
                                    <?= $s['scheduled_at'] ?> (<?= $s['duration_minutes'] ?> min)<br>
                                    <span class="badge bg-<?= $s['status'] === 'confirmed' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($s['status']) ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
