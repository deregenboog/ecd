[![Build Status](https://travis-ci.org/deregenboog/ecd.svg?branch=master)](https://travis-ci.org/deregenboog/ecd)

# Electronisch Cliëntendossier (ECD)

## Requirements

 - [Docker](https://docs.docker.com/)
 - [Docker Composer](https://docs.docker.com/compose/)
 - [GNU Make](https://www.gnu.org/software/make/)

The [docker-compose.yml](docker-compose.yml) file in this project references the Docker image `ecd_database` which should be built first. Please refer to its repository: [https://github.com/deregenboog/docker-ecd-database](https://github.com/deregenboog/docker-ecd-database) for instructions.

## Installation

 - clone the repository: `git clone git@github.com:deregenboog/ecd.git`
 - cd into the project directory: `cd ecd`
 - build the image: `make docker-build`
 - start the Docker-containers and start a Bash shell on the web container: `make docker-up`
 - install PHP related dependencies using Composer: `make install`
 - migrate database: `php bin/console doctrine:migrations:migrate --no-interaction`
 - load database fixtures `php bin/console hautelook:fixtures:load --no-interaction --purge-with-truncate`
 - install web related dependencies using NPM (node package manager): `npm install` (if you have npm installed locally, otherwise do so)

The ECD web-application should now be accessible by pointing your web-browser to [http://localhost/](http://localhost/). PhpMyAdmin is available at port 81 for easy database access: [http://localhost:81/](http://localhost:81/) (user: ecd, password: ecd).

## Tests

The test suite includes both unit tests and integration tests. The integration tests use a MySQL server running in a Docker container. Before running the tests the MySQL database needs to be built and seeded with data fixtures. To prevent this (slow) process to happen before each test, every test is wrapped in a transaction that is rolled back afterwards.

To run the full test suite run:

```
make docker-test-setup
make docker-test-run
make docker-test-teardown
```
