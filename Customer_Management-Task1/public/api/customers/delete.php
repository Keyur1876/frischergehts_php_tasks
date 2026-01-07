<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../../config.php';

$data = get_json_input();

$id = (int)($data['id'] ?? 0);
if ($id <= 0) json_response(['ok' => false, 'error' => 'Invalid id'], 400);

$pdo = db();

$stmt = $pdo->prepare("DELETE FROM customer WHERE id = :id");
$stmt->execute(['id' => $id]);

json_response(['ok' => true]);
