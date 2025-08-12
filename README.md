# Core PHP MVC

A lightweight, simple, and modern Core PHP MVC framework designed for fast and easy development without unnecessary complexity. It provides a clean MVC architecture, routing, middleware, database migrations & seeders, session-based authentication, and Tailwind CSS styling.

This framework comes with **prebuilt user authentication scaffolding**, including middleware, controllers, authentication pages (login, register), error pages (403, 404, 500), and a protected dashboard—all ready for you to customize and extend.

---

## Features

- Simple and intuitive MVC architecture (Controllers, Models, Views)
- Named routes and middleware support
- Prebuilt **user authentication** (login, registration, logout) with session management
- CSRF protection helpers (`csrf_token()`, `csrf_field()`)
- Custom CLI tool for managing migrations, seeders, controllers, middleware, views, and database
- Database migrations and seeders (e.g., users table)
- Error handling with custom 403, 404, and 500 error pages
- Blade-like PHP templating views
- Asset management for CSS and JS
- Modern UI styled with **Tailwind CSS**
- Middleware support (e.g., `AuthMiddleware`) for route protection

---

## Folder Structure

<details>
<summary>Project Folder Structure</summary>

```
├── .env                      # Environment variables for your local setup (not committed)
├── .env.example              # Template for .env, shows required env vars
├── .gitignore                # Specifies files and folders Git should ignore
├── package-lock.json         # Automatically generated file for npm dependency versions
├── package.json              # NPM configuration file with scripts and dependencies
├── README.md                 # Project README file (this file)
├── core/                     # Core framework classes and utilities
│   ├── App.php              # Main application bootstrap and setup
│   ├── Autoloader.php       # PSR-4 compliant class autoloader
│   ├── CLI.php              # Custom CLI tool for migrations, seeders, controllers, etc.
│   ├── Container.php        # Simple service container for dependency injection
│   ├── Controller.php       # Base controller class
│   ├── Env.php              # Environment variable loader and handler
│   ├── ErrorHandler.php     # Custom error and exception handling
│   ├── Middleware.php       # Base middleware class
│   ├── Model.php            # Base model class
│   ├── Request.php          # HTTP request abstraction
│   ├── Response.php         # HTTP response abstraction
│   ├── Router.php           # Routing system with support for named routes
│   └── View.php             # View renderer for PHP templates
├── database/                 # Database related files
│   ├── migrations/          # Migration scripts to create or modify database schema
│   │   └── 20250809101405_create_users_table.php # Migration for users table
│   └── seeders/             # Seeder scripts to populate database with test or default data
│       └── 20250809070008_UsersSeeder.php        # Seeder for users table data
├── app/                      # Application-specific code and resources
│   ├── Helpers.php           # Global helper functions
│   ├── Controllers/          # Controllers handling request logic
│   │   ├── AuthController.php      # Handles user authentication (login, register, logout)
│   │   ├── DashboardController.php # Controller for protected dashboard area
│   │   └── HomeController.php      # Controller for public home/welcome pages
│   ├── Middleware/           # Custom middleware classes
│   │   └── AuthMiddleware.php       # Middleware to protect routes (checks auth)
│   ├── Models/               # Database models
│   │   └── User.php                # User model representing users table
│   ├── Routes/               # Route definitions
│   │   └── web.php                 # Main web routes file
│   ├── Resources/            # Static assets and resources
│   │   ├── css/
│   │   │   └── app.css             # Tailwind CSS input and custom styles
│   │   └── js/
│   │       └── app.js              # JavaScript scripts
│   └── Views/                # View templates (PHP files rendered by View class)
│       ├── dashboard.php            # Dashboard page view
│       ├── welcome.php              # Public welcome/home page view
│       ├── auth/                   # Authentication-related views
│       │   ├── login.php            # Login form view
│       │   └── register.php         # Registration form view
│       └── errors/                 # Custom error pages
│           ├── 403.php             # Forbidden error page
│           ├── 404.php             # Not found error page
│           └── 500.php             # Server error page
├── public/                   # Publicly accessible files, web server root
│   ├── .htaccess             # Apache config for URL rewriting and security
│   ├── index.php             # Front controller, main entry point for all requests
│   └── build/                # Compiled frontend assets
│       └── css/
│           └── app.css        # Compiled Tailwind CSS output file
```

</details>

---

## Requirements

- PHP 8.0 or higher
- SQLite or MySQL (configured via `.env`)
- Node.js & npm (for Tailwind CSS build)
- Web server with URL rewriting enabled (Apache, Nginx, or PHP built-in server)

---

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/UmarAbbasX/core-php-mvc.git
   cd core-php-mvc
   ```

2. Set up your environment:

   ```bash
   cp .env.example .env
   # Edit .env to configure your database credentials and other settings
   ```

3. Install Node dependencies (for Tailwind CSS):

   ```bash
   npm install
   ```

4. Build Tailwind CSS assets:

   - For development with watching:

     ```bash
     npm run dev
     ```

   - For production build (minified):

     ```bash
     npm run build
     ```

5. Run database migrations:

   ```bash
   php core/CLI.php migrate
   ```

6. (Optional) Seed the database with initial data:

   ```bash
   php core/CLI.php db:seed
   ```

7. Start the PHP built-in server (or configure your web server):

   ```bash
   php -S localhost:8000 -t public
   ```

---

## Usage

- Visit [http://localhost:8000](http://localhost:8000) in your browser
- Register a new user or login using the prebuilt auth pages
- Access the protected dashboard page (requires login)
- Logout via the navbar link
- Modify routes in `app/Routes/web.php` as needed
- Customize views inside `app/Views`
- Add or extend middleware, controllers, models according to your app logic

---

## Debugging & Error Handling

This application uses an environment variable `APP_DEBUG` (set in the `.env` file) to control error display:

- **`APP_DEBUG=true`**  
  Shows detailed error messages with stack traces to help during development.

- **`APP_DEBUG=false`**  
  Displays simple, user-friendly error pages (403, 404, 500) for production environments to avoid exposing sensitive details.

Make sure to set this in your `.env` file:

```env
APP_DEBUG=true
```
**Important:** Toggle this to `false` before deploying to production.

## Commands (Custom CLI Tool)

This project includes a custom CLI tool `core/CLI.php` inspired by Laravel’s Artisan CLI, allowing you to manage migrations, seeders, middleware, controllers, views, and database operations efficiently.

| Command                                   | Description                   |
| ----------------------------------------- | ----------------------------- |
| `php core/CLI.php make:migration <name>`  | Create a new migration file   |
| `php core/CLI.php make:seeder <name>`     | Create a new seeder class     |
| `php core/CLI.php make:middleware <name>` | Create a new middleware class |
| `php core/CLI.php make:controller <name>` | Create a new controller class |
| `php core/CLI.php make:view <name>`       | Create a new view file        |
| `php core/CLI.php migrate`                | Run all pending migrations    |
| `php core/CLI.php migrate:rollback`       | Rollback the last migration   |
| `php core/CLI.php migrate:status`         | Show migration status         |
| `php core/CLI.php db:seed`                | Run all database seeders      |

### Examples

```bash
php core/CLI.php make:migration create_users_table
php core/CLI.php migrate
php core/CLI.php db:seed
```

---

## Tailwind CSS Integration

Tailwind CSS is used for modern, responsive styling. This project uses the official Tailwind CLI for easy and fast builds.

### NPM Scripts Included

```json
"scripts": {
  "build": "tailwindcss -i ./app/Resources/css/app.css -o ./public/build/css/app.css --minify --content ./app/Views/**/*.php,./app/Resources/js/**/*.js",
  "dev": "tailwindcss -i ./app/Resources/css/app.css -o ./public/build/css/app.css --watch --content ./app/Views/**/*.php,./app/Resources/js/**/*.js"
}
```

### How to Build CSS

- Development with watching:

  ```bash
  npm run dev
  ```

- Production minified build:

  ```bash
  npm run build
  ```

---

## Helpers

- `csrf_token()` and `csrf_field()` — CSRF protection for forms
- `redirect()` — easy HTTP redirects
- `route()` — generate URLs for named routes
- `asset()` — reference public assets (CSS, JS, images)

---

## Contributing

Contributions are welcome! Feel free to open issues or submit pull requests.

---

## License

MIT License © Umar Abbas

---

## Contact

- GitHub: [https://github.com/UmarAbbasX](https://github.com/UmarAbbasX)
- Discord: [https://discord.com/users/1246577121359433828](https://discord.com/users/1246577121359433828)
- LinkedIn: [https://linkedin.com/in/umarabbasx](https://linkedin.com/in/umarabbasx)

---
