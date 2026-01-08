<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../../config.php';

$data = get_json_input();
$name = trim((string)($data['name'] ?? ''));

if ($name === '') json_response(['ok'=>false,'error'=>'Name required'], 422);

$pdo = db();

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO category (name) VALUES (:name)");
    $stmt -> execute(['name' => $name]);

    $pdo->commit();
    json_response(['ok'=>true,'id'=>$articleId], 201); 
}catch (Throwable $e) {
    $pdo->rollBack();
    json_response(['ok'=>false,'error'=>'Server error'], 500);
}