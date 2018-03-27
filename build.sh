#!/usr/bin/env bash

docker-compose pull
docker-compose stop

docker-compose rm -f app

docker-compose run -u www-data app composer install
if [ "$?" -ne "0" ]; then
    echo -e "\n\n\e[41mFailed to install composer dependencies\e[0m\n\n"
fi

docker-compose up -d --force-recreate --remove-orphans 2>&1
if [ "`docker-compose ps -q app`" = "" ]; then
    echo -e "\n\n\e[41mFailed to start[0m\n\n"
fi

docker-compose run -d -u www-data app php /var/www/console/sms-worker.php
if [ "$?" -ne "0" ]; then
    echo -e "\n\n\e[41mFailed to start worker\e[0m\n\n"
fi
