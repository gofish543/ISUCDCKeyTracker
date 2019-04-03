#!/bin/bash

php artisan down

git pull

composer install --no-dev
npm install

npm run production

php artisan config:cache
php artisan view:cache
php artisan route:cache

find ./ -type d -print0 | xargs -0 chmod 0770
find ./ -type f -print0 | xargs -0 chmod 0660

chmod 0770 artisan
chmod 0770 deploy.bash

npm rebuild

chown www-data:www-data -R ./

php artisan up
