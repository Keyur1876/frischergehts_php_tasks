const btnLoad = document.getElementById("btnLoad");
const tbody = document.getElementById("tbody");
const statusEl = document.getElementById("status");

async function loadCustomers() {
  statusEl.textContent = "Loading...";

  try {
    // If index.php is in /public, this goes up one folder to /api
    const res = await fetch("../api/customers/list.php");
    const json = await res.json();

    if (!json.ok) {
      statusEl.textContent = "Failed: " + (json.error || "unknown error");
      return;
    }

    // Render rows
    tbody.innerHTML = "";
    for (const c of json.data) {
      const tr = document.createElement("tr");

      tr.innerHTML = `
        <td>${c.id}</td>
        <td>${escapeHtml(c.first_name)} ${escapeHtml(c.last_name)}</td>
        <td>${escapeHtml(c.customer_group ?? "")}</td>
        <td>${escapeHtml(c.created_at)}</td>
      `;

      tbody.appendChild(tr);
    }

    statusEl.textContent = `Loaded ${json.data.length} customers.`;
  } catch (err) {
    statusEl.textContent = "Request error: " + err.message;
  }
}

// Small safety helper to avoid HTML injection
function escapeHtml(str) {
  return String(str).replace(/[&<>"']/g, (s) => ({
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#039;",
  }[s]));
}

btnLoad.addEventListener("click", loadCustomers);

// optional: auto-load on page open
//loadCustomers();
