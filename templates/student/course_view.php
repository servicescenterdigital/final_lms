<?php
// templates/student/course_view.php
$course_id = $parts[2] ?? null;
if (!$course_id) {
    echo "Course not found";
    return;
}

$db = get_db();
$stmt = $db->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

$stmt = $db->prepare("SELECT * FROM modules WHERE course_id = ? ORDER BY sort_order");
$stmt->execute([$course_id]);
$modules = $stmt->fetchAll();
?>

<div class="row">
    <div class="col-md-4">
        <h4><?= htmlspecialchars($course['title']) ?></h4>
        <div class="accordion" id="courseAccordion">
            <?php foreach ($modules as $m): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading<?= $m['id'] ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $m['id'] ?>">
                            <?= htmlspecialchars($m['title']) ?>
                        </button>
                    </h2>
                    <div id="collapse<?= $m['id'] ?>" class="accordion-collapse collapse" data-bs-parent="#courseAccordion">
                        <div class="accordion-body p-0">
                            <div class="list-group list-group-flush">
                                <?php
                                $stmt = $db->prepare("SELECT * FROM lessons WHERE module_id = ? ORDER BY sort_order");
                                $stmt->execute([$m['id']]);
                                $lessons = $stmt->fetchAll();
                                foreach ($lessons as $l):
                                ?>
                                    <a href="#" class="list-group-item list-group-item-action lesson-link" data-id="<?= $l['id'] ?>" data-type="lesson">
                                        <?= htmlspecialchars($l['title']) ?>
                                    </a>
                                <?php endforeach; ?>
                                
                                <?php
                                $stmt = $db->prepare("SELECT * FROM quizzes WHERE module_id = ?");
                                $stmt->execute([$m['id']]);
                                $quizzes = $stmt->fetchAll();
                                foreach ($quizzes as $q):
                                ?>
                                    <a href="#" class="list-group-item list-group-item-action quiz-link" data-id="<?= $q['id'] ?>" data-type="quiz">
                                        📝 <?= htmlspecialchars($q['title']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-md-8">
        <div id="content-viewer" class="card p-4">
            <h5>Select a lesson to start learning</h5>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contentViewer = document.getElementById('content-viewer');
    const courseId = <?= $course_id ?>;

    document.querySelectorAll('.lesson-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const lessonId = this.dataset.id;
            loadLesson(lessonId);
        });
    });

    document.querySelectorAll('.quiz-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const quizId = this.dataset.id;
            loadQuiz(quizId);
        });
    });

    function loadLesson(id) {
        // In a real app, you'd fetch lesson content via API
        fetch(`/api/lesson/${id}`)
            .then(res => res.json())
            .then(data => {
                contentViewer.innerHTML = `
                    <h3>${data.title}</h3>
                    <div class="mt-3">${data.content}</div>
                    <button class="btn btn-success mt-4" onclick="markCompleted(${id})">Mark as Completed</button>
                `;
            });
    }

    function loadQuiz(id) {
        fetch(`/api/quiz/${id}`)
            .then(res => res.json())
            .then(data => {
                let html = `<h3>${data.title}</h3><form id="quiz-form">`;
                data.questions.forEach(q => {
                    html += `<div class="mb-3"><strong>${q.question_text}</strong><br>`;
                    q.options.forEach(o => {
                        html += `<div class="form-check">
                            <input class="form-check-input" type="radio" name="q${q.id}" value="${o.id}" required>
                            <label class="form-check-label">${o.option_text}</label>
                        </div>`;
                    });
                    html += `</div>`;
                });
                html += `<button type="submit" class="btn btn-primary">Submit Quiz</button></form>`;
                contentViewer.innerHTML = html;

                document.getElementById('quiz-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const answers = {};
                    formData.forEach((value, key) => {
                        answers[key.substring(1)] = value;
                    });
                    submitQuiz(id, answers);
                });
            });
    }

    window.markCompleted = function(lessonId) {
        fetch('/api/course-progress', {
            method: 'POST',
            body: JSON.stringify({ course_id: courseId, lesson_id: lessonId })
        })
        .then(res => res.json())
        .then(data => {
            alert('Progress saved!');
        });
    };

    function submitQuiz(quizId, answers) {
        fetch('/api/quiz-submit', {
            method: 'POST',
            body: JSON.stringify({ quiz_id: quizId, answers: answers })
        })
        .then(res => res.json())
        .then(data => {
            let resHtml = `<h3>Quiz Results</h3>
                <p>Score: ${data.score}%</p>
                <p>Status: ${data.passed ? '<span class="text-success">Passed</span>' : '<span class="text-danger">Failed</span>'}</p>`;
            if (data.certificate_id) {
                resHtml += `<p class="alert alert-success">Congratulations! You earned a certificate: ${data.certificate_id}</p>`;
            }
            contentViewer.innerHTML = resHtml;
        });
    }
});
</script>
