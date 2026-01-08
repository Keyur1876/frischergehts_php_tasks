<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../../config.php';

$data = get_json_input();
$baseId = (int)($data['base_category_id'] ?? 0);
$addonIds = $data['addon_category_ids'] ?? [];

if ($baseId <= 0) json_response(['ok'=>false,'error'=>'Invalid base_category_id'], 400);
if (!is_array($addonIds)) json_response(['ok'=>false,'error'=>'addon_category_ids must be an array'], 422);

$pdo = db();

try {
  $pdo->beginTransaction();

  // delete all for base
  $stmt = $pdo->prepare("DELETE FROM category_compatibility WHERE base_category_id = :base");
  $stmt->execute(['base' => $baseId]);

  // insert selected
  $ins = $pdo->prepare("
    INSERT INTO category_compatibility (base_category_id, addon_category_id)
    VALUES (:base, :addon)
  ");

  foreach ($addonIds as $aid) {
    $aid = (int)$aid;
    if ($aid <= 0) continue;
    if ($aid === $baseId) continue; // also matches your CHECK constraint
    $ins->execute(['base' => $baseId, 'addon' => $aid]);
  }

  $pdo->commit();
  json_response(['ok'=>true]);
} catch (Throwable $e) {
  $pdo->rollBack();
  json_response(['ok'=>false,'error'=>'Server error'], 500);
}