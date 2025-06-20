# Dockerfile

FROM php:8.4-fpm

# Atualiza sistema e instala dependências necessárias
RUN apt-get update && apt-get install -y \
    zip unzip curl libzip-dev libpq-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Instala o Composer diretamente da imagem oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório da aplicação
WORKDIR /var/www

# Copia os arquivos da aplicação (faremos isso depois da criação do projeto)
COPY . .

# Instala dependências PHP do Laravel
#RUN composer install --prefer-dist --no-scripts --no-autoloader

# Permissões corretas para storage/cache
#RUN chown -R www-data:www-data /var/www \
#    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
