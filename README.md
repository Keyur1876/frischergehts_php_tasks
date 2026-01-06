# Customer Management – PHP + AJAX

Small customer management tool for a delivery service.

## Tech
- Plain PHP (PDO)
- Vanilla JS (fetch/AJAX)
- MySQL/MariaDB
- Bootstrap (UI)

## Features
- List customers (AJAX)
- Create customer (AJAX)
- Edit customer (AJAX modal)
- Delete customer (AJAX + confirmation)
- Search + filter by group

## Setup
1. Create DB + tables:
   `mysql -u crm_user -p delivery_crm < sql/schema.sql`
2. Configure DB in `src/db.php`
3. Run (dev server):
   `php -S localhost:8000 -t .`
4. Open:
   `http://localhost:8000/public/`
