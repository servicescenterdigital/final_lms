<?php
// api/router.php

$action = $parts[1] ?? '';

header('Content-Type: application/json');

if (!is_logged_in() && !in_array($action, ['payment-callback'])) {
    json_response(['error' => 'Unauthorized'], 401);
}

switch ($action) {
    case 'course-progress':
        require_once __DIR__ . '/course_progress.php';
        break;
    case 'lesson':
        require_once __DIR__ . '/get_lesson.php';
        break;
    case 'quiz':
        require_once __DIR__ . '/get_quiz.php';
        break;
    case 'quiz-submit':
        require_once __DIR__ . '/quiz_submit.php';
        break;
    case 'generate-schema':
        json_response(generate_ai_course_schema());
        break;
    case 'import-course':
        require_once __DIR__ . '/import_course.php';
        break;
    case 'payment-init':
        require_once __DIR__ . '/payment_init.php';
        break;
    case 'payment-callback':
        require_once __DIR__ . '/payment_callback.php';
        break;
    default:
        json_response(['error' => 'API Endpoint not found'], 404);
}
