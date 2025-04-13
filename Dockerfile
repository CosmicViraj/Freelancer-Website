# Use official PHP image
FROM php:8.2-cli

# Set working directory
WORKDIR /var/www/html

# Copy all files to container
COPY . .

# Expose the port (same as Render start command port)
EXPOSE 10000

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:10000"]
