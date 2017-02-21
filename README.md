# Electronisch Cliëntendossier (ECD)

## Requirements

[Docker](https://www.docker.com/) needs to be installed on your system.

## Installation

Checkout the repository from [https://github.com/deregenboog/ecd](https://github.com/deregenboog/ecd) and cd into the project directory.

Build and start the Docker-containers and start a Bash-shell on the web-container:

    bin/docker-up.sh

Install dependencies using Composer:

    ./composer.phar install

Update db-schema:

    bin/console doctrine:migrations:migrate

Load db-fixtures (this may take a while):

    bin/console hautelook_alice:doctrine:fixtures:load

The ECD web-application should now be accessible by pointing your web-browser to [http://0.0.0.0/](http://0.0.0.0/). PhpMyAdmin is available at post 81 for easy database access: [http://0.0.0.0:81/](http://0.0.0.0:81/) (user: ecd, password: ecd).

To quit simply `exit` the web-container and stop all three Docker-containers by running:

    bin/docker-stop.sh
