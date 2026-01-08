# CRMProf - CRM System

A modern Customer Relationship Management system built with Laravel 11, Vue 3, and TypeScript.

## Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js 18+
- npm
- PostgreSQL 13+ (or SQLite for development)

## Quick Start

1. **Clone the Repository**
   ```bash
   git clone <repository-url>
   cd crmprof
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install Node Dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Note: Configure your database settings in .env*

5. **Database Setup**
   ```bash
   # For SQLite (default development)
   touch database/database.sqlite
   php artisan migrate --seed
   ```

6. **Start the Application**
   ```bash
   # Terminal 1: Laravel Server
   php artisan serve

   # Terminal 2: Vite Dev Server
   npm run dev
   ```

7. **Open in Browser**
   Navigate to [http://localhost:8000](http://localhost:8000)

## Login Test Credentials
- **Email:** test@crmprof.local
- **Password:** password

## Project Structure
- `app/` - Backend logic (Controllers, Models, Services)
- `resources/js/` - Frontend Vue 3 components and pages
- `database/migrations/` - Database schemas
- `routes/` - API and Web routes

## Modules

### Dashboard (Analytics)
- View key performance indicators (KPIs)
- Total orders, active drivers, available vehicles, and revenue
- Recent orders overview

### Orders
- Create, view, edit, delete orders
- Detailed order management (client info, addresses, dates)
- Status tracking (Pending, In Transit, Delivered, Cancelled)
- Responsive table with filtering

### Drivers
- Manage driver profiles
- Track contact information and license numbers
- Monitor driver status (Active, Inactive)
- Visual cards with driver details

### Vehicles
- Fleet management
- Track make, model, year, and plate numbers
- Monitor vehicle status (Available, Busy, Maintenance)
- Easy-to-read vehicle cards

## Troubleshooting

**Port 8000 already in use:**
```bash
php artisan serve --port=8001
```

**Database connection error:**
- Check `.env` settings
- Ensure your database service is running
- Verify the database exists

**npm run dev fails:**
- Clear node_modules: `rm -rf node_modules`
- Reinstall: `npm install`
- Check Node version: `node --version`

**Migrations fail:**
- Check `.env` database config
- Ensure database is empty or reset: `php artisan migrate:refresh`

## Code Style
- Follow PSR-12 coding standards for PHP
- Use TypeScript for all JavaScript code
- Use Vue 3 Composition API with `<script setup>` syntax
- Tailwind CSS for styling

## Support
For questions or issues, please contact the development team.
