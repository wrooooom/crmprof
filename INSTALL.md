# Quick Installation Guide

## Prerequisites

Ensure you have the following installed:
- PHP >= 8.2
- Composer
- Node.js >= 18.x
- npm >= 9.x
- PostgreSQL 13+ (or SQLite for development)

## Installation Steps

### 1. Clone and Install Dependencies

```bash
# Clone the repository
git clone <repository-url>
cd crmprof

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 2. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Configure Database

Edit `.env` file and update database credentials. For a quick start with SQLite:

```env
DB_CONNECTION=sqlite
```
And then create the database file:
```bash
touch database/database.sqlite
```

### 4. Run Migrations and Seed

```bash
php artisan migrate --seed
```

### 5. Start Development Server

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start Vite (Frontend)
npm run dev
```

## Demo Credentials
- **Email:** test@crmprof.local
- **Password:** password

## Troubleshooting

### Clearing Caches
If you encounter issues after updating, try clearing Laravel caches:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Permission Errors
```bash
chmod -R 775 storage bootstrap/cache
```

For more detailed information, see [README.md](README.md).
