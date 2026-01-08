$(function () {
  const $tbody = $("#tbody");
  const $status = $("#status");
  const $name = $("#name");
  const $id = $("#cat_id");

  const modal = new bootstrap.Modal(document.getElementById("catModal"));
  const $modalTitle = $("#modalTitle");

  function setStatus(msg) { $status.text(msg); }

  function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, s => ({
      "&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#039;"
    }[s]));
  }

  function openNew() {
    $modalTitle.text("New Category");
    $id.val("");
    $name.val("");
    modal.show();
  }

  function openEdit(cat) {
    $modalTitle.text(`Edit Category #${cat.id}`);
    $id.val(cat.id);
    $name.val(cat.name);
    modal.show();
  }

  function loadCategories() {
    setStatus("Loading...");
    $.get("/api/categories/list.php")
      .done(function (json) {
        if (!json.ok) return setStatus("Load failed: " + (json.error || "unknown"));

        const rows = json.data.map(c => `
          <tr>
            <td>${c.id}</td>
            <td>${escapeHtml(c.name)}</td>
            <td class="text-muted">${escapeHtml(c.created_at || "")}</td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-primary" data-action="edit" data-id="${c.id}">Edit</button>
              <button class="btn btn-sm btn-outline-danger" data-action="delete" data-id="${c.id}">Delete</button>
            </td>
          </tr>
        `).join("");

        $tbody.html(rows);
        setStatus(`Loaded ${json.data.length} categories.`);
      })
      .fail(() => setStatus("Request failed (network/server)."));
  }

  $("#btnNew").on("click", openNew);

  $("#catForm").on("submit", function (e) {
    e.preventDefault();
    const name = $name.val().trim();
    const id = $id.val() ? Number($id.val()) : null;

    if (!name) return;

    setStatus("Saving...");
    const url = id ? "/api/categories/update.php" : "/api/categories/create.php";
    const payload = id ? { id, name } : { name };

    $.ajax({
      url,
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify(payload)
    }).done(function (json) {
      if (!json.ok) return setStatus("Save failed: " + (json.error || "unknown"));
      modal.hide();
      setStatus(id ? "Updated." : "Created.");
      loadCategories();
    }).fail(() => setStatus("Request failed (network/server)."));
  });

  $tbody.on("click", "button[data-action]", function () {
    const action = $(this).data("action");
    const id = Number($(this).data("id"));

    if (action === "delete") {
      if (!confirm("Really delete this category?")) return;

      setStatus("Deleting...");
      $.ajax({
        url: "/api/categories/delete.php",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify({ id })
      }).done(function (json) {
        if (!json.ok) return setStatus("Delete failed: " + (json.error || "unknown"));
        setStatus("Deleted.");
        loadCategories();
      }).fail(() => setStatus("Request failed (network/server)."));
    }

    if (action === "edit") {
      // simplest: fetch list and find item (or call a get.php if you have one)
      $.get("/api/categories/list.php").done(function (json) {
        if (!json.ok) return setStatus("Load failed.");
        const cat = json.data.find(x => Number(x.id) === id);
        if (!cat) return setStatus("Category not found.");
        openEdit(cat);
      });
    }
  });

  loadCategories();
});