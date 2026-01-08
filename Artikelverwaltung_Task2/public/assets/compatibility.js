$(function () {
  const $base = $("#baseCategory");
  const $addons = $("#addonCheckboxes");
  const $status = $("#status");
  const $btnSave = $("#btnSave");

  let categories = [];

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

  function renderAddonCheckboxes(selectedIds, baseId) {
    const selected = new Set((selectedIds || []).map(Number));
    const b = Number(baseId);

    const html = categories
      .map((c) => {
        const cid = Number(c.id);
        const disabled = cid === b;
        return `
        <div class="col-md-4">
          <div class="form-check border rounded-2 p-2 bg-light">
            <input class="form-check-input addon-check"
                   type="checkbox"
                   value="${cid}"
                   id="addon_${cid}"
                   ${selected.has(cid) ? "checked" : ""}
                   ${disabled ? "disabled" : ""}>
            <label class="form-check-label" for="addon_${cid}">
              ${escapeHtml(c.name)}
            </label>
          </div>
        </div>
      `;
      })
      .join("");

    $addons.html(html || `<div class="text-muted">No categories found.</div>`);
  }

  function getSelectedAddonIds() {
    return $(".addon-check:checked")
      .map(function () {
        return Number($(this).val());
      })
      .get();
  }

  function loadCategories() {
    setStatus("Loading categories...");
    return $.get("/api/categories/list.php").then((json) => {
      if (!json.ok) throw new Error(json.error || "Failed to load categories");
      categories = json.data || [];
      const options = ['<option value="">Select...</option>'].concat(
        categories.map(
          (c) => `<option value="${c.id}">${escapeHtml(c.name)}</option>`,
        ),
      );
      $base.html(options.join(""));
      setStatus("Choose a base category.");
    });
  }

  function loadCompatibility(baseId) {
    if (!baseId) {
      renderAddonCheckboxes([], 0);
      $btnSave.prop("disabled", true);
      return;
    }

    setStatus("Loading compatibility...");
    $.get("/api/compatibility/get.php", { base_category_id: baseId })
      .done((json) => {
        if (!json.ok)
          return setStatus("Load failed: " + (json.error || "unknown"));
        renderAddonCheckboxes(json.data?.addon_category_ids || [], baseId);
        setStatus("Edit allowed addon categories and click Save.");
        $btnSave.prop("disabled", false);
      })
      .fail(() => setStatus("Request failed (network/server)."));
  }

  $base.on("change", function () {
    const baseId = $(this).val();
    loadCompatibility(baseId);
  });

  $btnSave.on("click", function () {
    const baseId = Number($base.val());
    if (!baseId) return;

    const addonIds = getSelectedAddonIds();

    setStatus("Saving...");
    $.ajax({
      url: "/api/compatibility/save.php",
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify({
        base_category_id: baseId,
        addon_category_ids: addonIds,
      }),
    })
      .done((json) => {
        if (!json.ok)
          return setStatus("Save failed: " + (json.error || "unknown"));
        setStatus("Saved.");
      })
      .fail(() => setStatus("Request failed (network/server)."));
  });

  // init
  loadCategories()
    .then(() => renderAddonCheckboxes([], 0))
    .catch((err) => setStatus(err.message));
});
