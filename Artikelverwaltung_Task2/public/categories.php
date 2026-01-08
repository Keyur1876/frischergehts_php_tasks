<?php declare(strict_types=1); ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Categories</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/app.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="/">Lieferdienst Admin</a>
    <div class="navbar-nav">
      <a class="nav-link active" href="/categories.php">Categories</a>
      <a class="nav-link" href="/articles.php">Articles</a>
      <a class="nav-link" href="/compatibility.php">Compatibility</a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Categories</h1>
    <button class="btn btn-primary" id="btnNew">+ New Category</button>
  </div>

  <div id="status" class="text-muted mb-2"></div>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th style="width:80px;">ID</th>
            <th>Name</th>
            <th style="width:220px;">Created</th>
            <th class="text-end" style="width:180px;">Actions</th>
          </tr>
        </thead>
        <tbody id="tbody"></tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="catModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="catForm">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">New Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="cat_id">
        <label class="form-label">Name</label>
        <input class="form-control" id="name" required>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" type="submit">Save</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/categories.js"></script>
</body>
</html>
