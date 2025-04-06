# Freelancer Platform Prototype

A MySQL-based freelancer platform with employer and freelancer dashboards.

## Features
- User authentication (signup/login)
- Job posting and browsing
- Application system
- Messaging between users
- Role-based dashboards

## Setup Instructions

### 1. Database Setup
```bash
# Initialize database
mysql -u root -e "CREATE DATABASE IF NOT EXISTS freelancer_platform"
mysql -u root freelancer_platform < init_db.sql
mysql -u root freelancer_platform < test_data.sql
```

### 2. Start Development Server
```bash
# Make script executable
chmod +x start_server.sh

# Run server
./start_server.sh
```

The application will be available at: http://localhost:8000

### 3. Test Accounts
**Freelancers:**
- Email: john@example.com
- Password: 12345

- Email: mike@example.com  
- Password: password

**Employers:**
- Email: sarah@example.com
- Password: password

- Email: lisa@example.com
- Password: 54321

## Project Structure
- `auth.php` - Authentication handler
- `dashboard-*.php` - Role-specific dashboards
- `apply.php` - Job application handler
- `process_application.php` - Application status updater
- `db_connect.php` - Database connection
- `init_db.sql` - Database schema
- `test_data.sql` - Sample data
- `start_server.sh` - Development server script

## Technical Stack
- Frontend: HTML5, CSS3 (Tailwind), JavaScript
- Backend: PHP
- Database: MySQL
