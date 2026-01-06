<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../src/db.php';
require_once __DIR__ . '/../../../src/helpers.php';

$data = get_json_input();

$id = (int)($data['id'] ?? 0);
if ($id <= 0) json_response(['ok' => false, 'error' => 'Invalid id'], 400);

$errors = require_fields($data, ['first_name', 'last_name']);
if ($errors) {
    json_response(['ok' => false, 'error' => 'Validation failed', 'fields' => $errors], 422);
}

$pdo = db();

$stmt = $pdo->prepare("
  UPDATE customer
  SET first_name = :first_name,
      last_name = :last_name,
      customer_group = :customer_group
  WHERE id = :id
");

$stmt->execute([
    'id' => $id,
    'first_name' => trim((string)$data['first_name']),
    'last_name' => trim((string)$data['last_name']),
    'customer_group' => ($data['customer_group'] ?? null) ?: null,
]);

json_response(['ok' => true]);
