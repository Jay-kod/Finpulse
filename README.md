# Finpulse

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

**Finpulse** is a robust, highly modular platform designed to ingest, track, preprocess, and analyze thousands of user reviews for financial applications. By leveraging a micro-component architecture, the platform offers deep insights into user sentiment, allowing financial analysts and data scientists to easily understand application performance and user satisfaction over time.

## 🌟 Key Features

- **Extensible Architecture**: Built on top of Laravel 11 with 25 distinct, decoupled components handling everything from core layouts to complex Data Pipelines.
- **Role-Based Access Control (RBAC)**: Powered by `spatie/laravel-permission`, the platform defines strict roles (`Admin`, `Analyst`, `Viewer`), securing routes and API endpoints seamlessly.
- **REST API integration**: Programmatic access to tracked applications, datasets, and reports secured via Laravel Sanctum.
- **Dynamic Settings Engine**: A globally cached, database-driven settings manager supporting encrypted configuration values.
- **Audit Logging**: A polymorphic logging trait dynamically tracks attribute-level changes across models for security and compliance.
- **Comprehensive Testing**: Enforces a strict TDD methodology with a 100% pass rate across over 130 feature and unit tests.

## 🛠️ Technology Stack

- **Backend**: [Laravel 11](https://laravel.com/) (PHP 8.2+)
- **Frontend**: [Blade Templates](https://laravel.com/docs/blade) combined with [Alpine.js](https://alpinejs.dev/) for reactivity and [Tailwind CSS](https://tailwindcss.com/) for utility-first styling.
- **Database**: SQLite (configured for seamless local development and rapid in-memory testing).
- **Authentication**: Laravel Breeze & Laravel Sanctum.

## 🚀 Getting Started

Follow these instructions to get a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/Jay-kod/Finpulse.git
   cd Finpulse
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install NPM dependencies:**
   ```bash
   npm install
   npm run build
   ```

4. **Environment Setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Note: By default, the application is configured to use a local SQLite database for ease of use.*

5. **Database Migration & Seeding:**
   ```bash
   # Create the SQLite database file if it doesn't exist
   touch database/database.sqlite
   
   # Run migrations and seed the database with initial Roles and Admin users
   php artisan migrate --seed
   ```

6. **Serve the Application:**
   ```bash
   php artisan serve
   ```
   You can now access the platform at `http://localhost:8000`.

## 🧪 Running the Tests

The application has a comprehensive test suite utilizing an in-memory SQLite database to ensure fast and isolated execution.

To run the full test suite:
```bash
php artisan test
```

## 📚 Documentation

Deep-dive documentation regarding the architectural decisions, component responsibilities, and specific implementations can be found in the `/docs` directory.

- [Master Task List](docs/task.md)
- [Component Documentation](docs/components) (e.g., API, Audit Logs, Settings Engine)

## 🛡️ License

This project is proprietary software belonging to Jay-kod. All rights reserved.
