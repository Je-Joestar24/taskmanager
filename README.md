# TaskManager

A full-stack Task Management system built with:
- Backend: Laravel 12 (Sanctum, PSR-12, Service Layer, Repository pattern where applicable)
- Frontend: Vue 3 (Composition API), Pinia, Vue Router, TailwindCSS
- Auth: Laravel Sanctum (SPA tokens)
- DB: MySQL (primary), SQLite for quick local/dev if desired

## Features

- Authentication (register, login, logout) with Sanctum
- Role-based access (user, admin) via `CheckAdmin` middleware
- Tasks CRUD (user-scoped) with filtering, search, priority, status toggle
- Drag-and-drop reorder endpoint (UI-ready)
- Admin dashboard and endpoints for global statistics, users, tasks, user-specific stats
- Scheduled cleanup job to remove tasks older than 30 days (daily at midnight)
- API Resources and Form Request validation
- Caching for task lists where applicable
- Unit/Feature tests for core functionality (Auth, Admin, Cleanup)

## Monorepo Structure

```
TaskManager/
  backend/        # Laravel 12 app
  frontend/       # Vue 3 app (Vite, TailwindCSS, Pinia)
```

---

## Backend (Laravel 12)

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 8+ (or MariaDB equivalent)

### Setup

1) Env
```
cd backend
cp .env.example .env
# Update DB_*, APP_URL, SANCTUM settings for SPA if needed
```

2) Install & key
```
cd backend
composer install
php artisan key:generate
```

3) Migrate & seed
```
cd backend
php artisan migrate --seed
```

4) Run server
```
cd backend
php artisan serve
```

### Authentication
- Routes: `POST /api/login`, `POST /api/register`, `POST /api/logout` (logout requires auth)
- Tokens: SPA Bearer token set in frontend `Authorization` header

### Task Routes (auth required)
- `GET /api/tasks` - list user tasks with filters: `status`, `priority`, `search`
- `POST /api/tasks` - create task (auto-assign `order`)
- `GET /api/tasks/{task}` - show task (owned by user)
- `PUT /api/tasks/{task}` - update task
- `PATCH /api/tasks/{task}/toggle-status` - toggle between pending/completed
- `POST /api/tasks/reorder` - reorder by sending array of `{ id, order }`
- `GET /api/tasks/statistics` - return per-user statistics

Task fields:
- `id`, `title`, `description`, `status` (pending|completed), `priority` (low|medium|high), `order`, `user_id`

### Admin Routes (auth + admin)
- `GET /api/admin/dashboard` - global stats
- `GET /api/admin/users` - list users (non-admin), supports `search` (name/email) and pagination
- `GET /api/admin/tasks` - list all tasks with filters/pagination
- `GET /api/admin/users/{user}/stats` - stats for a specific user
- `DELETE /api/admin/tasks/{task}` - delete any task

### Middleware
- `auth:sanctum` for protected routes
- `admin` alias for `CheckAdmin` middleware

### Scheduler & Jobs
- Job: `CleanupOldTasks` deletes tasks older than 30 days, logs actions
- Scheduled daily at midnight in `bootstrap/app.php`
- Manually trigger: `php artisan tasks:cleanup` (supports `--dry-run`)

### Testing
```
cd backend
php artisan test
```
Covers: Auth, Admin endpoints, Cleanup job.

---

## Frontend (Vue 3 + Vite)

### Prerequisites
- Node 18+
- npm 9+

### Setup
```
cd frontend
npm install
npm run dev
```
Vite server defaults to `http://localhost:5173`.

### Tech
- Vue 3 (Composition API)
- Pinia for state (`auth`, `tasks`, `admin`, `notif`)
- Vue Router with route guards (`requireAuth`, `requireAdmin`, `requireGuest`)
- TailwindCSS with custom theme via CSS variables

### State & Modules
- `stores/auth.js`: login/register/logout, token+user in `localStorage`, isAdmin/isUser
- `stores/tasks.js`: tasks list, filters (status/priority/search), stats, CRUD & reorder
- `stores/admin.js`: dashboard stats, manage users (search non-admins), all tasks, delete as admin
- `stores/notif.js`: global loading states and notifications (toast + persistent)

### UI Highlights
- Tasks list with filters and search
- Create/Edit modals with theme styles
- Toggle status, reorder-ready UI hooks
- Admin views: Users, Tasks, Dashboard
- Mobile and desktop navigations (accessible, ARIA-labeled)

### Environment & API Config
- Frontend config for API base URL: `frontend/src/config/api.js`
- Sets `Authorization: Bearer <token>` after login/register.

---

## Usage Walkthrough

1) Register or login from the frontend.
2) As a user:
   - Create tasks (title, description, priority)
   - Filter/search tasks; toggle status; edit tasks; view per-user stats.
3) As an admin:
   - Access Admin Panel for dashboard, users (non-admin) list with search, and all tasks.
   - Delete tasks where necessary.

---

## Accessibility & Semantics
- Semantic HTML and ARIA labels on navigation and modals
- Keyboard focus styles and transitions enabled
- Tailwind theme variables ensure contrast and consistency

---

## Security
- Sanctum for CSRF-safe SPA auth
- Validation via Form Requests
- Password hashing
- Authorization via policies/middleware

---

## Performance
- Server-side caching for repeated queries
- Debounced search on admin users
- Minimal bundles via Vite

---

## Troubleshooting
- API 401: Ensure token is set and Sanctum configured correctly
- CORS: Confirm allowed origins in Laravel `config/cors.php`
- DB: Run migrations and confirm `.env` DB credentials

---

## Scripts
Backend:
```
cd backend
php artisan serve
php artisan migrate --seed
php artisan test
php artisan tasks:cleanup --dry-run
```

Frontend:
```
cd frontend
npm run dev
npm run build
```

---

## License
MIT
