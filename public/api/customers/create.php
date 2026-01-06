<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../src/db.php';
require_once __DIR__ . '/../../../src/helpers.php';

$data = get_json_input();

// validate required fields
$errors = require_fields($data, ['first_name', 'last_name']);
if ($errors) {
    json_response(['ok' => false, 'error' => 'Validation failed', 'fields' => $errors], 422);
}

$pdo = db();

$stmt = $pdo->prepare("
  INSERT INTO customer (first_name, last_name, customer_group)
  VALUES (:first_name, :last_name, :customer_group)
");

$stmt->execute([
    'first_name' => trim((string)$data['first_name']),
    'last_name' => trim((string)$data['last_name']),
    'customer_group' => ($data['customer_group'] ?? null) ?: null,
]);

$newId = (int)$pdo->lastInsertId();

json_response(['ok' => true, 'id' => $newId], 201);
