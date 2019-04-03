#!bin/bash

php artisan down

git pull

composer install --no-dev
npm install

npm run production

php artisan config:cache
php artisan view:cache
php artisan route:cache

php artisan migrate

php artisan up
