# XAMPP MySQL Setup Guide for Campus Lost & Found

This guide will help you set up and migrate the database to MySQL in XAMPP.

## Prerequisites

- XAMPP installed and running
- MySQL/MariaDB service active in XAMPP Control Panel
- PHP 8.1+ configured properly

## Step 1: Start XAMPP Services

1. Open XAMPP Control Panel
2. Start the following services:
   - **Apache** - for the web server
   - **MySQL** - for the database

## Step 2: Create the MySQL Database

There are two methods to create the database:

### Method A: Using phpMyAdmin (Easiest)

1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click on "Databases" tab
3. Enter database name: `lost_and_found`
4. Click "Create"
5. (Optional) For testing, also create: `lost_and_found_test`

### Method B: Using MySQL Command Line

1. Open XAMPP Control Panel and click "Shell" button
2. Run these commands:
   ```sql
   mysql -u root
   ```
3. In the MySQL prompt, execute:
   ```sql
   CREATE DATABASE lost_and_found;
   CREATE DATABASE lost_and_found_test;
   EXIT;
   ```

## Step 3: Run Migrations

The configuration files have been updated to use MySQL. Run these commands in your project directory:

### Fresh Setup (Recommended)

```bash
php artisan migrate:fresh --seed
```

This will:
- Drop all existing tables
- Run all migrations in order
- Seed the database with test data (if seeders exist)

### Or Migrate Only

```bash
php artisan migrate
```

## Step 4: Verify Configuration

The following files have been automatically updated to use MySQL:

✅ **config/database.php**
- Default connection changed from `sqlite` to `mysql`

✅ **.env.example**
- Updated to show MySQL configuration
- Database: `lost_and_found`
- Host: `127.0.0.1`
- Port: `3306`
- Username: `root`
- Password: (empty)

✅ **phpunit.xml**
- Test database changed from in-memory SQLite to MySQL
- Test database: `lost_and_found_test`

✅ **config/queue.php**
- Updated default database connection from `sqlite` to `mysql`

✅ **composer.json**
- Removed SQLite file creation from post-install commands

## Step 5: Environment Configuration (.env)

Verify your `.env` file has these settings:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lost_and_found
DB_USERNAME=root
DB_PASSWORD=
```

## Step 6: Storage and Permissions

Ensure these directories are writable (Windows usually handles this):

```
storage/
storage/app/
storage/logs/
public/storage/
```

## Step 7: Test the Setup

Run the application with:

```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

## Running Tests

To run PHPUnit tests with the new MySQL configuration:

```bash
php artisan test
```

Or using PHPUnit directly:

```bash
php artisan test --filter TestName
```

## Troubleshooting

### Issue: "Connection refused" or "Cannot connect to MySQL"

**Solution:**
- Verify MySQL is running in XAMPP Control Panel
- Check that the database `lost_and_found` exists
- Verify credentials in `.env` match your MySQL setup

### Issue: "No tables in database"

**Solution:**
- Run migrations: `php artisan migrate:fresh --seed`

### Issue: Test database errors

**Solution:**
- Create the `lost_and_found_test` database
- Ensure it's configured in `phpunit.xml`

### Issue: "Unknown database character set 'utf8mb4'"

**Solution:**
- This is rare but if it occurs, update MySQL to version 5.7.7+
- Or temporarily downgrade to `utf8` in config/database.php

## Database Structure

The application includes the following tables (created by migrations):

- `users` - User accounts and authentication
- `lost_items` - Items reported as lost
- `found_items` - Items reported as found
- `item_matches` - Automatic matches between lost and found items
- `claim_requests` - Claims made on found items
- `departments` - University departments
- `password_reset_tokens` - Password reset tokens
- `sessions` - User sessions
- `jobs` - Queue jobs
- `job_batches` - Job batches
- `failed_jobs` - Failed queue jobs

## File Upload Storage

- Lost items: `storage/app/public/lost-items/`
- Found items: `storage/app/public/found-items/`

To make these accessible via web:

```bash
php artisan storage:link
```

## Additional Commands

View migration status:
```bash
php artisan migrate:status
```

Rollback migrations:
```bash
php artisan migrate:rollback
```

Refresh database (drop and re-run):
```bash
php artisan migrate:refresh
```

## Next Steps

1. ✅ Complete steps 1-7 above
2. Run `php artisan migrate:fresh --seed`
3. Start the application with `php artisan serve`
4. Access the application at `http://localhost:8000`

## Support

If you encounter issues:
- Check XAMPP Control Panel logs
- Review Laravel logs in `storage/logs/`
- Verify MySQL service is running
- Confirm database credentials in `.env`
