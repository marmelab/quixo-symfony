.PHONY: help install run stop test

.DEFAULT_GOAL := help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

composer-install: ## Run composer install within the host
	docker-compose run --rm \
		php bash -ci '/usr/bin/composer install'

composer-update: ## Run composer update within the host
	docker-compose run --no-deps --rm \
		php bash -ci '/usr/bin/composer update'

init-db: ## Create and setup the database
	docker-compose run --rm \
		php bash -ci './bin/console doctrine:database:create --if-not-exists && ./bin/console doctrine:schema:update --force'

go-install:
	docker-compose run --no-deps --rm \
		advisor go get -u -v github.com/githubnemo/CompileDaemon

install:
	$(MAKE) composer-install
	$(MAKE) init-db
	$(MAKE) go-install

update: composer-update ## Update docker environnement

start: ## Start the server
	docker-compose up -d

stop: ## Stop the server
	docker-compose down

build: ## Build the dockers images
	docker-compose build

test: ## Test the code
	docker-compose run --rm \
		php bash -ci bin/phpunit
