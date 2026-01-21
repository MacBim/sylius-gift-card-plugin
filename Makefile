include makefiles/variables.mk

start: up

stop: down

shell:
	${DOCKER_EXEC_CMD} app /bin/sh

build:
	${DOCKER_COMPOSE_CMD} build

up:
	$(DOCKER_COMPOSE_CMD) up -d

down:
	$(DOCKER_COMPOSE_CMD) down --volumes

rebuild: ## Rebuild project
	$(DOCKER_COMPOSE_CMD) up -d --build

cache-clear:
	$(SF_CONSOLE_CMD) cache:clear

phpunit:
	${PHP_CMD} vendor/bin/phpunit

phpspec:
	${PHP_CMD} vendor/bin/phpspec run --ansi --no-interaction -f dot

phpstan:
	${PHP_CMD} vendor/bin/phpstan analyse

cs-fix:
	${PHP_CMD} vendor/bin/php-cs-fixer fix

cs-check:
	${PHP_CMD} vendor/bin/php-cs-fixer check

install:
	${PHP_CMD} composer install --no-interaction --no-scripts

backend:
	${SF_CONSOLE_CMD} doctrine:database:drop --if-exists --force
	${SF_CONSOLE_CMD} sylius:install --no-interaction
	${SF_CONSOLE_CMD} doctrine:schema:update --force
	$(SF_CONSOLE_CMD) sylius:fixtures:load default --no-interaction

frontend:
	${PHP_CMD} cd tests/Application && yarn install --pure-lockfile
	${PHP_CMD} (cd tests/Application && GULP_ENV=prod yarn build)

behat:
	APP_ENV=test vendor/bin/behat --colors --strict --no-interaction -vvv -f progress

init: install backend frontend

ci: init phpstan cs-check phpunit

integration: init phpunit behat

static: install phpstan cs-check
