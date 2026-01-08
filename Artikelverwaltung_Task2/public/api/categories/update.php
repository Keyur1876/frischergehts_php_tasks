<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../../config.php';

$data = get_json_input();
$id = (int)($data['id'] ?? 0);
$name = trim((string)($data['name'] ?? ''));

if ($id <= 0) json_response(['ok'=>false,'error'=>'Invalid id'], 400);
if ($name === '') json_response(['ok'=>false,'error'=>'Name required'], 422);

$pdo = db();
$stmt = $pdo->prepare("UPDATE category SET name = :name WHERE id = :id");
$stmt->execute(['name' => $name, 'id' => $id]);

json_response(['ok'=>true]);