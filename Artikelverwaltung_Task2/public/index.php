<?php declare(strict_types=1); ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Article Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/app.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="/">Lieferdienst Admin</a>
    <div class="navbar-nav">
      <a class="nav-link" href="/categories.php">Categories</a>
      <a class="nav-link" href="/articles.php">Articles</a>
      <a class="nav-link" href="/compatibility.php">Category Compatibility</a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <div class="p-4 bg-white border rounded-3">
    <h1 class="h4 mb-2">Artikelverwaltung</h1>
    <p class="text-muted mb-0">
      Manage categories, articles, assignments, and allowed category combinations.
    </p>
  </div>

  <div class="row g-3 mt-2">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h2 class="h6">Categories</h2>
          <p class="text-muted">Create/edit/delete categories.</p>
          <a class="btn btn-outline-primary btn-sm" href="/categories.php">Open</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h2 class="h6">Articles</h2>
          <p class="text-muted">CRUD articles + assign categories.</p>
          <a class="btn btn-outline-primary btn-sm" href="/articles.php">Open</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h2 class="h6">Compatibility</h2>
          <p class="text-muted">Define which categories can be combined.</p>
          <a class="btn btn-outline-primary btn-sm" href="/compatibility.php">Open</a>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>