# Task Manager API Documentation

A comprehensive RESTful API for task management built with Laravel, featuring user authentication, task CRUD operations, admin dashboard, and role-based access control.

## 🚀 Features

- **User Authentication** - Registration, login, logout with Laravel Sanctum
- **Task Management** - Full CRUD operations with filtering, search, and reordering
- **Admin Dashboard** - User management, task overview, and statistics
- **Role-Based Access Control** - Admin and user roles with middleware protection
- **Caching** - Performance optimization with Redis-compatible caching
- **Scheduled Task Cleanup** - Automatic deletion of tasks older than 30 days
- **Comprehensive Testing** - Unit and feature tests with 100% coverage

## 📋 API Endpoints

### Authentication

#### Register User
```http
POST /api/register
```

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "Password@090",
  "password_confirmation": "Password@090"
}
```

**Response (201):**
```json
{
  "message": "Registration successful.",
  "access_token": "11|6BlMilPsqevDbwFIUVTpihWXhsXxEjY1TgS1otva7b045906",
  "token_type": "Bearer",
  "user": {
    "id": 8,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "user",
    "created_at": "2025-09-01 15:09:53",
    "updated_at": "2025-09-01 15:09:53"
  }
}
```

#### Login
```http
POST /api/login
```

**Request Body:**
```json
{
  "email": "admin@example.com",
  "password": "admin123"
}
```

**Response (200):**
```json
{
  "message": "Login successful.",
  "access_token": "3|8zNUyOsldS4bKaSvYOZYz8pEqVypssBPdUFqXyd56e47d519",
  "token_type": "Bearer",
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@example.com",
    "role": "admin",
    "created_at": "2025-09-01 11:38:31",
    "updated_at": "2025-09-01 11:38:31"
  }
}
```

#### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "message": "Logged out successfully."
}
```

### Task Management

#### Get All Tasks
```http
GET /api/tasks
Authorization: Bearer {token}
```

**Query Parameters:**
- `status` (optional): `pending` | `completed`
- `priority` (optional): `low` | `medium` | `high`
- `search` (optional): Search in title and description
- `sort_by` (optional): `order` | `created_at` | `updated_at`
- `sort_direction` (optional): `asc` | `desc`

**Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Finish project",
      "description": "Complete backend",
      "status": "pending",
      "priority": "high",
      "order": 1,
      "user_id": 7,
      "created_at": "2025-09-01 14:57:15",
      "updated_at": "2025-09-01 14:57:15"
    }
  ],
  "meta": {
    "total": 4,
    "pending": 4,
    "completed": 0,
    "low_priority": 0,
    "medium_priority": 0,
    "high_priority": 4
  }
}
```

#### Create Task
```http
POST /api/tasks
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "title": "Finish project 4",
  "description": "Complete frontend",
  "status": "pending",
  "priority": "high"
}
```

**Response (201):**
```json
{
  "message": "Task created successfully.",
  "data": {
    "id": 4,
    "title": "Finish project 4",
    "description": "Complete frontend",
    "status": "pending",
    "priority": "high",
    "order": 4,
    "user_id": 7,
    "created_at": "2025-09-01 15:05:56",
    "updated_at": "2025-09-01 15:05:56"
  }
}
```

#### Get Specific Task
```http
GET /api/tasks/{id}
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "data": {
    "id": 2,
    "title": "Finish project 2",
    "description": "Complete frontend",
    "status": "pending",
    "priority": "high",
    "order": 2,
    "user_id": 7,
    "created_at": "2025-09-01 14:59:13",
    "updated_at": "2025-09-01 14:59:13"
  }
}
```

#### Update Task
```http
PUT /api/tasks/{id}
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "title": "Finish project updated",
  "status": "completed",
  "priority": "medium"
}
```

**Response (200):**
```json
{
  "message": "Task updated successfully.",
  "data": {
    "id": 1,
    "title": "Finish project updated",
    "description": "Complete backend",
    "status": "completed",
    "priority": "medium",
    "order": 1,
    "user_id": 7,
    "created_at": "2025-09-01 14:57:15",
    "updated_at": "2025-09-01 15:14:43"
  }
}
```

#### Delete Task (Admin Only)
```http
DELETE /api/admin/tasks/{id}
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "message": "Task deleted successfully by admin.",
  "deleted_task": {
    "id": 3,
    "title": "Task Title",
    "user_id": 7
  }
}
```

**Note:** Only admins can delete tasks. Regular users cannot delete tasks and must contact an admin for task removal.

#### Toggle Task Status
```http
PATCH /api/tasks/{id}/toggle-status
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "message": "Task status updated successfully.",
  "data": {
    "id": 2,
    "title": "Finish project 2",
    "description": "Complete frontend",
    "status": "completed",
    "priority": "high",
    "order": 3,
    "user_id": 7,
    "created_at": "2025-09-01 14:59:13",
    "updated_at": "2025-09-01 15:27:51"
  }
}
```

#### Reorder Tasks
```http
POST /api/tasks/reorder
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "tasks": [
    { "id": 4, "order": 1 },
    { "id": 1, "order": 2 },
    { "id": 2, "order": 3 }
  ]
}
```

**Response (200):**
```json
{
  "message": "Tasks reordered successfully."
}
```

#### Get Task Statistics
```http
GET /api/tasks/statistics
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "data": {
    "total": 3,
    "pending": 1,
    "completed": 2,
    "low_priority": 0,
    "medium_priority": 1,
    "high_priority": 2,
    "completion_rate": 66.67
  }
}
```

### Admin Dashboard

#### Get Dashboard Statistics
```http
GET /api/admin/dashboard
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "data": {
    "total_users": 8,
    "total_tasks": 3,
    "pending_tasks": 1,
    "completed_tasks": 2,
    "completion_rate": 66.67
  }
}
```

#### Get All Users
```http
GET /api/admin/users
Authorization: Bearer {token}
```

**Query Parameters:**
- `per_page` (optional): Number of users per page (default: 15)

**Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Admin User",
      "email": "admin@example.com",
      "email_verified_at": null,
      "role": "admin",
      "created_at": "2025-09-01T11:38:31.000000Z",
      "updated_at": "2025-09-01T11:38:31.000000Z",
      "total_tasks": 0,
      "pending_tasks": 0,
      "completed_tasks": 0,
      "tasks": []
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 8
  }
}
```

#### Get All Tasks (Admin)
```http
GET /api/admin/tasks
Authorization: Bearer {token}
```

**Query Parameters:**
- `per_page` (optional): Number of tasks per page (default: 15)
- `status` (optional): `pending` | `completed`
- `priority` (optional): `low` | `medium` | `high`
- `search` (optional): Search in title and description

**Response (200):**
```json
{
  "data": [
    {
      "id": 4,
      "title": "Finish project 4",
      "description": "Complete frontend",
      "status": "pending",
      "priority": "high",
      "order": 1,
      "user_id": 7,
      "created_at": "2025-09-01T15:05:56.000000Z",
      "updated_at": "2025-09-01T15:25:11.000000Z",
      "user": {
        "id": 7,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user"
      }
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 15,
    "total": 3
  }
}
```

#### Get User Statistics
```http
GET /api/admin/users/{user_id}/stats
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "data": {
    "user_id": 7,
    "user_name": "John Doe",
    "user_email": "john@example.com",
    "total_tasks": 3,
    "pending_tasks": 1,
    "completed_tasks": 2,
    "low_priority": 0,
    "medium_priority": 1,
    "high_priority": 2,
    "completion_rate": 66.67
  }
}
```

## 🔐 Authentication

All endpoints except registration and login require authentication using Bearer tokens. Include the token in the Authorization header:

```
Authorization: Bearer {your_access_token}
```

## 📊 Error Responses

### Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password field is required."]
  }
}
```

### Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
  "message": "Unauthorized. Admin access only."
}
```

### Not Found (404)
```json
{
  "message": "Task not found."
}
```

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

Run specific test files:
```bash
php artisan test tests/Feature/Auth/LoginControllerTest.php
php artisan test tests/Feature/User/TasksControllerTest.php
php artisan test tests/Feature/Admin/AdminControllerTest.php
php artisan test tests/Feature/Admin/AdminTasksControllerTest.php
php artisan test tests/Unit/CleanupOldTasksTest.php
```

## 🔄 Scheduled Tasks

### Task Cleanup Job

The system automatically deletes tasks older than 30 days to maintain database performance and storage efficiency.

**Manual Execution:**
```bash
# Dry run (see what would be deleted without actually deleting)
php artisan tasks:cleanup --dry-run

# Run the cleanup job
php artisan tasks:cleanup
```

**Automatic Schedule:**
- Runs daily at midnight via Laravel Scheduler
- Configured in `bootstrap/app.php`
- Logs all deletions for audit trail

**Queue Processing:**
```bash
# Process queued jobs
php artisan queue:work
```

## 📁 Postman Collection

Download the complete Postman collection with all endpoints and examples:

**[Task Manager API Collection](docs/TaskManagerAPI.postman_collection.json)**

The collection includes:
- Environment variables setup
- Pre-request scripts for token management
- Test scripts for response validation
- All API endpoints with example requests/responses
- Authentication flow examples

## 🛠️ Installation & Setup

1. **Clone the repository**
```bash
git clone https://github.com/Je-Joestar24/taskmanager.git
cd TaskManager/backend
```

2. **Install dependencies**
```bash
composer install
```

3. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database configuration**
```bash
# Update .env with your database credentials
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Run migrations and seeders**
```bash
php artisan migrate
php artisan db:seed
```

6. **Start the server**
```bash
php artisan serve
```

## 🚀 Performance Features

- **Caching**: 5-minute TTL for task lists and statistics
- **Database Indexing**: Optimized queries with proper indexes
- **Eager Loading**: Prevents N+1 query problems
- **Pagination**: Scalable data handling for large datasets

## 🔒 Security Features

- **Input Validation**: Comprehensive request validation
- **XSS Protection**: Automatic content sanitization
- **CSRF Protection**: Built-in CSRF token validation
- **Role-Based Access**: Middleware-based authorization
- **Password Hashing**: Secure password storage
- **Token Authentication**: Sanctum-based API authentication
- **Admin-Only Deletion**: Only administrators can delete tasks for enhanced security

## 📈 API Status Codes

- `200` - Success
- `201` - Created
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass
6. Submit a pull request

---

**Built with ❤️ using Laravel**
