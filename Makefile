install:
	php composer.phar install

docker-build:
	docker compose build

docker-up:
	docker compose up -d
	docker compose exec --user $$(id -u):$$(id -g) web bash

docker-stop:
	docker compose stop

docker-down:
	docker compose down

docker-test-setup:
	docker compose -f docker-compose.test.yml build
	docker compose -f docker-compose.test.yml up -d test-database && sleep 1
	docker compose -f docker-compose.test.yml run --rm test bin/console -n doctrine:migrations:migrate
	docker compose -f docker-compose.test.yml run --rm test bin/console -n hautelook:fixtures:load --purge-with-truncate

docker-test-run:
	docker compose -f docker-compose.test.yml build test
	docker compose -f docker-compose.test.yml run --rm test vendor/bin/phpunit $(tests)

docker-test-teardown:
	docker compose -f docker-compose.test.yml stop test-database
