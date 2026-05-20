FROM php:8.2-cli-bookworm

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git unzip zip curl ca-certificates gnupg \
    libzip-dev \
    default-mysql-client \
    chromium \
    fontconfig \
    libnss3 \
    libatk-bridge2.0-0 \
    libgtk-3-0 \
    libxss1 \
    libasound2 \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql zip \
    && node -v \
    && npm -v \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm ci
RUN npm run build

ENV APP_ENV=production
ENV PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium

CMD php artisan optimize:clear \
    && php artisan migrate --force \
    && php artisan storage:link || true \
    && mkdir -p storage/app/pptx/tmp storage/app/pptx/exports storage/app/charts storage/app/private/charts \
    && chmod -R 777 storage bootstrap/cache \
    && php artisan serve --host=0.0.0.0 --port=${PORT}
