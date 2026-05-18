#!/bin/bash
cd ~/www/es-teler
git pull origin main
composer install
php artisan migrate
php artisan config:clear
php artisan cache:clear
npm install
npm run build
