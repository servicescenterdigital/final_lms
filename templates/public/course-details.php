<?php
// templates/public/course-details.php
$course_id = $parts[1] ?? null;
$course = get_course_by_id($course_id);

if (!$course) {
    echo "Course not found";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($course['title']) ?> - <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <h1><?= htmlspecialchars($course['title']) ?></h1>
                <p class="lead mt-3"><?= nl2br(htmlspecialchars($course['description'])) ?></p>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h3 class="text-center"><?= $course['price'] > 0 ? number_format($course['price']) . ' RWF' : 'Free' ?></h3>
                    <?php if (!is_logged_in()): ?>
                        <a href="/login" class="btn btn-primary btn-lg w-100 mt-3">Login to Enroll</a>
                    <?php elseif (is_enrolled(get_logged_in_user_id(), $course['id'])): ?>
                        <a href="/student/course/<?= $course['id'] ?>" class="btn btn-success btn-lg w-100 mt-3">Continue Learning</a>
                    <?php else: ?>
                        <?php if ($course['price'] > 0): ?>
                            <div class="mt-3">
                                <label class="form-label">Phone Number for Payment (MOMO/Airtel)</label>
                                <input type="text" id="phone" class="form-control" placeholder="078XXXXXXX">
                                <button onclick="startPayment()" class="btn btn-primary btn-lg w-100 mt-2">Pay & Enroll</button>
                            </div>
                        <?php else: ?>
                            <button onclick="enrollFree()" class="btn btn-primary btn-lg w-100 mt-3">Enroll for Free</button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    function startPayment() {
        const phone = document.getElementById('phone').value;
        if (!phone) { alert('Please enter phone number'); return; }

        fetch('/api/payment-init', {
            method: 'POST',
            body: JSON.stringify({
                course_id: <?= $course['id'] ?>,
                phone: phone
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Payment initiated! Please check your phone for the prompt.');
                // In a real app, you might poll for status or redirect to a pending page
            } else {
                alert('Error: ' + data.error);
            }
        });
    }

    function enrollFree() {
        fetch('/api/payment-init', {
            method: 'POST',
            body: JSON.stringify({ course_id: <?= $course['id'] ?>, phone: '0000000000' })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/student/course/<?= $course['id'] ?>';
            }
        });
    }
    </script>
</body>
</html>
