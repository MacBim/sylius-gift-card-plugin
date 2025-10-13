DOCKER_CMD = docker

ENV ?= dev

DOCKER_COMPOSE_CMD = docker compose
DOCKER_EXEC_CMD = $(DOCKER_COMPOSE_CMD) exec

PHP_CMD = $(DOCKER_EXEC_CMD) -e APP_ENV=$(ENV) app

SF_CONSOLE_CMD = $(PHP_CMD) tests/Application/bin/console
