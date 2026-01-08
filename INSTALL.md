# Quick Installation Guide

## Prerequisites

Ensure you have the following installed:
- PHP >= 8.2
- Composer
- Node.js >= 18.x
- npm >= 9.x
- PostgreSQL >= 13.x

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

Edit `.env` file and update database credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=crmprof
DB_USERNAME=postgres
DB_PASSWORD=your_password_here
```

### 4. Create Database

```bash
# Using createdb command
createdb crmprof

# OR using psql
psql -U postgres -c "CREATE DATABASE crmprof;"
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Build Frontend Assets

```bash
# For development (with hot reload)
npm run dev

# For production
npm run build
```

### 7. Start Development Server

```bash
# Start Laravel server
php artisan serve

# Access at: http://localhost:8000
```

## Quick Start Commands

```bash
# Development workflow
composer install && npm install
cp .env.example .env
php artisan key:generate
# Configure .env database credentials
php artisan migrate
npm run dev  # In one terminal
php artisan serve  # In another terminal
```

## Troubleshooting

### PostgreSQL Connection Issues
```bash
# Check PostgreSQL is running
sudo service postgresql status

# Restart PostgreSQL
sudo service postgresql restart
```

### Permission Errors
```bash
chmod -R 775 storage bootstrap/cache
```

### Clear Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Next Steps

1. Review project structure in README.md
2. Create your first model and migration
3. Build Vue components in `resources/js/components/`
4. Add routes in `routes/web.php`
5. Implement business logic in `app/Services/`

For detailed documentation, see [README.md](README.md).
