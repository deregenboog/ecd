[![Build Status](https://travis-ci.org/deregenboog/ecd.svg?branch=master)](https://travis-ci.org/deregenboog/ecd)

# Electronisch Cliëntendossier (ECD)

## Requirements

[Docker](https://docs.docker.com/) and
[Docker Composer](https://docs.docker.com/compose/) need to be installed on
your system.

## Installation

Clone the repository from [https://github.com/deregenboog/ecd](https://github.com/deregenboog/ecd) and cd into the project directory.

Start the Docker-containers by running

    docker-compose up --build

To start a Bash shell on the web-container run

    docker-compose exec web bash

The ECD web-application should now be accessible by pointing your web-browser to [http://localhost/](http://localhost/).

PhpMyAdmin is available at post 81 for easy database access: [http://localhost:81/](http://localhost:81/) (user: ecd, password: ecd).
