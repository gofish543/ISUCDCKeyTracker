#!/bin/bash

php artisan down

git pull

composer install --no-dev
npm install

npm run production

php artisan config:cache
php artisan view:cache
php artisan route:cache

php artisan migrate

chown www-data:www-data -R ./

find ./ -type d -print0 | xargs -0 chmod 0770
find ./ -type f -print0 | xargs -0 chmod 0660

chmod artisan 0770
chmod deploy.bash 0770

php artisan up
