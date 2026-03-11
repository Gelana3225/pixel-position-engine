## Pixel Position Engine

Pixel Position Engine is a focused job discovery and publishing engine for modern web teams.  
It provides a clean, tag‑driven job board where employers can publish roles and candidates can explore curated positions with rich metadata (employer, salary, tags, and external application URLs).

Built on **Laravel 12** and **PHP 8.2**, the project emphasizes maintainable backend architecture, Blade component–driven UIs, and a streamlined local developer experience (single‑command setup, concurrent dev tooling, and first‑class testing support).

---

### Key Features

- **Job listing engine**
  - Structured `Job` entities with title, salary, external `url`, and associated `Employer`.
  - Employer attribution and logo support for strong visual branding.
  - Separation between display data and external application flow (candidate applies on employer site).

- **Tag‑based discovery**
  - Each job can be associated with one or more `Tag` records.
  - Dedicated `/tags/{tag:name}` endpoint for tag‑scoped browsing.
  - Tag chips rendered via Blade components for consistent UX across the board.

- **Search experience**
  - `/search` endpoint orchestrated via a dedicated `SearchController`.
  - Designed for extending into multi‑field search (keyword, location, compensation bands, tag filters) without touching core routing.

- **Authenticated job publishing**
  - Authenticated users can:
    - Access `/jobs/create` to publish new positions.
    - Submit jobs via `POST /jobs` to the `JobController`.
  - Guest users are directed through registration and login flows before accessing publisher workflows.

- **User authentication**
  - Guest‑only routes for onboarding (`/register`, `/login`) governed by `guest` middleware.
  - Authenticated logout endpoint (`DELETE /logout`) governed by `auth` middleware.
  - Clear separation between anonymous browsing and authenticated publishing.

- **Modern Laravel tooling**
  - Laravel 12 application skeleton with opinionated defaults.
  - Vite‑based asset pipeline (see `vite.config.js`) for front‑end builds.
  - Pail‑powered application logs and queue workers integrated into the dev workflow.
  - First‑class testing stack via Pest and PHPUnit.

---

### Architecture Overview

- **Framework**
  - Laravel 12 (`laravel/framework:^12.0`)
  - PHP 8.2+ as baseline runtime

- **Core HTTP Layer**
  - `routes/web.php` defines:
    - `GET /` → `JobController@index` for the primary job feed.
    - `GET /jobs/create`, `POST /jobs` → job publishing, protected by `auth`.
    - `GET /search` → `SearchController` single‑action controller for search.
    - `GET /tags/{tag:name}` → `TagController` for tag‑driven listings.
    - Auth flows (`/register`, `/login`, `/logout`) handled by `RegisteredUserController` and `SessionController` with `guest` / `auth` middleware groups.

- **View Layer**
  - Blade components provide composable, semantic UI primitives:
    - `job-card` – encapsulates how a single job is presented:
      - Employer name
      - Job title linked to external `url`
      - Salary band
      - Associated tags via `x-tag`
      - Employer branding via `x-employer-logo`
    - The card is wrapped in `x-panel`, giving a consistent layout and hover behavior across listings.
  - Tailwind‑style utility classes are used to keep styles expressive and maintainable at the template level.

- **Supporting Infrastructure**
  - Queues and workers are wired by default (`php artisan queue:listen` in the dev script).
  - Application logging via Laravel Pail (`php artisan pail`) is integrated into the developer feedback loop.
  - Database migrations and seeders are bootstrapped through Laravel’s conventions.

---

### Tech Stack

- **Backend**
  - PHP 8.2+
  - Laravel 12
  - Laravel Queue (worker‑driven async jobs)
  - Laravel Pail (log streaming)

- **Frontend**
  - Blade templates & components
  - Vite asset bundling
  - (Typical Laravel stack: Tailwind CSS, Alpine.js or similar can be layered in as needed.)

- **Tooling & Quality**
  - Composer scripts to orchestrate setup, dev, and test flows.
  - Pest and PHPUnit for automated testing.
  - Laravel Pint for opinionated code style enforcement.
  - Collision for improved error output during development.

---

### Getting Started

#### 1. Requirements

- **PHP**: 8.2+
- **Composer**
- **Node.js**: 18+ (20+ recommended)
- **npm**

#### 2. Clone the repository

```bash
git clone https://github.com/Gelana3225/pixel-position-engine.git
cd pixel-position-engine
```

#### 3. One‑shot setup (recommended)

Use the provided Composer script to provision the environment, database, and front‑end build:

```bash
composer run setup
```

What this does:

- Installs PHP dependencies.
- Copies `.env.example` → `.env` if missing.
- Generates an application key.
- Runs database migrations (`php artisan migrate --force`).
- Installs Node dependencies.
- Builds front‑end assets (`npm run build`).

> If you prefer to run these steps manually, you can still follow the standard Laravel workflow.

#### 4. Configure environment

Edit `.env` according to your environment:

- **App**: `APP_NAME`, `APP_ENV`, `APP_URL`
- **Database**: `DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- **Queue / Cache**: `QUEUE_CONNECTION`, `CACHE_DRIVER`, etc.

Run migrations if you did not use `composer run setup`:

```bash
php artisan migrate
```

---

### Local Development Workflow

Use the `dev` Composer script to boot the full developer stack (HTTP server, queue worker, log tailing, Vite dev server) in a single command.

```bash
composer run dev
```

Under the hood, this:

- Disables Composer’s process timeout.
- Uses `npx concurrently` to start:
  - `php artisan serve`
  - `php artisan queue:listen --tries=1`
  - `php artisan pail --timeout=0` (live logs)
  - `npm run dev` (Vite)

After this is running:

- Visit the app at the URL printed by `php artisan serve` (typically `http://127.0.0.1:8000`).
- As a guest, you can browse the job list, search, and tag‑filtered listings.
- Register and log in to access the job publishing endpoints.

To stop the stack, interrupt the process (e.g., `Ctrl+C` in your shell).

---

### Testing

The project includes a modern testing toolchain with Pest and PHPUnit.

Run the test suite via the Composer script:

```bash
composer run test
```

This script:

- Clears configuration cache for a clean test context.
- Executes `php artisan test`, which runs both Pest and PHPUnit tests under Laravel’s testing harness.

You can extend the tests by adding:

- Feature tests under `tests/Feature`
- Unit tests under `tests/Unit`
- Database‑backed tests leveraging Laravel’s test helpers and factories.

---

### Project Structure (High‑Level)

- `app/`
  - Controllers (`JobController`, `SearchController`, `TagController`, `RegisteredUserController`, `SessionController`, …)
  - Models (e.g., `Job`, `Employer`, `Tag`, `User`)
  - Domain logic and application services.
- `routes/web.php`
  - All browser‑facing endpoints for the job engine and auth flows.
- `resources/views/`
  - Layouts and Blade templates for the UI.
  - Component library (e.g., `components/job-card.blade.php`, tag and panel components).
- `database/migrations/`, `database/seeders/`
  - Schema definition and initial data.
- `public/`
  - Web server document root and built assets.
- `vite.config.js`
  - Vite configuration for asset compilation.
- `composer.json`, `package.json`
  - PHP and Node dependency manifests and scripts.

---

### Deployment Notes

- **Build assets**

  ```bash
  npm install
  npm run build
  ```

- **Optimize and migrate**

  ```bash
  php artisan migrate --force
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```

- **Queues & workers**

  - Ensure a queue worker (e.g., `php artisan queue:work` or a supervisor-managed process) is running in production if you rely on queued jobs.
  - Configure `QUEUE_CONNECTION` appropriately for your environment (`database`, `redis`, etc.).

- **Environment**

  - Use environment‑specific `.env` values and never commit secrets.
  - Align `APP_URL` with the public URL of the deployed instance to avoid issues with redirects and asset URLs.

---

### Extensibility & Roadmap (Suggested)

Pixel Position Engine is intentionally minimal but structured to support richer capabilities, such as:

- Location‑aware search (country/region/city fields and filters).
- Saved searches and candidate profiles.
- Advanced employer dashboards (job performance metrics, click‑throughs).
- API endpoints for programmatic job ingestion and syndication.
- Webhooks or integrations with external ATS platforms.

The current codebase provides a clean Laravel 12 foundation with a consistent view layer and solid developer ergonomics, making it straightforward to iterate toward a more fully featured job marketplace.