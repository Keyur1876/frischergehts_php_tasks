$(function () {
  const $tbody = $("#tbody");
  const $status = $("#status");
  const $search = $("#search");
  const $filterCategory = $("#filterCategory");

  const modal = new bootstrap.Modal(document.getElementById("articleModal"));
  const $modalTitle = $("#modalTitle");

  const $id = $("#article_id");
  const $name = $("#article_name");
  const $price = $("#article_price");
  const $desc = $("#article_description");
  const $categoryCheckboxes = $("#categoryCheckboxes");

  let allCategories = []; // cached for rendering checkboxes + filter

  function setStatus(msg) {
    $status.text(msg);
  }

  function escapeHtml(str) {
    return String(str).replace(
      /[&<>"']/g,
      (s) =>
        ({
          "&": "&amp;",
          "<": "&lt;",
          ">": "&gt;",
          '"': "&quot;",
          "'": "&#039;",
        })[s],
    );
  }

  function debounce(fn, ms) {
    clearTimeout(window.__t);
    window.__t = setTimeout(fn, ms);
  }

  function loadCategoriesForUI() {
    return $.get("/api/categories/list.php").then(function (json) {
      if (!json.ok) throw new Error(json.error || "Failed to load categories");
      allCategories = json.data || [];

      // Filter dropdown
      const options = ['<option value="">All categories</option>'].concat(
        allCategories.map(
          (c) => `<option value="${c.id}">${escapeHtml(c.name)}</option>`,
        ),
      );
      $filterCategory.html(options.join(""));

      // Modal checkboxes
      renderCategoryCheckboxes([]);
    });
  }

  function renderCategoryCheckboxes(selectedIds) {
    const set = new Set((selectedIds || []).map(Number));
    const html = allCategories
      .map(
        (c) => `
      <div class="col-md-4">
        <div class="form-check border rounded-2 p-2 bg-light">
          <input class="form-check-input cat-check" type="checkbox" value="${c.id}" id="cat_${c.id}" ${set.has(Number(c.id)) ? "checked" : ""}>
          <label class="form-check-label" for="cat_${c.id}">${escapeHtml(c.name)}</label>
        </div>
      </div>
    `,
      )
      .join("");

    $categoryCheckboxes.html(
      html || `<div class="text-muted">No categories found.</div>`,
    );
  }

  function getSelectedCategoryIds() {
    return $(".cat-check:checked")
      .map(function () {
        return Number($(this).val());
      })
      .get();
  }

  function loadArticles() {
    const search = $search.val().trim();
    const categoryId = $filterCategory.val();

    setStatus("Loading...");
    const params = {};
    if (search) params.search = search;
    if (categoryId) params.category_id = categoryId;

    $.get("/api/articles/list.php", params)
      .done(function (json) {
        if (!json.ok)
          return setStatus("Load failed: " + (json.error || "unknown"));

        const rows = (json.data || [])
          .map(
            (a) => `
          <tr>
            <td>${a.id}</td>
            <td>
              <div class="fw-semibold">${escapeHtml(a.name)}</div>
              <div class="text-muted small">${escapeHtml(a.description || "")}</div>
            </td>
            <td>€ ${escapeHtml(a.price || "0.00")}</td>
            <td>${escapeHtml(a.categories || "")}</td>
            <td class="text-muted">${escapeHtml(a.created_at || "")}</td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-primary" data-action="edit" data-id="${a.id}">Edit</button>
              <button class="btn btn-sm btn-outline-danger" data-action="delete" data-id="${a.id}">Delete</button>
            </td>
          </tr>
        `,
          )
          .join("");

        $tbody.html(
          rows ||
            `<tr><td colspan="6" class="text-muted">No articles.</td></tr>`,
        );
        setStatus(`Loaded ${(json.data || []).length} articles.`);
      })
      .fail(() => setStatus("Request failed (network/server)."));
  }

  function openNew() {
    $modalTitle.text("New Article");
    $id.val("");
    $name.val("");
    $price.val("0.00");
    $desc.val("");
    renderCategoryCheckboxes([]);
    modal.show();
  }

  function openEdit(article) {
    $modalTitle.text(`Edit Article #${article.id}`);
    $id.val(article.id);
    $name.val(article.name || "");
    $price.val(article.price || "0.00");
    $desc.val(article.description || "");
    renderCategoryCheckboxes(article.category_ids || []);
    modal.show();
  }

  function getArticle(id) {
    return $.get("/api/articles/get.php", { id }).then(function (json) {
      if (!json.ok) throw new Error(json.error || "Failed to load article");
      return json.data;
    });
  }

  function saveArticle(payload, isUpdate) {
    const url = isUpdate
      ? "/api/articles/update.php"
      : "/api/articles/create.php";
    return $.ajax({
      url,
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify(payload),
    });
  }

  function deleteArticle(id) {
    return $.ajax({
      url: "/api/articles/delete.php",
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify({ id }),
    });
  }

  $("#btnNew").on("click", openNew);
  $("#btnReload").on("click", loadArticles);

  $search.on("input", function () {
    debounce(loadArticles, 250);
  });
  $filterCategory.on("change", loadArticles);

  $tbody.on("click", "button[data-action]", function () {
    const action = $(this).data("action");
    const id = Number($(this).data("id"));

    if (action === "delete") {
      if (!confirm("Really delete this article?")) return;
      setStatus("Deleting...");
      deleteArticle(id)
        .done((json) => {
          if (!json.ok)
            return setStatus("Delete failed: " + (json.error || "unknown"));
          setStatus("Deleted.");
          loadArticles();
        })
        .fail(() => setStatus("Request failed (network/server)."));
    }

    if (action === "edit") {
      setStatus("Loading article...");
      getArticle(id)
        .then(openEdit)
        .then(() => setStatus("Ready."))
        .catch((err) => setStatus(err.message));
    }
  });

  $("#articleForm").on("submit", function (e) {
    e.preventDefault();

    const id = $id.val() ? Number($id.val()) : null;
    const name = $name.val().trim();
    const price = $price.val(); // keep as string for DECIMAL
    const description = $desc.val().trim() || null;
    const category_ids = getSelectedCategoryIds();

    if (!name) return setStatus("Name is required.");
    if (price === "" || Number(price) < 0)
      return setStatus("Price must be >= 0.");

    setStatus("Saving...");
    const payload = { name, price, description, category_ids };
    if (id) payload.id = id;

    saveArticle(payload, !!id)
      .done(function (json) {
        if (!json.ok)
          return setStatus("Save failed: " + (json.error || "unknown"));
        modal.hide();
        setStatus(id ? "Updated." : "Created.");
        loadArticles();
      })
      .fail(() => setStatus("Request failed (network/server)."));
  });

  // init
  loadCategoriesForUI()
    .then(loadArticles)
    .catch((err) => setStatus(err.message));
});
