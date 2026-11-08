#!/bin/sh
echo "Menunggu MySQL..."
while ! mysqladmin ping -h"database" --silent; do
    sleep 1
done
echo "MySQL Siap!"
exec "$@"