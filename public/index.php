<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Customers</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ddd; padding: 8px; }
    th { text-align: left; background: #f5f5f5; }
  </style>
</head>
<body>
  <h1>Customers</h1>

  <button id="btnLoad">Load customers</button>

  <p id="status"></p>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Group</th>
        <th>Created</th>
      </tr>
    </thead>
    <tbody id="tbody"></tbody>
  </table>

  <script src="app.js"></script>
</body>
</html>
