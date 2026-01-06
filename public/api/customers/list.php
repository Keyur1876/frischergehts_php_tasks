<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../src/db.php';
require_once __DIR__ . '/../../../src/helpers.php';

$pdo = db();

$search = $_GET['search'] ?? '';
$group = $_GET['group'] ?? '';

$sql = "
SELECT
  c.id,
  c.first_name,
  c.last_name,
  c.customer_group,
  c.created_at,
  cc.email,
  cc.phone,
  ca.street,
  ca.zip,
  ca.city
FROM customer c
LEFT JOIN customer_contact cc ON cc.customer_id = c.id
LEFT JOIN customer_address ca ON ca.customer_id = c.id
WHERE 1=1
";

$params = [];

if ($search !== '') {
    $sql .= " AND (c.first_name LIKE :search OR c.last_name LIKE :search OR cc.email LIKE :search)";
    $params['search'] = '%' . $search . '%';
}

if ($group !== '') {
    $sql .= " AND c.customer_group = :grp";
    $params['grp'] = $group;
}

//$sql .= " ORDER BY c.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

json_response([
    'ok' => true,
    'data' => $stmt->fetchAll()
]);

//start in terminal:  php -S localhost:8000 -t .
// test in browser : http://localhost/customer-management-php/api/customers/list.php
// ../list.php?search=keyur
//../list.php?group=A