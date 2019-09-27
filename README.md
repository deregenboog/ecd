[![Build Status](https://travis-ci.org/deregenboog/ecd.svg?branch=master)](https://travis-ci.org/deregenboog/ecd)

# Electronisch Cliëntendossier (ECD)

## Requirements

[Docker](https://docs.docker.com/) and
[Docker Composer](https://docs.docker.com/compose/) need to be installed on
your system.

The [Docker Compose configuration file](docker-compose.yml) in this project references the Docker image `ecd_database` which should be built first. Please refer to its repository: [https://github.com/deregenboog/docker-ecd-database](https://github.com/deregenboog/docker-ecd-database) for instructions.

## Installation

 - clone the repository: `git clone git@github.com:deregenboog/ecd.git`
 - cd into the project directory: `cd ecd`
 - build the image: `bin/docker-build.sh`
 - For MacOS: install docker-sync (see http://docker-sync.io)
 - Check configuration in docker-sync.yml
 - start docker-sync container: `docker-sync start`
 - start the Docker-containers and start a Bash shell on the web container: `bin/docker-up.sh`
 - install PHP related dependencies using Composer: `./composer.phar install`
 - migrate database: `bin/console doctrine:migrations:migrate --no-interaction`
 - load database fixtures `bin/console fixtures:load --no-interaction --purge-with-truncate`
 - install web related dependencies using NPM (node package manager): `cd app/webroot/ npm install` (if you have npm installed locally, otherwise do so)

The ECD web-application should now be accessible by pointing your web-browser to [http://localhost/](http://localhost/). PhpMyAdmin is available at port 81 for easy database access: [http://localhost:81/](http://localhost:81/) (user: ecd, password: ecd).
