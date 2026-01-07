<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../src/db.php';
require_once __DIR__ . '/../../../src/helpers.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) json_response(['ok' => false, 'error' => 'Invalid id'], 400);

$pdo = db();

$stmt = $pdo->prepare("
    SELECT
      c.id, c.first_name, c.last_name, c.customer_group, c.created_at,
      cc.email, cc.phone,
      ca.street, ca.zip, ca.city
    FROM customer c
    LEFT JOIN customer_contact cc ON cc.customer_id = c.id
    LEFT JOIN customer_address ca ON ca.customer_id = c.id
    WHERE c.id = :id
    LIMIT 1
");
$stmt->execute(['id' => $id]);

$row = $stmt->fetch();
if (!$row) json_response(['ok' => false, 'error' => 'Not found'], 404);

json_response(['ok' => true, 'data' => $row]);
