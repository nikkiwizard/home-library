# I know I said mysql but through research, apache seems to be the way to go
FROM php:7.2-apache

# If we don't set this, apt-get prompts us to put in the timezone
ENV DEBIAN_FRONTEND=noninteractive

# Install ubuntu packages & MySQL server
RUN apt-get update
RUN apt-get install -y git php composer 
RUN apt-get install -y default-mysql-server
RUN apt-get install docker-php-ext-install mysqli pdo pdo_mysql
RUN rm -rf /var/lib/apt/lists/*

# Install PHP dependencies
RUN composer install --no-dev --prefer-source

# Put php app into directory
COPY . /var/www/html

CMD service mysql start && apache2-foreground
