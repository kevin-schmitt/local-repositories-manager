#########################################
##       MAKEFILE FOR MASSIMPORT       ##
#########################################

DOCKER_COMPOSE = docker-compose
PHP-APP = php

#####
# Stop containers
stop:
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) stop

install:
	$(DOCKER_COMPOSE) up -d --build
	$(DOCKER_COMPOSE) exec $(PHP-APP) cp .env.dist .env && exit
	make composer

composer:
	$(DOCKER_COMPOSE) exec $(PHP-APP) composer install

start:
	$(DOCKER_COMPOSE) up -d

#####
# Start new bash terminal inside the php-app Container
sh:
	make start
	$(DOCKER_COMPOSE) exec $(PHP-APP) sh

csfixer:
	make start
	$(DOCKER_COMPOSE) exec $(PHP-APP) ./vendor/bin/php-cs-fixer fix --config=.php_cs

# Execute unit tests
phpunit:
	make start
	$(DOCKER_COMPOSE) exec $(PHP-APP) ./bin/phpunit

