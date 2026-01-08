const tbody = document.getElementById("tbody");
const statusEl = document.getElementById("status");

const form = document.getElementById("customerForm");
const btnReload = document.getElementById("btnReload");
const btnCancel = document.getElementById("btnCancel");

// form fields
const idEl = document.getElementById("id");
const firstNameEl = document.getElementById("first_name");
const lastNameEl = document.getElementById("last_name");
const groupEl = document.getElementById("customer_group");
const email = document.getElementById("email");
const phone = document.getElementById("phone");
const street = document.getElementById("street");
const zip = document.getElementById("zip");
const city = document.getElementById("city");

// Small safety helper to avoid HTML injection
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

function setStatus(msg) {
  statusEl.textContent = msg;
}

async function loadCustomers() {
  setStatus("Loading customers...");

  const res = await fetch("/api/customers/list.php");
  const json = await res.json();

  if (!json.ok) {
    setStatus("Load failed: " + (json.error || "unknown"));
    return;
  }

  tbody.innerHTML = json.data
    .map(
      (c) => `
    <tr>
      <td>${c.id}</td>
      <td>${escapeHtml(c.first_name)} ${escapeHtml(c.last_name)}</td>
      <td>${escapeHtml(c.customer_group ?? "")}</td>
      <td>${escapeHtml(c.created_at ?? "")}</td>
      <td>${escapeHtml(c.email ?? "")}</td>
      <td>${escapeHtml(c.phone ?? "")}</td>
      <td>${escapeHtml(c.street ?? "")}</td>
      <td>${escapeHtml(c.zip ?? "")}</td>
      <td>${escapeHtml(c.city ?? "")}</td>
      <td>
        <button data-action="edit" data-id="${c.id}">Edit</button>
        <button data-action="delete" data-id="${c.id}">Delete</button>
      </td>
    </tr>
  `,
    )
    .join("");

  setStatus(`Loaded ${json.data.length} customers.`);
}

// ---------- READ ONE (for edit) ----------
async function getCustomer(id) {
  const res = await fetch(
    `/api/customers/get.php?id=${encodeURIComponent(id)}`,
  );
  const json = await res.json();
  if (!json.ok) {
    setStatus("Get failed: " + (json.error || "unknown"));
    return null;
  }
  return json.data;
}

// ---------- CREATE ----------
async function createCustomer(payload) {
  const res = await fetch("/api/customers/create.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload),
  });
  return await res.json();
}

// ---------- UPDATE ----------
async function updateCustomer(payload) {
  const res = await fetch("/api/customers/update.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload),
  });
  return await res.json();
}

// ---------- DELETE ----------
async function deleteCustomer(id) {
  const res = await fetch("/api/customers/delete.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id }),
  });
  return await res.json();
}

// ---------- FORM SUBMIT (CREATE or UPDATE) ----------
form.addEventListener("submit", async (e) => {
  e.preventDefault();

  const payload = {
    id: idEl.value ? Number(idEl.value) : null,
    first_name: firstNameEl.value.trim(),
    last_name: lastNameEl.value.trim(),
    customer_group: groupEl.value.trim() || null,
    email: email.value.trim() || null,
    phone: phone.value.trim() || null,
    street: street.value.trim() || null,
    zip: zip.value.trim() || null,
    city: city.value.trim() || null,
  };

  if (!payload.first_name || !payload.last_name) {
    setStatus("First name and last name are required.");
    return;
  }

  setStatus("Saving...");

  let result;
  if (payload.id) result = await updateCustomer(payload);
  else result = await createCustomer(payload);

  if (!result.ok) {
    setStatus("Save failed: " + (result.error || "unknown"));
    return;
  }

  // reset form to "create mode"
  idEl.value = "";
  form.reset();

  setStatus("Saved.");
  await loadCustomers();
});

// Cancel edit mode
btnCancel.addEventListener("click", () => {
  idEl.value = "";
  form.reset();
  setStatus("Edit cancelled.");
});

// Reload button
btnReload.addEventListener("click", loadCustomers);

// Table actions (event delegation)
tbody.addEventListener("click", async (e) => {
  const btn = e.target.closest("button[data-action]");
  if (!btn) return;

  const id = btn.dataset.id;
  const action = btn.dataset.action;

  if (action === "delete") {
    if (!confirm("Really delete this customer?")) return;
    setStatus("Deleting...");
    const result = await deleteCustomer(id);
    if (!result.ok) {
      setStatus("Delete failed: " + (result.error || "unknown"));
      return;
    }
    setStatus("Deleted.");
    await loadCustomers();
  }

  if (action === "edit") {
    setStatus("Loading customer...");
    const c = await getCustomer(id);
    if (!c) return;

    // Fill the form so submit becomes an UPDATE
    idEl.value = c.id;
    firstNameEl.value = c.first_name ?? "";
    lastNameEl.value = c.last_name ?? "";
    groupEl.value = c.customer_group ?? "";
    email.value = c.email ?? "";
    phone.value = c.phone ?? "";
    street.value = c.street ?? "";
    zip.value = c.zip ?? "";
    city.value = c.city ?? "";

    setStatus(`Editing customer #${c.id}.`);
  }
});

// initial load
loadCustomers();
