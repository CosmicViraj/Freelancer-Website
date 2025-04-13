# Use PHP 8.1 with Apache
FROM php:8.1-apache

# Install PDO MySQL and SQLite support
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite

# Copy project files into container
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Set file permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
