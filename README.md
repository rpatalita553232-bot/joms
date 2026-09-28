# Job Order Management System

JOMS is a Laravel application for managing repair-shop customers, vehicles, staff, services, and job orders.

## Requirements

- PHP 8.3 or newer with the extensions required by Laravel
- Composer
- Node.js and npm
- MySQL-compatible database
- The hand-authored `joms_full_schema.sql` file supplied separately from this repository

## Fresh Setup

1. Clone the repository and install PHP dependencies:

	```shell
	composer install
	```

2. Create the local environment file and application key:

	```shell
	cp .env.example .env
	php artisan key:generate
	```

	On Windows PowerShell, use `Copy-Item .env.example .env` in place of `cp`.

3. Create an empty MySQL database, then set `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in `.env` to match it.

4. Import the supplied SQL schema into that empty database. From the directory containing the SQL file, run:

	```shell
	mysql -u your-database-user -p your-database-name < joms_full_schema.sql
	```

	The schema SQL is distributed separately and is not currently included in this repository. Do not run `php artisan migrate`; the database schema is managed by the supplied SQL file.

5. Install frontend dependencies and build the assets:

	```shell
	npm install && npm run build
	```

6. Start the local server:

	```shell
	php artisan serve
	```

	Visit the URL printed by Artisan.

### Initial Admin Account

- Email: `admin@bsa.test`
- Password: `password`

Change this password after the first login. These credentials must exist in the imported database; the schema SQL is maintained outside this repository.

## Features

- Role-based access for Admin/Secretary, Supervisor/Head Mechanic, and Mechanic
- Customer, vehicle, staff, and service management
- Job-order creation and mechanic assignment
- Supervisor approval and rejection with audit history
- Search and filtering for job orders
- Role-specific dashboards

## Out of Scope

- Parts or inventory management
- Payroll or accounting
- Automated vehicle diagnostics

## Laravel Cloud Preparation

This repository has no `.cloud` application/environment defaults. No Cloud resources or deployment were created because an application, environment, organization, and region were not specified. After selecting an existing Cloud database-backed environment, configure `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, and `APP_KEY` there. Attached Cloud database resources provide their connection variables; use those injected values rather than committing credentials. Since Cloud filesystems are ephemeral, configure sessions and cache to use attached persistent resources (for example `SESSION_DRIVER=redis` and `CACHE_STORE=redis` with an attached Valkey/Redis-compatible cache). Do not use the local `file` settings from `.env.example` for replicated production workloads.

Use the Laravel Cloud build command:

```shell
composer install --no-dev && npm run build
```

JOMS uses a hand-authored schema, so do not configure `php artisan migrate --force` as a deploy command. Provision/import the existing schema into the selected database before deployment. Keep sessions and cache on persistent database/Valkey resources rather than an ephemeral local filesystem. Monitor every deployment to completion and verify the environment URL and resource health.
