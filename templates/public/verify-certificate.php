<?php
// templates/public/verify-certificate.php
$cert_id = $_GET['id'] ?? '';
$cert = null;

if ($cert_id) {
    $db = get_db();
    $stmt = $db->prepare("SELECT c.*, u.name as student_name, crs.title as course_name 
                          FROM certificates c 
                          JOIN users u ON c.user_id = u.id 
                          JOIN courses crs ON c.course_id = crs.id 
                          WHERE c.certificate_id = ?");
    $stmt->execute([$cert_id]);
    $cert = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Certificate - <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5 text-center">
        <h2>Certificate Verification</h2>
        <div class="row justify-content-center mt-4">
            <div class="col-md-6">
                <form action="/verify-certificate" method="GET" class="input-group mb-3">
                    <input type="text" name="id" class="form-control" placeholder="Enter Certificate ID" value="<?= htmlspecialchars($cert_id) ?>">
                    <button class="btn btn-primary" type="submit">Verify</button>
                </form>

                <?php if ($cert_id): ?>
                    <?php if ($cert): ?>
                        <div class="card border-success mt-4">
                            <div class="card-body">
                                <h4 class="text-success">✅ Valid Certificate</h4>
                                <hr>
                                <p><strong>Student:</strong> <?= htmlspecialchars($cert['student_name']) ?></p>
                                <p><strong>Course:</strong> <?= htmlspecialchars($cert['course_name']) ?></p>
                                <p><strong>Issued on:</strong> <?= date('F j, Y', strtotime($cert['issued_at'])) ?></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger mt-4">
                            ❌ Invalid Certificate ID. Please check and try again.
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
