FROM composer as composer
ARG LARAVEL_PATH=/app/laravel
COPY . ${LARAVEL_PATH}
RUN cd ${LARAVEL_PATH} \
    && composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/  \
    && composer install \
       --no-interaction \
       --no-plugins \
       --no-scripts \
       --prefer-dist

FROM php:7.2.33-fpm-alpine3.12 as laravel
ARG LARAVEL_PATH=/var/www/laravel
COPY --from=composer /app/laravel ${LARAVEL_PATH}
RUN cd ${LARAVEL_PATH} \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && mkdir -p ${LARAVEL_PATH}/storage \
    && mkdir -p ${LARAVEL_PATH}/storage/framework/cache \
    && mkdir -p ${LARAVEL_PATH}/storage/framework/sessions \
    && mkdir -p ${LARAVEL_PATH}/storage/framework/testing \
    && mkdir -p ${LARAVEL_PATH}/storage/framework/views \
    && mkdir -p ${LARAVEL_PATH}/storage/logs \
    && chmod -R 777 ${LARAVEL_PATH}/storage \
    && php artisan package:discover \
    && cp ${LARAVEL_PATH}/.env.example ${LARAVEL_PATH}/.env \
    && cp ${LARAVEL_PATH}/.env.example ${LARAVEL_PATH}/.env \
    && php ${LARAVEL_PATH}/artisan key:generate \
    && php ${LARAVEL_PATH}/artisan jwt:secret \
    && mv ${LARAVEL_PATH}/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh \
    && chmod 777 /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]

FROM nginx:stable-alpine-perl as nginx
ARG LARAVEL_PATH=/var/www/laravel
COPY --from=laravel ${LARAVEL_PATH}/public ${LARAVEL_PATH}/public
COPY laravel.conf /etc/nginx/conf.d/
EXPOSE 8000
