# Use an official PHP runtime as a parent image
FROM php:8.1-apache

# Install dependencies and enable necessary PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy your PHP project files into the container
COPY . /var/www/html/

# Expose port 80 to be able to access the application
EXPOSE 80
