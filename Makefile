install:
	php composer.phar install
	npm install && yarn encore dev

lint:
	vendor/bin/parallel-lint src/ tests/
	bin/console lint:twig templates/
	bin/console lint:yaml --parse-tags config/
	bin/console lint:container

test: install
	bin/console --env=test doctrine:schema:drop --full-database --force
	bin/console --env=test doctrine:schema:create
	PREVENT_SAVE_ENABLED=false bin/console --env=test -n hautelook:fixtures:load
	vendor/bin/phpunit

docker-build:
	docker compose build
	docker compose up --force-recreate --wait database && sleep 1
	docker compose run --rm php bin/console -n doctrine:migrations:sync-metadata-storage
	docker compose run --rm php bin/console -n doctrine:migrations:migrate
	docker compose run --rm -e PREVENT_SAVE_ENABLED=false php bin/console -n hautelook:fixtures:load
	docker compose run --rm php bin/console -n inloop:access:update

docker-up:
	docker compose up -d
	docker compose exec --user $$(id -u):$$(id -g) php bash

docker-up-debug:
	XDEBUG_MODE=debug,develop docker compose up -d
	docker compose exec --user $$(id -u):$$(id -g) php bash

docker-stop:
	docker compose stop

docker-down:
	docker compose down

docker-lint:
	docker compose -f docker-compose.test.yml build test
	docker compose -f docker-compose.test.yml run --rm test vendor/bin/parallel-lint src/ tests/
	docker compose -f docker-compose.test.yml run --rm test bin/console lint:twig templates/
	docker compose -f docker-compose.test.yml run --rm test bin/console lint:yaml --parse-tags config/
	docker compose -f docker-compose.test.yml run --rm test bin/console lint:container

docker-test-setup:
	docker compose -f docker-compose.test.yml build --build-arg UID=$$(id -u) --build-arg GID=$$(id -g)
	docker compose -f docker-compose.test.yml up --force-recreate --wait test-database && sleep 1
	docker compose -f docker-compose.test.yml run --rm test bin/console -n doctrine:migrations:migrate
	docker compose -f docker-compose.test.yml run --rm -e PREVENT_SAVE_ENABLED=false test bin/console -n hautelook:fixtures:load

docker-test-run:
	docker compose -f docker-compose.test.yml run --rm \
		--user $$(id -u):$$(id -g) \
		-e SYMFONY_DEPRECATIONS_HELPER=max[direct]=0 \
		-e XDEBUG_MODE=coverage \
		test vendor/bin/phpunit --coverage-html build/ $(tests)
	xdg-open build/index.html || true

docker-test-teardown:
	docker compose -f docker-compose.test.yml stop test-database

docker-test: docker-test-setup docker-test-run docker-test-teardown
