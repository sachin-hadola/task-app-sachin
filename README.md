# Task Management Application

A simple Task Management application built with **Laravel 12.46** (REST API) and **Vue.js 3** (frontend).  
This project is part of a technical assessment for a **Senior Backend Developer (Laravel)** role.

---

## Tech Stack

- **Backend:** Laravel 12.46, PHP 8.4+
- **Frontend:** Vue.js 3 (Vite)
- **Database:** MySQL
- **Testing:** PHPUnit (Feature tests)

---

## Features

- Task CRUD REST API
- Vue.js frontend to manage tasks
- Task status update
- Soft deletes
- Pagination (10 items per page)
- Filter tasks by status
- Feature tests with database isolation

---

## Project Setup Instructions

### 1. Clone Repository

```bash
git clone https://github.com/sachin-hadola/task-app-sachin.git
cd task-app-sachin
```

---

### 2. Install Backend Dependencies

```bash
composer install
```

---

### 3. Environment Configuration 

Copy `.env.example` to `.env`

```bash
cp .env.example .env
php artisan key:generate
```

Update database credentials in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_app
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
```

Set application URL:

```env
APP_URL=Your Project path
```

---

```bash
php artisan optimize:clear
```

---

## Database Setup

### Run Migrations & Seeders

```bash
php artisan migrate --seed
```

This will:
- Create the `tasks` table
- Insert sample tasks

---

## Running Tests

```bash
php artisan test
```

Covered test cases:
- Create task (success)
- Create task (validation failure)
- List tasks
- Update task
- Delete task

---

### Run Migrations & Seeders again

```bash
php artisan migrate --seed
```


## Frontend (Vue.js) Setup

### Install Dependencies

```bash
npm install
```

### Run Vite Dev Server

```bash
npm run dev
```

### Build Assets (Production)

```bash
npm run build
```

---

## Access Application

```
Eg. http://task-app-sachin.test

OR "php artisan serve"
```

---

## API Documentation

Base URL:

```
/api
```

### List Tasks
GET `/api/tasks`

Optional filter:
```
/api/tasks?status=pending
```

### Create Task
POST `/api/tasks`

```json
{
  "title": "New Task",
  "description": "Optional description",
  "status": "pending",
  "due_date": "2024-12-31"
}
```

### Update Task
PUT `/api/tasks/{id}`

### Delete Task
DELETE `/api/tasks/{id}`
