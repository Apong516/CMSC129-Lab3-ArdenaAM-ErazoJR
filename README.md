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
- [AI Chatbot](#ai-chatbot-🤖)
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

- **AI Journal Assistant (Chatbot)**  
  A floating AI-powered chatbot widget embedded on the main page. Supports two modes: **Ask** (read-only inquiry) and **Manage** (natural language CRUD). Uses Groq.

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
│  │     ├─ Controller.php              # Base controller
│  │     ├─ JournalController.php       # Handles journal CRUD logic
│  │     ├─ UserController.php          # Handles user/profile logic
│  │     ├─ ChatBotController.php       # Handles AI chatbot (inquiry + CRUD)
│  │     ├─ AIAssistantController.php   # Handles AI function calls
│  │     └─ Api/
│  │        └─ JournalApiController.php # Internal API endpoints for AI
│  └─ Models/                  # Eloquent models
│     ├─ JournalEntry.php         # Journal entry model
│     └─ User.php                 # User/profile model
│  └─ Services/                # Business logic services
│     ├─ AIService.php             # Groq API wrapper
│     ├─ PromptService.php         # Prompt builder (inquiry + CRUD prompts)
│     ├─ JournalContextService.php # Resolves DB context for AI queries
│     ├─ JournalCrudService.php    # Handles AI-triggered CRUD operations
│     └─ FunctionCallService.php   # Handles AI function call intents
├─ bootstrap/                  # Bootstrapping Laravel
├─ config/                     # Configuration files
├─ database/                   # Database related files
│  ├─ migrations/              # Migration files
│  └─ seeders/                 # Database seeders
│     ├─ DatabaseSeeder.php
│     └─ JournalEntrySeeder.php   # 15 sample journal entries
├─ public/                     # Publicly accessible folder
│  └─ js/
│     └─ chatbot.js               # Chatbot frontend logic
├─ resources/                  # Resources like views, CSS, JS
│  └─ views/                   # Blade templates
│     ├─ journals/
│     ├─ layouts/
│     │  └─ app.blade.php
│     ├─ chat/
│     │  └─ components/
│     │     └─ chat-widget.blade.php  # Floating chat widget UI
│     └─ users/
├─ routes/                     # Routes configuration
│  └─ web.php                  # Web routes including /chat and /chat/crud
└─ vendor/                     # Composer dependencies
```

---

## INSTALLATION & SETUP ⚙️

1. **Clone the repository:**
```bash
git clone "https://github.com/elirvrrii/CMSC129-Lab2-ArdenAAM-ErazoJR"
cd inJOURNALize
```

2. **Install PHP dependencies:**
```bash
composer install
```

3. **Install Node dependencies (optional for assets):**
```bash
npm install
npm run dev
```

4. **Copy `.env.example` to `.env`:**
```bash
cp .env.example .env
```

5. **Generate application key:**
```bash
php artisan key:generate
```

6. **Update `.env` with database credentials and Groq API key** (see sections below)

---

## SETTING UP DATABASE 🗄️

1. **Create PostgreSQL database:**
```sql
CREATE DATABASE journal_db;
```

2. **Create PostgreSQL user (if needed):**
```sql
CREATE USER journal_user WITH PASSWORD 'your_password';
GRANT ALL PRIVILEGES ON DATABASE journal_db TO journal_user;
```

3. **Update `.env` with credentials:**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=journal_db
DB_USERNAME=journal_user
DB_PASSWORD=your_password
```

---

## MIGRATION COMMANDS 🔄

```bash
# Run all migrations
php artisan migrate

# Run migrations and seed database with sample data
php artisan migrate:fresh --seed

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

## AI CHATBOT 🤖

inJOURNALize includes a floating AI assistant powered by **Groq**. It is embedded as a widget on the main dashboard and supports two modes: **Ask** for read-only inquiry and **Manage** for natural language CRUD operations.

### AI Service Used

| Detail | Value |
|--------|-------|
| **Provider** | Groq |
| **Model** | `llama-3.3-70b-versatile` |
| **PHP Package** | `openai-php/client` (Groq is OpenAI-compatible) |
| **Access** | Free tier available at [console.groq.com](https://console.groq.com/keys) |

---

### Setup & API Key

1. Go to [https://console.groq.com/keys](https://console.groq.com/keys)
2. Sign in with your account
3. Click **"Create API key"** and copy it
4. Install the OpenAI-compatible PHP client via Composer (Groq uses an OpenAI-compatible API)

```bash
composer require openai-php/client
```

5. Add the following variables to your `.env` file:

```env
GROQ_API_KEY=your_groq_api_key_here
```

> ⚠️ Never commit your `.env` file or expose your API key publicly.

---

### Environment Variables

The following environment variables are required for the AI chatbot to function:

```env
# Required — Groq AI
GROQ_API_KEY=your_groq_api_key_here
```

---

### How It Works

The AI never accesses the database directly. All data flows through your Laravel backend:

```
User → ChatBotController → JournalContextService / JournalCrudService → DB
                                        ↓
                                   JSON string
                                        ↓
                           PromptService → AIService → Groq API
                                        ↓
                              text response → User
```

This design keeps database credentials safe and lets you control exactly what context the AI receives.

---

### Modes

#### 💬 Ask Mode — Read-Only Inquiry

Ask questions about your journal entries in plain English. The AI retrieves relevant entries from the database, packages them as context, and generates a natural language answer. No data is modified.

#### ✏️ Manage Mode — Natural Language CRUD

Perform create, update, and delete operations on your journal entries using conversational commands. Destructive operations (update and delete) require explicit **confirmation** before executing, preventing accidental changes.

---

### Example Queries

#### 💬 Ask Mode (Inquiry)

Try these prompts to explore your journal history:

```
"How have I been feeling this week?"
"What did I write about last Monday?"
"Show me all my happy entries from this month."
"What's the most recent entry I wrote in the morning?"
"Summarize my mood over the past 7 days."
"Do I have any entries mentioning work or school?"
"What location did I write most of my entries from?"
"Which day did I feel the saddest based on my entries?"
```

#### ✏️ Manage Mode (CRUD)

Use these prompts to create or modify entries through conversation:

```
# Create
"Add a new entry titled 'Morning Walk' with a happy mood."
"Create a journal entry for today about my study session."

# Update
"Change the mood of my last entry to neutral."
"Update the title of yesterday's entry to 'Rough Day'."
"Edit the content of my entry about work stress."

# Delete
"Delete my entry from two days ago."
"Remove the entry titled 'Draft'."
```

> 🔒 For **update** and **delete** prompts, the chatbot will ask you to confirm before making any changes.

---

### Architecture Overview

| File | Responsibility |
|------|----------------|
| `ChatBotController.php` | Routes Ask/Manage requests, returns responses |
| `AIService.php` | Wraps the Groq API call |
| `PromptService.php` | Builds system + user prompts for each mode |
| `JournalContextService.php` | Fetches and formats DB entries as AI context |
| `JournalCrudService.php` | Executes AI-triggered create/update/delete |
| `FunctionCallService.php` | Parses AI intent into function calls |
| `chatbot.js` | Frontend widget logic (send, receive, confirm) |
| `chat-widget.blade.php` | Floating chat UI rendered on the dashboard |

---

## SCREENSHOTS 🖼️

### Dashboard
<img width="1919" height="921" alt="DashboardPage" src="https://github.com/user-attachments/assets/9c6cfb17-6fb9-476c-9542-882600e9c6c8" />

### New Entry Page
<img width="1919" height="910" alt="NewEntryPage" src="https://github.com/user-attachments/assets/c4ac39f8-ca3d-4bd0-803b-0905d92c9144" />

### Profile Page
<img width="1919" height="897" alt="ProfilePage" src="https://github.com/user-attachments/assets/0ee92bc4-3b0b-483e-acc6-517580ffaca7" />

### AI Chatbot — Ask Mode
> *Screenshot placeholder: Show the chat widget open with a sample inquiry like "How have I been feeling this week?" and the AI's mood summary response.*

### AI Chatbot — Manage Mode
> *Screenshot placeholder: Show a manage-mode prompt like "Delete my entry from yesterday" followed by the confirmation dialog before the action executes.*

---

## USAGE ▶️

1. Select or create a user profile
2. Add a new journal entry with title, content, mood, date, and location
3. Track your mood over time using the filter system
4. Edit, delete, or archive entries as needed
5. Restore entries from archive or permanently delete them
6. Use the **💬 chat widget** (bottom-right corner) to:
   - **Ask Mode:** Ask questions about your past entries in plain English
   - **Manage Mode:** Create, update, or delete entries using natural language commands

---

## CONTRIBUTING 🤝

Contributions are welcome! Please fork the repository and create a pull request.
