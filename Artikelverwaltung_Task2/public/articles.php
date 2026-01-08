<?php declare(strict_types=1); ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Articles</title>
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
      <a class="nav-link active" href="/articles.php">Articles</a>
      <a class="nav-link" href="/compatibility.php">Compatibility</a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Articles</h1>
    <button class="btn btn-primary" id="btnNew">+ New Article</button>
  </div>

  <div class="row g-2 mb-3">
    <div class="col-md-6">
      <input class="form-control" id="search" placeholder="Search by name..." />
    </div>
    <div class="col-md-4">
      <select class="form-select" id="filterCategory">
        <option value="">All categories</option>
      </select>
    </div>
    <div class="col-md-2">
      <button class="btn btn-outline-secondary w-100" id="btnReload">Reload</button>
    </div>
  </div>

  <div id="status" class="text-muted mb-2"></div>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th style="width:80px;">ID</th>
            <th>Name</th>
            <th style="width:140px;">Price</th>
            <th>Categories</th>
            <th style="width:220px;">Created</th>
            <th class="text-end" style="width:200px;">Actions</th>
          </tr>
        </thead>
        <tbody id="tbody"></tbody>
      </table>
    </div>
  </div>
</div>

<!-- Article Modal -->
<div class="modal fade" id="articleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" id="articleForm">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">New Article</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="article_id">

        <div class="row g-2">
          <div class="col-md-8">
            <label class="form-label">Name</label>
            <input class="form-control" id="article_name" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Price (€)</label>
            <input class="form-control" id="article_price" type="number" step="0.01" min="0" value="0.00" required>
          </div>
          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea class="form-control" id="article_description" rows="3"></textarea>
          </div>
        </div>

        <hr class="my-3">

        <div>
          <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-2">Assign Categories</h6>
            <small class="text-muted">Select one or multiple</small>
          </div>
          <div id="categoryCheckboxes" class="row g-2"></div>
        </div>
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
<script src="/assets/articles.js"></script>
</body>
</html>
