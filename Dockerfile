FROM yiisoftware/yii2-php:7.3-apache

WORKDIR /app

# Copia i file composer* e fai install (non update)
COPY composer.* /app/
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction || true

RUN mkdir -p /app/web/assets /app/runtime

RUN chown -R www-data:www-data /app/web/assets /app/runtime

RUN composer update

CMD ["apache2-foreground"]
