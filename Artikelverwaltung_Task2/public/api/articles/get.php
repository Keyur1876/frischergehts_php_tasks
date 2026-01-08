<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../../config.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) json_response(['ok'=>false,'error'=>'Invalid id'], 400);

$pdo = db();

// get article
$stmt = $pdo->prepare("SELECT id, name, description, price, created_at FROM article WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $id]);
$article = $stmt->fetch();
if (!$article) json_response(['ok'=>false,'error'=>'Not found'], 404);

// get assigned categories
$stmt = $pdo->prepare("SELECT category_id FROM article_category WHERE article_id = :id");
$stmt->execute(['id' => $id]);
$ids = array_map(fn($r) => (int)$r['category_id'], $stmt->fetchAll());

$article['category_ids'] = $ids;

json_response(['ok'=>true,'data'=>$article]);