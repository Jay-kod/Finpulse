# FinPulse — Deployment Guide

This guide explains how to deploy and run the FinPulse Sentiment Analysis platform in both development and production environments.

---

## Prerequisites

| Software       | Version    | Purpose                           |
| -------------- | ---------- | --------------------------------- |
| PHP            | >= 8.2     | Laravel backend                   |
| Composer       | >= 2.x     | PHP dependency management         |
| Node.js        | >= 18.x    | Frontend asset compilation        |
| npm            | >= 9.x     | Node dependency management        |
| Python         | >= 3.9     | FastAPI ML microservice           |
| pip            | >= 22.x    | Python dependency management      |
| MySQL / MariaDB| >= 8.0     | Database                          |

---

## Quick Start (Development)

### 1. Clone the repository
```bash
git clone <repository-url> finpulse
cd finpulse
```

### 2. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials:
```
DB_DATABASE=finpulse
DB_USERNAME=root
DB_PASSWORD=
NLP_SERVICE_URL=http://127.0.0.1:8001
```

### 3. Install dependencies
```bash
composer install
npm install
```

### 4. Set up the database
```bash
php artisan migrate
php artisan db:seed --class=FintechAppsSeeder
```

### 5. Start the Laravel server (Terminal 1)
```bash
php artisan serve --port=3000
```
The app will be available at `http://127.0.0.1:3000`.

### 6. Start the Vite dev server (Terminal 2)
```bash
npm run dev
```

### 7. Start the Python ML service (Terminal 3)
```bash
cd ml-service
pip install -r requirements.txt
python -m uvicorn main:app --host 127.0.0.1 --port 8001
```

### 8. (Optional) Run the pipeline scheduler
```bash
php artisan schedule:work
```

---

## Production Deployment

### Step 1: Build frontend assets
```bash
npm run build
```

### Step 2: Optimize Laravel
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan storage:link
```

### Step 3: Run the deploy script (Windows)
Or simply run the automated deployment script:
```powershell
.\deploy.ps1
```

### Step 4: Run the ML service as a background process

**Windows (using `nssm` or Task Scheduler):**
1. Open Task Scheduler
2. Create a new task
3. Set the action to: `python -m uvicorn main:app --host 127.0.0.1 --port 8001`
4. Set the working directory to `C:\path\to\finpulse\ml-service`
5. Set it to run at system startup

**Linux (using Supervisor):**
Create `/etc/supervisor/conf.d/finpulse-ml.conf`:
```ini
[program:finpulse-ml]
command=python -m uvicorn main:app --host 127.0.0.1 --port 8001
directory=/path/to/finpulse/ml-service
autostart=true
autorestart=true
stderr_logfile=/var/log/finpulse-ml.err.log
stdout_logfile=/var/log/finpulse-ml.out.log
```

Then:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start finpulse-ml
```

### Step 5: Set up the cron job (Scheduler)

**Windows:** Use Task Scheduler to run every minute:
```
php artisan schedule:run
```

**Linux:** Add to crontab:
```
* * * * * cd /path-to-finpulse && php artisan schedule:run >> /dev/null 2>&1
```

---

## Architecture Overview

```
┌─────────────────────────────────┐
│          Browser (User)         │
│  - Admin / Analyst / Viewer     │
└────────────┬────────────────────┘
             │ HTTP
             ▼
┌─────────────────────────────────┐
│   Laravel 11 Application       │
│   (Port 3000)                   │
│                                 │
│  • Auth & RBAC (Spatie)         │
│  • Dashboard & Analytics        │
│  • Review Management            │
│  • Report Generation            │
│  • App Store Sync (Jobs)        │
└────────────┬────────────────────┘
             │ HTTP (internal)
             ▼
┌─────────────────────────────────┐
│   FastAPI ML Microservice       │
│   (Port 8001)                   │
│                                 │
│  • /api/preprocess              │
│  • /api/classify                │
│  • /api/sentiment               │
│  • TF-IDF + LogReg / SVM       │
│  • NLTK VADER                   │
└─────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────┐
│   MySQL / MariaDB Database      │
│                                 │
│  • users, roles, permissions    │
│  • fintech_apps, datasets       │
│  • reviews (with NLP fields)    │
│  • reports, notifications       │
└─────────────────────────────────┘
```

---

## Default User Accounts

After seeding, the following test accounts are available:

| Role    | Email                  | Password   |
| ------- | ---------------------- | ---------- |
| Admin   | admin@finpulse.test    | password   |
| Analyst | analyst@finpulse.test  | password   |
| Viewer  | viewer@finpulse.test   | password   |

> **⚠️ Change these passwords immediately in production!**

---

## Environment Variables

| Key                | Default                    | Description                        |
| ------------------ | -------------------------- | ---------------------------------- |
| `NLP_SERVICE_URL`  | `http://127.0.0.1:8001`   | URL of the FastAPI ML service      |
| `DB_DATABASE`      | `finpulse`                 | MySQL database name                |
| `APP_ENV`          | `local`                    | Set to `production` for deployment |
| `APP_DEBUG`        | `true`                     | Set to `false` in production       |

---

## Troubleshooting

### ML service is not reachable
- Ensure the FastAPI server is running on port 8001
- Check `NLP_SERVICE_URL` in `.env`
- Run `php artisan config:clear` after changing `.env`

### SSL errors during app store sync
- This is a known issue with local XAMPP environments
- The system falls back to Apple App Store API automatically
- For production, ensure proper SSL certificates are installed

### Reviews are stuck in "pending"
- Ensure the ML service is running
- Run the pipeline manually: `php artisan reviews:preprocess && php artisan reviews:classify && php artisan reviews:sentiment`
