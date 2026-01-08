<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../../config.php';

$baseId = (int)($_GET['base_category_id'] ?? 0);
if ($baseId <= 0) json_response(['ok'=>false,'error'=>'Invalid base_category_id'], 400);

$pdo = db();

$stmt = $pdo->prepare("
  SELECT addon_category_id
  FROM category_compatibility
  WHERE base_category_id = :base
  ORDER BY addon_category_id
");
$stmt->execute(['base' => $baseId]);

$ids = array_map(fn($r) => (int)$r['addon_category_id'], $stmt->fetchAll());

json_response(['ok'=>true, 'data'=>['addon_category_ids'=>$ids]]);
