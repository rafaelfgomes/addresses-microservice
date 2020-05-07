#!/bin/bash

if [ ! -f .env ]; then

    cp .env.example .env

fi

appKey=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)

sed -i "s+APP_KEY=+APP_KEY=$appKey+g" .env

php-fpm