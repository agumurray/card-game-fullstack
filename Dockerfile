FROM php:8.2-apache

# Instala extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Habilita mod_rewrite para Slim
RUN a2enmod rewrite

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia configuración personalizada de Apache
COPY ./apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# Copia el instalador de Composer (instalación oficial segura)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Ajusta permisos
RUN chown -R www-data:www-data /var/www/html
