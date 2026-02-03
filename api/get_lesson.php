<?php
// api/get_lesson.php
$lesson_id = $parts[2] ?? null;
if (!$lesson_id) json_response(['error' => 'No lesson ID'], 400);

$db = get_db();
$stmt = $db->prepare("SELECT * FROM lessons WHERE id = ?");
$stmt->execute([$lesson_id]);
$lesson = $stmt->fetch();

if (!$lesson) json_response(['error' => 'Lesson not found'], 404);

json_response($lesson);
