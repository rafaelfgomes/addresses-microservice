#!/bin/bash

docker-compose down
docker container prune -f
docker image rm app-php
docker image rm app-mongodb
docker image rm app-webserver
docker volume prune -f