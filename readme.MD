# Lightweight PHP MVC Framework (Custom Laravel-style)

This is a lightweight PHP MVC project with a simple CLI for generating files and managing database tables (migrations).

---

## 📁 Directory Structure

```
framework/
├── app/
│   ├── controller/
│   ├── models/
│   ├── views/
├── config/
│   └── database.php
├── core/
│   └── Database.php
├── migrations/
├── public/
│   └── index.php
├── cli.php
└── migrate.php
└── router.php


```

---

## ⚙️ Requirements

- PHP 7.x or later
- MySQL/MariaDB
- CLI Access (e.g., Command Prompt, Terminal)

---

## 👨‍🛠️ How to Use

### 1. 📦 Create Files (Controller, Model, View)

Use `cli.php` to generate files automatically.

```bash
php cli.php make:controller UserController
php cli.php make:model User
php cli.php make:view user_view
```

Generated files will go into their respective folders:
- Controllers → `app/controller/`
- Models → `app/models/`
- Views → `app/views/`

---

### 2. 🧱 Create a Migration File (for Table Creation)

Manually create a migration file inside the `/migrations/` folder.
php cli.php make:migration create_students_table

Example file: `migrations/2025_04_04_184533_create_faculty_table.php`

```php
<?php

class CreateFacultyTable {
    public function up($pdo) {
        $query = "
            CREATE TABLE IF NOT EXISTS faculty (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100),
                email VARCHAR(100),
                department VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ";
        $pdo->exec($query);
    }
}
```

📝 **Important**: Class names must NOT start with numbers.

---

### 3. 🚀 Run Migrations (Push Tables to DB)

Use the migration runner to push your defined tables to the database:

```bash
php migrate.php
```

Each migration file will:
- Be loaded
- The class will be instantiated
- The `up()` method will be called to create your table

You will see success messages in your terminal.

---

## ✅ Example Workflow

```bash
# Step 1: Generate controller, model, and view
php cli.php make:controller FacultyController
php cli.php make:model Faculty
php cli.php make:view faculty

# Step 2: Create migration file
# (create manually: migrations/2025_04_04_184533_create_faculty_table.php)

# Step 3: Run migrations
php migrate.php
```

---

## 🔧 Config

Edit `config/database.php` to update your DB connection.

```php
class Database {
    public static function connect() {
        return new PDO('mysql:host=localhost;dbname=your_db_name', 'root', '');
    }
}
```

---

## 💡 Tips

- Keep migration filenames unique using timestamps (e.g. `2025_04_04_184533_create_users_table.php`)
- You can expand `cli.php` to support route generation, migration creation, etc.

---

## 📌 License

This is a custom educational framework inspired by Laravel. Not for production use.

