#!/bin/sh

echo "APP_URL=${APP_URL}" >> /var/www/laravel/.env
echo "DB_USERNAME=${DB_USERNAME}" >> /var/www/laravel/.env
echo "DB_PASSWORD=${DB_PASSWORD}" >> /var/www/laravel/.env
echo "REDIS_PASSWORD=${REDIS_PASSWORD}" >> /var/www/laravel/.env
echo "WE_CHAT_APP_ID=${WE_CHAT_APP_ID}" >> /var/www/laravel/.env
echo "WE_CHAT_SECRET=${WE_CHAT_SECRET}" >> /var/www/laravel/.env
echo "QI_NIU_ACCESS_KEY=${QI_NIU_ACCESS_KEY}" >> /var/www/laravel/.env
echo "QI_NIU_SECRET_KEY=${QI_NIU_SECRET_KEY}" >> /var/www/laravel/.env
echo "BUCKET_NAME=${BUCKET_NAME}" >> /var/www/laravel/.env
echo "QI_NIU_DOMAIN=${QI_NIU_DOMAIN}" >> /var/www/laravel/.env
echo "YUN_PIAN_KEY=${YUN_PIAN_KEY}" >> /var/www/laravel/.env
echo "WECHAT_DOMAIN=${WECHAT_DOMAIN}" >> /var/www/laravel/.env
echo "ALI_ID=${ALI_ID}" >> /var/www/laravel/.env
echo "ALI_SECRET=${ALI_SECRET}" >> /var/www/laravel/.env
echo "ALI_ID=${ALI_ID}" >> /var/www/laravel/.env
echo "SEND_CLOUD_API_USER=${SEND_CLOUD_API_USER}" >> /var/www/laravel/.env
echo "SEND_CLOUD_APP_KEY=${SEND_CLOUD_APP_KEY}" >> /var/www/laravel/.env

exec "$@"
