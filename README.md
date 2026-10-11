# MedzSolutions

A Laravel 13 application with a React 19 interface built through Vite. The sample authentication flow includes account registration, session-based sign in, a protected dashboard, and sign out.

## Requirements

- PHP 8.3+
- Composer
- Node.js and npm

## Run locally

Install dependencies and prepare the database:

```bash
composer install
npm install
php artisan migrate
```

Start Laravel and Vite in separate terminals:

```bash
php artisan serve
```

```bash
npm run dev
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000), choose **Create an account**, and register to see the signed-in dashboard. Sign out to return to the login page. Login attempts are rate-limited.

For a production-style frontend build, run `npm run build`. Run the automated checks with `php artisan test`.
# MedzSolutions
