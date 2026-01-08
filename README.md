# Customer Management – PHP + AJAX-- Task 1

Small customer management tool for a delivery service.

## Tech

- Plain PHP (PDO)
- Vanilla JS (fetch/AJAX)
- MySQL

## Features

- List customers (AJAX)
- Create customer (AJAX)
- Edit customer (AJAX modal)
- Delete customer (AJAX + confirmation)
- Search + filter by group

## Setup

1. Add 'db.php' file into root folder (Attached in mail)
2. Change Directory to Customer_Management-Task1
3. Run (dev server):
   `php -S localhost:8000 -t ./public/`
4. Open:
   `http://localhost:8000`

# Article Management – PHP + AJAX — Task 2

Small article and category management tool for a delivery service.

## Tech

- Plain PHP (PDO)
- jQuery (AJAX)
- MySQL
- Bootstrap 5

## Features

- Category CRUD (create, edit, delete)
- Article CRUD (name, description, price)
- Assign articles to multiple categories
- Define category compatibility rules
- Dynamic article list (AJAX)
- Search and filter articles by category
- No page reloads

## Database

- `category`
- `article`
- `article_category` (many-to-many)
- `category_compatibility`

## Setup

1. Add `db.php` into root folder (DB credentials)
2. Change Directory to Artikelverwaltung_Task2
3. Start dev server:
   `php -S localhost:8000 -t ./public/`
4. Open:
   `http://localhost:8000`
