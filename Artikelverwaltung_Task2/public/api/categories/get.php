<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../../config.php';

$data = get_json_input();
$name = trim((string)($data['name'] ?? ''));

if ($name === '') {
    json_response(['ok' => false, 'error' => 'Name is required'], 422);
}

$pdo = db();

try {
    $stmt = $pdo->prepare("INSERT INTO category (name) VALUES (:name)");
    $stmt->execute(['name' => $name]);

    json_response(['ok' => true, 'id' => (int)$pdo->lastInsertId()], 201);
} catch (Throwable $e) {
    // likely duplicate name because of UNIQUE constraint
    json_response(['ok' => false, 'error' => 'Could not create category (maybe duplicate?)'], 400);
}
