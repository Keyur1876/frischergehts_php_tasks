<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../../config.php';

$data = get_json_input();
$id = (int)($data['id'] ?? 0);
$name = trim((string)($data['name'] ?? ''));
$price = (string)($data['price'] ?? '0.00');
$description = isset($data['description']) ? trim((string)$data['description']) : null;
$categoryIds = $data['category_ids'] ?? [];

if ($id <= 0) json_response(['ok'=>false,'error'=>'Invalid id'], 400);
if ($name === '') json_response(['ok'=>false,'error'=>'Name required'], 422);
if (!is_numeric($price) || (float)$price < 0) json_response(['ok'=>false,'error'=>'Invalid price'], 422);
if (!is_array($categoryIds)) $categoryIds = [];

$pdo = db();

try {
  $pdo->beginTransaction();

  $stmt = $pdo->prepare("
    UPDATE article
    SET name = :name, description = :description, price = :price
    WHERE id = :id
  ");
  $stmt->execute([
    'id' => $id,
    'name' => $name,
    'description' => ($description === '') ? null : $description,
    'price' => $price
  ]);

  // Replace category assignments
  $stmt = $pdo->prepare("DELETE FROM article_category WHERE article_id = :id");
  $stmt->execute(['id' => $id]);

  if (!empty($categoryIds)) {
    $stmt = $pdo->prepare("INSERT INTO article_category (article_id, category_id) VALUES (:aid, :cid)");
    foreach ($categoryIds as $cid) {
      $cid = (int)$cid;
      if ($cid > 0) $stmt->execute(['aid' => $id, 'cid' => $cid]);
    }
  }

  $pdo->commit();
  json_response(['ok'=>true]);
} catch (Throwable $e) {
  $pdo->rollBack();
  json_response(['ok'=>false,'error'=>'Server error'], 500);
}
