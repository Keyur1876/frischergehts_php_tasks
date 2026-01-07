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

try {
    $pdo->beginTransaction();

    // 1) Update customer (main table)
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

    // 2) Update contact
    $stmt = $pdo->prepare("
        UPDATE customer_contact
        SET email = :email,
            phone = :phone
        WHERE customer_id = :customer_id
    ");
    $stmt->execute([
        'customer_id' => $id,
        'email' => ($data['email'] ?? null) ?: null,
        'phone' => ($data['phone'] ?? null) ?: null,
    ]);

    // 3) Update address
    $stmt = $pdo->prepare("
        UPDATE customer_address
        SET street = :street,
            zip = :zip,
            city = :city
        WHERE customer_id = :customer_id
    ");
    $stmt->execute([
        'customer_id' => $id,
        'street' => ($data['street'] ?? null) ?: null,
        'zip' => ($data['zip'] ?? null) ?: null,
        'city' => ($data['city'] ?? null) ?: null,
    ]);

    $pdo->commit();
    json_response(['ok' => true]);

} catch (Throwable $e) {
    $pdo->rollBack();
    json_response(['ok' => false, 'error' => 'Server error'], 500);
}
