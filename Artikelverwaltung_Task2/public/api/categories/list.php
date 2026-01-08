<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../../config.php';

$pdo = db();

$stmt = $pdo->query("SELECT id, name, created_at FROM category");

json_response([
    'ok' => true,
    'data' => $stmt->fetchAll(),
]);
