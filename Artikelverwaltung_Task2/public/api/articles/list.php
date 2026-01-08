<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../../config.php';

$pdo = db();

$search = trim((string)($_GET['search'] ?? ''));
$categoryId = (int)($_GET['category_id'] ?? 0);

// GROUP_CONCAT to display categories nicely
$sql = "
SELECT
  a.id,
  a.name,
  a.description,
  a.price,
  a.created_at,
  COALESCE(GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR ', '), '') AS categories
FROM article a
LEFT JOIN article_category ac ON ac.article_id = a.id
LEFT JOIN category c ON c.id = ac.category_id
WHERE 1=1
";

$params = [];

if ($search !== '') {
  $sql .= " AND a.name LIKE :search";
  $params['search'] = '%' . $search . '%';
}

if ($categoryId > 0) {
  // filter: article must have this category
  $sql .= " AND EXISTS (
    SELECT 1 FROM article_category ac2
    WHERE ac2.article_id = a.id AND ac2.category_id = :cat
  )";
  $params['cat'] = $categoryId;
}

$sql .= " GROUP BY a.id ORDER BY a.id";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

json_response(['ok' => true, 'data' => $stmt->fetchAll()]);
