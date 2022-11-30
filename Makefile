install:
	php composer.phar install
	cd public && npm install && node_modules/.bin/encore dev

test: install
	bin/console --env=test doctrine:schema:drop --full-database --force
	bin/console --env=test doctrine:schema:create
	bin/console --env=test app:fixtures:load
	vendor/bin/phpunit
