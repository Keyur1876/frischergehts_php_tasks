<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../../config.php';

$data = get_json_input();
$name = trim((string)($data['name'] ?? ''));
$price = (string)($data['price'] ?? '0.00');
$description = isset($data['description']) ? trim((string)$data['description']) : null;
$categoryIds = $data['category_ids'] ?? [];

if ($name === '') json_response(['ok'=>false,'error'=>'Name required'], 422);
if (!is_numeric($price) || (float)$price < 0) json_response(['ok'=>false,'error'=>'Invalid price'], 422);
if (!is_array($categoryIds)) $categoryIds = [];

$pdo = db();

try {
  $pdo->beginTransaction();

  $stmt = $pdo->prepare("INSERT INTO article (name, description, price) VALUES (:name, :description, :price)");
  $stmt->execute([
    'name' => $name,
    'description' => ($description === '') ? null : $description,
    'price' => $price
  ]);

  $articleId = (int)$pdo->lastInsertId();

  if (!empty($categoryIds)) {
    $stmt = $pdo->prepare("INSERT INTO article_category (article_id, category_id) VALUES (:aid, :cid)");
    foreach ($categoryIds as $cid) {
      $cid = (int)$cid;
      if ($cid > 0) $stmt->execute(['aid' => $articleId, 'cid' => $cid]);
    }
  }

  $pdo->commit();
  json_response(['ok'=>true,'id'=>$articleId], 201);
} catch (Throwable $e) {
  $pdo->rollBack();
  json_response(['ok'=>false,'error'=>'Server error'], 500);
}