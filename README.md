# inJOURNALize 📖

---

This is your own personal web diary! inJOURNALize serves as both a journal and mood tracker guaranteed to work right beneath your fingertips. The first version uses MERN for its tech stack and has now been "migrated" to Laravel. Made to provide the best user experience by emphasizing simplicity and minimalism.

This app is created to experiment with Laravel. By extension, it is to practice the use of MVC architecture and database relationships while using PostgreSQL as a database.

---

## TABLE OF CONTENTS 📋

- [Features](#features-🛠️)
- [Prerequisites](#prerequisites)
- [Project Structure](#project-structure)
- [Installation & Setup](#installation--setup)
- [Database Setup](#setting-up-database)
- [Migration Commands](#migration-commands)
- [Screenshots](#screenshots)
- [Usage](#usage)
- [Contributing](#contributing)

---

## FEATURES 🛠️

- **CRUD Implementation**  
  The application uses simple CRUD (Create, Read, Update, Delete) operations to make journal entries easy to use.

- **Archive System (Soft/Hard Delete Implementation)**  
  When a user deletes their journal entry, the journal first gets sent to the "Archive" where they can either send it back to the list of journal entries or remove it permanently.

- **Profile System**  
  This version of inJOURNALize lets you switch between different profiles to accommodate their needs. Users can also add a password to keep entries private.

- **Mood Tracker**  
  inJOURNALize keeps track of your mood for each journal entry you make. You can track whether you were sad, happy, or in between.

- **Search and Filter System**  
  Users can search for past entries and filter by mood or date range.

---

## PREREQUISITES 📦

- PHP 8.3+ (core programming language)
- Laravel 13 (PHP framework)
- PostgreSQL (database)
- Composer (dependency manager)
- Git (version control)

**Optional but recommended:**  
- npm (for frontend asset compilation)  
- Laravel Pint (for code formatting)

---

## PROJECT STRUCTURE 🗂️

```text
├─ app/                       # Main application folder
│  ├─ Http/                   # HTTP layer
│  │  └─ Controllers/         # All controllers
│  │     ├─ Controller.php        # Base controller
│  │     ├─ JournalController.php # Handles journal CRUD logic
│  │     └─ UserController.php    # Handles user/profile logic
│  └─ Models/                  # Eloquent models
│     ├─ JournalEntry.php         # Journal entry model
│     └─ User.php                 # User/profile model
├─ bootstrap/                  # Bootstrapping Laravel
├─ config/                     # Configuration files
├─ database/                   # Database related files
│  ├─ migrations/              # Migration files
│  │  ├─ 0001_01_01_000000_create_users_table.php
│  │  ├─ 0001_01_01_000001_create_cache_table.php
│  │  ├─ 0001_01_01_000002_create_jobs_table.php
│  │  ├─ 0001_01_01_000003_create_entries_table.php
│  │  ├─ 2026_03_28_050300_add_deleted_at_to_users_table.php
│  │  └─ 2026_03_30_044125_create_sessions_table.php
├─ node_modules/               # Node.js packages (if using npm)
├─ public/                     # Publicly accessible folder (CSS, JS, images)
├─ resources/                  # Resources like views, CSS, JS
│  ├─ css/                     # Stylesheets
│  ├─ js/                      # JS files
│  └─ views/                   # Blade templates
│     ├─ journals/             # Journal-related views
│     │  ├─ create.blade.php
│     │  ├─ edit.blade.php
│     │  └─ index.blade.php
│     ├─ layouts/              # Layout templates
│     │  └─ app.blade.php
│     ├─ users/                # User-related views
│     │  ├─ create.blade.php
│     │  ├─ edit.blade.php
│     │  └─ index.blade.php
│     └─ welcome.blade.php     # Homepage
├─ routes/                     # Routes configuration
│  ├─ console.php              # Commands run in console
│  └─ web.php                  # Web routes
└─ vendor/                     # Composer dependencies
```

This structure follows **Laravel’s MVC architecture**:

- `app/Models` contains all Eloquent models
- `app/Http/Controllers` contains controller logic
- `resources/views` contains Blade templates (views)

---

## INSTALLATION & SETUP ⚙️

1. **Clone the repository:**
```
git clone "https://github.com/elirvrrii/CMSC129-Lab2-Arde-aAM-ErazoJR" 
cd inJOURNALize
```

2. **Install PHP dependencies:**
```
composer install
```

3. **Install Node dependencies (optional for assets):**
```
npm install  
npm run dev
```

4. **Copy `.env.example` to `.env`:**
```
cp .env.example .env
```

5. **Generate application key:**
```
php artisan key:generate
```

6. **Update `.env` with database credentials** (see Database Setup section)

---

## SETTING UP DATABASE 🗄️

1. **Create PostgreSQL database:**
```
CREATE DATABASE journal_db;
```

2. **Create PostgreSQL user (if needed):**
```
CREATE USER journal_user WITH PASSWORD 'your_password';  
GRANT ALL PRIVILEGES ON DATABASE journal_db TO journal_user;
```

3. **Update `.env` with credentials:**
```
DB_CONNECTION=pgsql  
DB_HOST=127.0.0.1  
DB_PORT=5432  
DB_DATABASE=journal_db  
DB_USERNAME=journal_user  
DB_PASSWORD=your_password

```

---

## MIGRATION COMMANDS 🔄

```
# Run all migrations  
php artisan migrate  
  
# Undo last batch of migrations  
php artisan migrate:rollback  
  
# Undo all migrations  
php artisan migrate:reset  
  
# Reset all migrations then run again  
php artisan migrate:refresh  
  
# Drop all tables and run migrations from scratch  
php artisan migrate:fresh
```

---

## SCREENSHOTS 🖼️
<img width="1919" height="921" alt="DashboardPage" src="https://github.com/user-attachments/assets/9c6cfb17-6fb9-476c-9542-882600e9c6c8" />
<img width="1919" height="910" alt="NewEntryPage" src="https://github.com/user-attachments/assets/c4ac39f8-ca3d-4bd0-803b-0905d92c9144" />
<img width="1919" height="897" alt="ProfilePage" src="https://github.com/user-attachments/assets/0ee92bc4-3b0b-483e-acc6-517580ffaca7" />

---

## USAGE ▶️

1. Select or create a user profile
2. Add a new journal entry with title, content, mood, date, and location
3. Track your mood over time using the filter system
4. Edit, delete, or archive entries as needed
5. Restore entries from archive or permanently delete them

---

## CONTRIBUTING 🤝

Contributions are welcome! Please fork the repository and create a pull request.
