FROM php:8.2-apache

# 1. Dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql

# 2. Activar mod_rewrite
RUN a2enmod rewrite

# 3. Definir DocumentRoot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# 4. Cambiar configuración de Apache
RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/000-default.conf

# Habilitar .htaccess correctamente
RUN sed -i 's|<Directory /var/www/>|<Directory /var/www/html/>|g' /etc/apache2/apache2.conf \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# 5. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Copiar proyecto
WORKDIR /var/www/html
COPY . .

# 7. Instalar dependencias
RUN composer install --no-dev --optimize-autoloader

# 8. Permisos
RUN chown -R www-data:www-data storage bootstrap/cache
