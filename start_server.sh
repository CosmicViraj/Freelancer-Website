#!/bin/bash

# Start MySQL service (if not already running)
sudo service mysql start

# Initialize database
echo "Initializing database..."
mysql -u root -e "CREATE DATABASE IF NOT EXISTS freelancer_platform"
mysql -u root freelancer_platform < init_db.sql
mysql -u root freelancer_platform < test_data.sql

# Start PHP development server
echo "Starting PHP development server on port 8000..."
php -S localhost:8000