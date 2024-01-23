install:
	php composer.phar install
	cd public && npm install && node_modules/.bin/encore dev

test: install
	bin/console --env=test doctrine:schema:drop --full-database --force
	bin/console --env=test doctrine:schema:create
	PREVENT_SAVE_ENABLED=false bin/console --env=test -n hautelook:fixtures:load --purge-with-truncate
	vendor/bin/phpunit

docker-build:
	docker compose build
	docker compose up --force-recreate --wait database && sleep 1
	docker compose run --rm php bin/console -n doctrine:migrations:sync-metadata-storage
	docker compose run --rm php bin/console -n doctrine:migrations:migrate
	docker compose run --rm -e PREVENT_SAVE_ENABLED=false php bin/console -n hautelook:fixtures:load --purge-with-truncate
	docker compose run --rm php bin/console -n inloop:access:update

docker-up:
	docker compose up -d
	docker compose exec --user $$(id -u):$$(id -g) php bash

docker-stop:
	docker compose stop

docker-down:
	docker compose down

docker-test-setup:
	docker compose -f docker-compose.test.yml build
	docker compose -f docker-compose.test.yml up --force-recreate --wait test-database && sleep 1
	docker compose -f docker-compose.test.yml run --rm test bin/console -n doctrine:migrations:migrate
	docker compose -f docker-compose.test.yml run --rm -e PREVENT_SAVE_ENABLED=false test bin/console -n hautelook:fixtures:load --purge-with-truncate

docker-test-run:
	docker compose -f docker-compose.test.yml build test
	docker compose -f docker-compose.test.yml run --rm test vendor/bin/phpunit $(tests)

docker-test-teardown:
	docker compose -f docker-compose.test.yml stop test-database

docker-test:
	$(MAKE) docker-test-setup
	$(MAKE) docker-test-run
	$(MAKE) docker-test-teardown
