# Deployment

- PHP 8.3+
- MySQL 8
- Composer
- Node.js
- Laravel 13

## Langkah
1. composer install
2. npm install && npm run build
3. cp .env.example .env
4. php artisan key:generate
5. php artisan migrate --seed
6. php artisan storage:link
