FROM php:8.4-fpm-alpine AS runtime
WORKDIR /var/www/html

RUN apk update && apk add --no-cache \
    nginx \
    supervisor \
    mysql-client

COPY docker/default.conf /etc/nginx/http.d/default.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

COPY src/ /var/www/html/

EXPOSE 80
ENTRYPOINT ["entrypoint.sh"]
CMD ["supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]