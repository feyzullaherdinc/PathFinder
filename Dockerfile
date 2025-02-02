# Resmi Laravel PHP görüntüsünü kullan
FROM php:8.2-fpm

# Çalışma dizinini belirle
WORKDIR /var/www

# Gerekli paketleri yükle
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql gd

# Composer yükle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Laravel bağımlılıklarını yükle
COPY . .
RUN composer install

# Laravel'in çalışma izinlerini ayarla
RUN chmod -R 777 storage bootstrap/cache

CMD ["php-fpm"]
