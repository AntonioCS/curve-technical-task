.PHONY: help
.DEFAULT_GOAL := help
proj-dir=./
dc-file=$(proj-dir)docker-compose.yml
dc=docker-compose --file $(dc-file)
php-service=php-fpm
webserver-service=webserver

setup: start php-composer-install php-run-tests ## Run this to setup everything up (should only run once)
	@echo Finished setup

start: ## Start containers
	$(dc) up -d

stop: ## Stop containers
	$(dc) stop

status: ## Status of containers
	$(dc) ps

php-bash: ## Bash php container
	$(dc) exec $(php-service) bash

php-composer-install: ## Run composer install
	$(dc) exec $(php-service) composer install

php-run-tests: ## Run tests
	$(dc) exec $(php-service) php bin/phpunit

redis-bash: ## Bash php container
	$(dc) exec redis sh

redis-list-items: ## List items in cache
	$(dc) exec redis sh -c "redis-cli KEYS *"

webserver-bash: ## Bash webserver container
	$(dc) exec $(webserver-service) bash

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
