# FinPulse Deployment Script for Windows
# Usage: Run this script from the project root directory
# .\deploy.ps1

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  FinPulse Deployment Script" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Step 1: Install PHP dependencies
Write-Host "[1/8] Installing PHP dependencies..." -ForegroundColor Yellow
composer install --optimize-autoloader --no-dev
Write-Host "  Done." -ForegroundColor Green

# Step 2: Install Node dependencies
Write-Host "[2/8] Installing Node dependencies..." -ForegroundColor Yellow
npm ci
Write-Host "  Done." -ForegroundColor Green

# Step 3: Build frontend assets
Write-Host "[3/8] Building frontend assets..." -ForegroundColor Yellow
npm run build
Write-Host "  Done." -ForegroundColor Green

# Step 4: Run database migrations
Write-Host "[4/8] Running database migrations..." -ForegroundColor Yellow
php artisan migrate --force
Write-Host "  Done." -ForegroundColor Green

# Step 5: Seed the database (apps only)
Write-Host "[5/8] Seeding fintech apps..." -ForegroundColor Yellow
php artisan db:seed --class=FintechAppsSeeder --force
Write-Host "  Done." -ForegroundColor Green

# Step 6: Clear and cache configs
Write-Host "[6/8] Optimizing application..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
Write-Host "  Done." -ForegroundColor Green

# Step 7: Set up storage link
Write-Host "[7/8] Creating storage link..." -ForegroundColor Yellow
php artisan storage:link 2>$null
Write-Host "  Done." -ForegroundColor Green

# Step 8: Install Python ML dependencies
Write-Host "[8/8] Setting up Python ML service..." -ForegroundColor Yellow
if (Test-Path "ml-service\requirements.txt") {
    Push-Location ml-service
    pip install -r requirements.txt
    Pop-Location
    Write-Host "  Done." -ForegroundColor Green
} else {
    Write-Host "  WARNING: ml-service/requirements.txt not found. Skipping." -ForegroundColor Red
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Deployment Complete!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "To start the application, run these commands in separate terminals:" -ForegroundColor White
Write-Host "  1. php artisan serve --port=3000" -ForegroundColor Gray
Write-Host "  2. cd ml-service && python -m uvicorn main:app --host 127.0.0.1 --port 8001" -ForegroundColor Gray
Write-Host ""
Write-Host "For automated pipeline scheduling (optional):" -ForegroundColor White
Write-Host "  php artisan schedule:work" -ForegroundColor Gray
