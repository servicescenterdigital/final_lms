<?php
// api/import_course.php

if (!has_role('instructor')) {
    json_response(['error' => 'Permission denied'], 403);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    json_response(['error' => 'Invalid JSON'], 400);
}

// Strict Validation
$errors = validate_course_schema($data);
if (!empty($errors)) {
    json_response(['error' => 'Schema Validation Failed', 'details' => $errors], 400);
}

$db = get_db();
try {
    $db->beginTransaction();

    $stmt = $db->prepare("INSERT INTO courses (title, description, price, instructor_id, org_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['title'],
        $data['description'] ?? '',
        $data['price'] ?? 0,
        get_logged_in_user_id(),
        $_SESSION['org_id'] ?? null
    ]);
    $course_id = $db->lastInsertId();

    foreach ($data['modules'] as $m_idx => $m) {
        $stmt = $db->prepare("INSERT INTO modules (course_id, title, sort_order) VALUES (?, ?, ?)");
        $stmt->execute([$course_id, $m['title'], $m_idx]);
        $module_id = $db->lastInsertId();

        if (!empty($m['lessons'])) {
            foreach ($m['lessons'] as $l_idx => $l) {
                $stmt = $db->prepare("INSERT INTO lessons (module_id, title, content, type, sort_order) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $module_id,
                    $l['title'],
                    $l['content'] ?? '',
                    $l['type'] ?? 'text',
                    $l_idx
                ]);
            }
        }

        if (!empty($m['quizzes'])) {
            foreach ($m['quizzes'] as $q) {
                $stmt = $db->prepare("INSERT INTO quizzes (course_id, module_id, title, pass_score) VALUES (?, ?, ?, ?)");
                $stmt->execute([
                    $course_id,
                    $module_id,
                    $q['title'],
                    $q['pass_score'] ?? 70
                ]);
                $quiz_id = $db->lastInsertId();

                if (!empty($q['questions'])) {
                    foreach ($q['questions'] as $qn) {
                        $stmt = $db->prepare("INSERT INTO questions (quiz_id, question_text, type) VALUES (?, ?, ?)");
                        $stmt->execute([
                            $quiz_id,
                            $qn['question'],
                            $qn['type'] ?? 'multiple_choice'
                        ]);
                        $question_id = $db->lastInsertId();

                        foreach ($qn['options'] as $opt) {
                            $stmt = $db->prepare("INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)");
                            $stmt->execute([
                                $question_id,
                                $opt['text'],
                                $opt['is_correct'] ? 1 : 0
                            ]);
                        }
                    }
                }
            }
        }
    }

    $db->commit();
    json_response(['success' => true, 'course_id' => $course_id]);
} catch (Exception $e) {
    $db->rollBack();
    json_response(['error' => 'Import failed: ' . $e->getMessage()], 500);
}
