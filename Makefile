##################
# Variables
##################

DOCKER_COMPOSE = docker-compose -f ./docker-compose.yml

##################
# Docker compose
##################

build:
	${DOCKER_COMPOSE} build

start:
	${DOCKER_COMPOSE} start

stop:
	${DOCKER_COMPOSE} stop

up:
	${DOCKER_COMPOSE} up -d --remove-orphans

down:
	${DOCKER_COMPOSE} down

restart: stop start

ps:
	${DOCKER_COMPOSE} ps

logs:
	${DOCKER_COMPOSE} logs -f

down_remove:
	${DOCKER_COMPOSE} down -v --rmi=all --remove-orphans

##################
# App
##################

php:
	docker exec -it app_php bash

postgres:
	docker exec -it app_postgres bash
