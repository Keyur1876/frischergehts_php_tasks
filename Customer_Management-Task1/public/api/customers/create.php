<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../src/db.php';
require_once __DIR__ . '/../../../src/helpers.php';

$data = get_json_input();

$errors = require_fields($data, ['first_name', 'last_name']);
if ($errors) {
    json_response(['ok' => false, 'error' => 'Validation failed', 'fields' => $errors], 422);
}

$pdo = db();

try {
    $pdo->beginTransaction();

    // 1) customer
    $stmt = $pdo->prepare("
        INSERT INTO customer (first_name, last_name, customer_group)
        VALUES (:first_name, :last_name, :customer_group)
    ");
    $stmt->execute([
        'first_name' => trim((string)$data['first_name']),
        'last_name' => trim((string)$data['last_name']),
        'customer_group' => ($data['customer_group'] ?? null) ?: null,
    ]);

    $customerId = (int)$pdo->lastInsertId();

    // contact
    $stmt = $pdo->prepare("
        INSERT INTO customer_contact (customer_id, email, phone)
        VALUES (:customer_id, :email, :phone)
    ");
    $stmt->execute([
        'customer_id' => $customerId,
        'email' => ($data['email'] ?? null) ?: null,
        'phone' => ($data['phone'] ?? null) ?: null,
    ]);

    // address
    $stmt = $pdo->prepare("
        INSERT INTO customer_address (customer_id, street, zip, city)
        VALUES (:customer_id, :street, :zip, :city)
    ");
    $stmt->execute([
        'customer_id' => $customerId,
        'street' => ($data['street'] ?? null) ?: null,
        'zip' => ($data['zip'] ?? null) ?: null,
        'city' => ($data['city'] ?? null) ?: null,
    ]);

    $pdo->commit();
    json_response(['ok' => true, 'id' => $customerId], 201);

} catch (Throwable $e) {
    $pdo->rollBack();
    json_response(['ok' => false, 'error' => 'Server error'], 500);
}
