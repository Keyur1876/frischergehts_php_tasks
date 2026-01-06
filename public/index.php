<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Customers CRUD</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body { font-family: Arial, sans-serif; padding: 16px; }
    table { border-collapse: collapse; width: 100%; margin-top: 12px; }
    th, td { border: 1px solid #ddd; padding: 8px; }
    th { background: #f5f5f5; text-align: left; }
    .row { display: flex; gap: 8px; margin-bottom: 8px; }
    input { padding: 6px; flex: 1; }
    button { padding: 6px 10px; }
    #status { margin-top: 10px; }
  </style>
</head>
<body>
  <h1>Customers</h1>

  <h3>Create / Edit</h3>
  <form id="customerForm">
    <input type="hidden" id="id" />

    <div class="row">
      <input id="first_name" placeholder="First name" required />
      <input id="last_name" placeholder="Last name" required />
      <input id="customer_group" placeholder="Group (optional)" />
    </div>

    <button type="submit">Save</button>
    <button type="button" id="btnCancel">Cancel Edit</button>
  </form>

  <p id="status"></p>

  <h3>List</h3>
  <button id="btnReload" type="button">Reload</button>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Group</th>
        <th>Created</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Street</th>
        <th>Zip</th>
        <th>City</th>
        <th>Actions</th>
        
      </tr>
    </thead>
    <tbody id="tbody"></tbody>
  </table>

  <script src="app.js"></script>
</body>
</html>
