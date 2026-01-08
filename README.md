# crmprof - CRM System

A modern Customer Relationship Management system built with Laravel 11, Vue 3, and TypeScript.

## Tech Stack

### Backend
- **Laravel 11** - PHP framework for web applications
- **PHP 8.2+** - Server-side programming language
- **PostgreSQL** - Relational database management system

### Frontend
- **Vue 3** - Progressive JavaScript framework with Composition API
- **TypeScript** - Typed superset of JavaScript
- **Vite** - Next-generation frontend build tool
- **Tailwind CSS** - Utility-first CSS framework

### Development Tools
- **Composer** - PHP dependency manager
- **npm** - Node package manager
- **Laravel Vite Plugin** - Laravel integration for Vite

## Project Structure

```
crmprof/
├── app/
│   ├── Console/         # Artisan commands
│   ├── Exceptions/      # Exception handlers
│   ├── Http/
│   │   ├── Controllers/ # HTTP controllers
│   │   └── Middleware/  # HTTP middleware
│   ├── Models/          # Eloquent models
│   └── Services/        # Business logic services
├── bootstrap/           # Framework bootstrap files
├── config/              # Configuration files
├── database/
│   ├── factories/       # Model factories
│   ├── migrations/      # Database migrations
│   └── seeders/         # Database seeders
├── public/              # Public assets
├── resources/
│   ├── css/             # CSS files
│   ├── js/
│   │   ├── components/  # Vue components
│   │   ├── pages/       # Page components
│   │   ├── layouts/     # Layout components
│   │   ├── types/       # TypeScript type definitions
│   │   ├── app.ts       # Main JavaScript entry point
│   │   └── bootstrap.ts # Bootstrap JavaScript
│   └── views/           # Blade templates
├── routes/
│   ├── api.php          # API routes
│   ├── console.php      # Console routes
│   └── web.php          # Web routes
├── storage/             # Application storage
├── tests/               # Automated tests
└── vendor/              # Composer dependencies
```

## Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18.x
- npm >= 9.x
- PostgreSQL >= 13.x

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd crmprof
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install JavaScript Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the example environment file and configure it:

```bash
cp .env.example .env
```

Update the `.env` file with your database credentials:

```env
APP_NAME=crmprof
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=crmprof
DB_USERNAME=postgres
DB_PASSWORD=your_password_here
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Database Setup

Create a PostgreSQL database:

```bash
# Using psql
createdb crmprof

# Or using PostgreSQL command line:
psql -U postgres
CREATE DATABASE crmprof;
\q
```

Run migrations:

```bash
php artisan migrate
```

### 7. Build Frontend Assets

For development:

```bash
npm run dev
```

For production:

```bash
npm run build
```

## Development

### Running the Development Server

Start the Laravel development server:

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`.

In a separate terminal, start the Vite dev server:

```bash
npm run dev
```

### Available Commands

#### PHP/Laravel Commands

```bash
# Start development server
php artisan serve

# Run database migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Seed database
php artisan db:seed

# Create a new migration
php artisan make:migration create_table_name

# Create a new model
php artisan make:model ModelName

# Create a new controller
php artisan make:controller ControllerName

# Run tests
php artisan test

# Clear application cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### Frontend Commands

```bash
# Install dependencies
npm install

# Start development server
npm run dev

# Build for production
npm run build

# Run type checking
npm run type-check
```

## Deployment

### Building for Production

1. Set the environment to production in `.env`:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

2. Install dependencies:
   ```bash
   composer install --optimize-autoloader --no-dev
   npm install
   ```

3. Build frontend assets:
   ```bash
   npm run build
   ```

4. Optimize Laravel:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

5. Run migrations:
   ```bash
   php artisan migrate --force
   ```

### TimeWeb Hosting Compatibility

This application is designed to be compatible with TimeWeb hosting:

- Standard PHP 8.2 support
- PostgreSQL database support
- Standard Laravel deployment process
- No special server requirements

Ensure your TimeWeb hosting plan includes:
- PHP 8.2 or higher
- PostgreSQL database access
- Composer support
- Node.js for building assets (can be done locally and deployed)

## Future Modules

This project is the foundation for a comprehensive CRM system. Planned modules include:

1. **Orders Module** - Order management and tracking
2. **Drivers Module** - Driver management and scheduling
3. **Vehicles Module** - Fleet management
4. **Clients Module** - Customer relationship management
5. **Tracking Module** - Real-time tracking and monitoring

## Code Style

- Follow PSR-12 coding standards for PHP
- Use TypeScript for all JavaScript code
- Use Vue 3 Composition API with `<script setup>` syntax
- Use meaningful variable and function names
- Write comments for complex logic

## Testing

Run PHP tests:

```bash
php artisan test
```

Run tests with coverage:

```bash
php artisan test --coverage
```

## Troubleshooting

### Common Issues

**Issue: "could not find driver" error**
- Solution: Install PostgreSQL PHP extension: `sudo apt-get install php8.2-pgsql`

**Issue: Vite not building assets**
- Solution: Clear npm cache and reinstall: `rm -rf node_modules package-lock.json && npm install`

**Issue: Database connection error**
- Solution: Check PostgreSQL is running and credentials in `.env` are correct

**Issue: Permission denied errors**
- Solution: Set proper permissions:
  ```bash
  chmod -R 775 storage bootstrap/cache
  chown -R www-data:www-data storage bootstrap/cache
  ```

## Contributing

1. Create a feature branch
2. Make your changes
3. Write tests for new functionality
4. Ensure all tests pass
5. Submit a pull request

## License

This project is proprietary software. All rights reserved.

## Support

For questions or issues, please contact the development team.
