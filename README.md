# BloodBankSystem — Blood Bank Management System

Laravel 12 blood bank management system with a public, database-driven frontend and a direct backend CRUD panel.

## Current scope
- No login/authentication is enabled at this stage.
- Existing `lifeblood_laravel` MySQL database is used.
- Frontend reads live data from the database.
- Backend supports Add, Edit/Update, Delete and status management for donors, hospitals, blood inventory, donations, appointments and blood requests.

## URLs
- `/` — public frontend
- `/dashboard` — public system dashboard
- `/admin` — backend CRUD management

## Database
Set `.env` to the existing database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lifeblood_laravel
DB_USERNAME=root
DB_PASSWORD=
SESSION_DRIVER=file
```

This version does not require the `sessions` table because sessions use Laravel's file driver.

## Run
```bash
php artisan optimize:clear
php artisan serve
```

## Demo / Presentation Data

The project includes a realistic Bangladesh-focused demo dataset for the frontend, dashboard and admin CRUD panel.

Run:

```bash
php artisan migrate
php artisan db:seed
php artisan optimize:clear
php artisan serve
```

The seeder creates demo donors, hospitals, blood inventory, donations, blood requests and appointments. The existing frontend is not redesigned; it reads the seeded records from the same database.

Admin demo account:
- Email: `admin@lifeblood.test`
- Password: `Admin@12345`

Demo donor/hospital passwords are `Donor@12345` and `Hospital@12345` respectively.
