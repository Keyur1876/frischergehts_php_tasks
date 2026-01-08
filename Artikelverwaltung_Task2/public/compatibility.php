<?php declare(strict_types=1); ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Category Compatibility</title>
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
      <a class="nav-link active" href="/compatibility.php">Compatibility</a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Category Compatibility</h1>
    <button class="btn btn-primary" id="btnSave" disabled>Save</button>
  </div>

  <p class="text-muted">
    Select a <strong>base category</strong> (e.g. Pizza) and choose which <strong>addon categories</strong>
    (e.g. Toppings) are allowed.
  </p>

  <div class="row g-3">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <label class="form-label">Base category</label>
          <select class="form-select" id="baseCategory">
            <option value="">Select...</option>
          </select>
          <div class="small text-muted mt-2" id="status"></div>
        </div>
      </div>
    </div>

    <div class="col-md-8">
      <div class="card">
        <div class="card-body">
          <h2 class="h6">Allowed addon categories</h2>
          <div id="addonCheckboxes" class="row g-2"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/compatibility.js"></script>
</body>
</html>