FROM ubuntu:22.04

# Avoid interactive prompts during installation
ENV DEBIAN_FRONTEND=noninteractive

# Install required packages
RUN apt-get update && apt-get install -y \
    apache2 \
    php \
    php-mysql \
    mariadb-server \
    mariadb-client \
    && rm -rf /var/lib/apt/lists/*

# Configure MariaDB
RUN service mariadb start && \
    mysqld_safe & sleep 5 && \
    mysql -e "CREATE DATABASE testdb;" && \
    mysql -e "CREATE USER 'testuser'@'localhost' IDENTIFIED BY 'testpass';" && \
    mysql -e "GRANT ALL PRIVILEGES ON testdb.* TO 'testuser'@'localhost';"

# Configure Apache
RUN a2enmod rewrite

# Copy your PHP application
COPY index.php /var/www/html/
COPY test_db.php /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Create a custom Apache configuration file
RUN echo '<VirtualHost *:80>\n\
    ServerName localhost\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        AllowOverride All\n\
        Options Indexes FollowSymLinks\n\
        Require all granted\n\
    </Directory>\n\
    DirectoryIndex index.php index.html index.htm\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Expose ports
EXPOSE 80 3306

# Start both Apache and MariaDB
CMD service mariadb start && apache2ctl -D FOREGROUND