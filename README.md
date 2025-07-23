[![Build Status](https://travis-ci.org/deregenboog/ecd.svg?branch=master)](https://travis-ci.org/deregenboog/ecd)

# Electronisch Cliëntendossier (ECD)

## Requirements

### All Platforms
- [Docker](https://docs.docker.com/)
- [Docker Composer](https://docs.docker.com/compose/)
- [GNU Make](https://www.gnu.org/software/make/)

### Platform-Specific Requirements

#### Linux/WSL2
No additional requirements - Docker Desktop or Docker Engine works out of the box.
Just uncomment the DOCKER_VOLUMES line in your .env(.dist) file

#### macOS (Recommended: Colima + Mutagen)
For optimal performance, use Colima instead of Docker Desktop:
(and leave DOCKER_VOLUME line commented out in .env(.dist))

```bash
# Install Colima (lightweight Docker alternative)
brew install colima

# Install Mutagen (fast file synchronization)
brew install mutagen-io/mutagen/mutagen

# Start Colima
colima start

# Verify Docker works
docker --version
docker-compose --version
```

#### Windows
Install Docker Desktop and Mutagen for file synchronization:

```powershell
# Install Mutagen (via Scoop package manager)
scoop install mutagen

# Or download from: https://github.com/mutagen-io/mutagen/releases
```

> **Note:** The [docker-compose.yml](docker-compose.yml) file references the Docker image `ecd_database` which should be built first. Please refer to its repository: [https://github.com/deregenboog/docker-ecd-database](https://github.com/deregenboog/docker-ecd-database) for instructions.

## Installation

- clone the repository: `git clone git@github.com:deregenboog/ecd.git`
- cd into the project directory: `cd ecd`
- build the image: `make docker-build`
- start the Docker-containers and start a Bash shell on the web container: `make docker-up`
- install PHP related dependencies using Composer: `make install`
- migrate database: `php bin/console doctrine:migrations:migrate --no-interaction`
- load database fixtures `php bin/console hautelook:fixtures:load --no-interaction`
- install web related dependencies using NPM (node package manager): `npm install` (if you have npm installed locally, otherwise do so)

The ECD web-application should now be accessible by pointing your web-browser to [http://localhost:8080/](http://localhost:8080/). PhpMyAdmin is available at port 81 for easy database access: [http://localhost:81/](http://localhost:81/) (user: ecd, password: ecd).

## Development Setup

This project uses a **dual PHP-FPM setup** for optimal development performance - combining fast development with debugging capabilities when needed.

### Platform-Specific File Synchronization

#### Linux/WSL2 Users
1. Uncomment volume mount in `docker-compose.yml`
2. Restart containers: `docker-compose up -d`

#### macOS/Windows Users (Performance Optimized)
1. Keep volumes commented out in `docker-compose.yml`
2. Install Mutagen: `brew install mutagen-io/mutagen/mutagen`
3. Start file sync: `mutagen project start`
4. Containers should already be running from installation

> **Note:** macOS/Windows users use Mutagen for file synchronization to avoid Docker volume performance issues.

### Development Modes

This setup automatically provides two development modes:

#### Fast Development (Default)
- Browse normally without special cookies
- Uses optimized PHP-FPM pool without Xdebug
- Fast response times (~200ms) comparable to production

#### Debug Mode
- Add `XDEBUG_SESSION=PHPSTORM` cookie (via browser extension)
- Automatically switches to debug PHP-FPM pool with Xdebug enabled
- Full debugging capabilities in your IDE
- Set ENABLE_PROFILER=true in .env to enable WDT and profiling

**Browser Extensions for Easy Switching:**
- Chrome: [Xdebug Helper](https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc)
- Firefox: [Xdebug Helper](https://addons.mozilla.org/en-US/firefox/addon/xdebug-helper-for-firefox/)

## Debugging Setup

### PhpStorm Configuration

#### Server Setup
1. **Settings** → **PHP** → **Servers**
2. **Add server:**
   ```
   Name: ecd-docker
   Host: localhost
   Port: 8080
   Debugger: Xdebug
   ☑️ Use path mappings
   ```

3. **Path Mappings:**
   ```
   Local Path: /path/to/your/ecd-project
   Server Path: /var/www/html
   ```

#### Debug Session
1. Set breakpoints in your PHP code
2. Start listening for PHP Debug Connections in PhpStorm
3. Enable Xdebug in browser (green bug icon in extension)
4. Refresh page - PhpStorm should stop at breakpoints

### Visual Studio Code Configuration

#### Required Extension
Install the **PHP Debug** extension by Xdebug:
1. Open Extensions (`Ctrl+Shift+X`)
2. Search for "PHP Debug" by Xdebug
3. Install the extension

#### Launch Configuration
Create/edit `.vscode/launch.json` in your project root:

```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/var/www/html": "${workspaceFolder}"
            },
            "log": true
        }
    ]
}
```

#### Debug Session
1. Set breakpoints by clicking in the gutter next to line numbers
2. Start debugging: `F5` or **Run → Start Debugging**
3. Enable Xdebug in browser (extension or cookie)
4. Refresh page - VS Code should stop at breakpoints

#### VS Code Debug Panel
- **Variables:** Inspect variable values at breakpoints
- **Call Stack:** See function call hierarchy
- **Watch:** Monitor specific expressions
- **Debug Console:** Execute PHP expressions in current context

### Troubleshooting Debugging

**Check which PHP pool is being used:**
```bash
# Fast pool (no debugging)
curl -I http://localhost:8080/ | grep X-FPM-Backend
# Should show: 127.0.0.1:9000

# Debug pool (with Xdebug)
curl -I -H "Cookie: XDEBUG_SESSION=PHPSTORM" http://localhost:8080/ | grep X-FPM-Backend
# Should show: 127.0.0.1:9001
```

**Common issues:**
- Breakpoints not working: Check path mappings in PhpStorm server config
- Still slow without debugging: Clear browser cookies or use incognito mode
- Connection failed: Ensure PhpStorm is listening on port 9003

## Architecture: Dual PHP-FPM Setup

This project uses an innovative dual PHP-FPM architecture to solve the common development dilemma of choosing between speed and debugging capabilities.

### How It Works

The setup runs **two parallel PHP-FPM processes** in a single container:
- **Fast Pool (port 9000):** Optimized for speed, no Xdebug overhead
- **Debug Pool (port 9001):** Full Xdebug capabilities for debugging

Nginx automatically routes requests based on the presence of an `XDEBUG_SESSION` cookie:

```nginx
set $fmp_backend "127.0.0.1:9000";  # Default: fast pool
if ($cookie_XDEBUG_SESSION) {
    set $fpm_backend "127.0.0.1:9001";  # Debug pool
}
```

### Benefits
✅ **Zero-configuration switching** between fast and debug modes  
✅ **Production-like performance** during normal development  
✅ **Full debugging power** when needed  
✅ **Resource efficient** - debug overhead only when debugging  
✅ **Team-friendly** - each developer chooses their own mode

### Configuration Files
- `docker/php/php-fast.ini` - PHP configuration without Xdebug
- `docker/php/php-debug.ini` - PHP configuration with Xdebug enabled
- `docker/php/pool-fast.conf` - PHP-FPM pool for fast development
- `docker/php/pool-debug.conf` - PHP-FPM pool for debugging
- `docker/php/start.sh` - Container startup script for both pools

## Tests

The test suite includes both unit tests and integration tests. The integration tests use a MySQL server running in a Docker container. Before running the tests the MySQL database needs to be built and seeded with data fixtures. To prevent this (slow) process to happen before each test, every test is wrapped in a transaction that is rolled back afterwards.

To run the full test suite run:

```
make docker-test-setup
make docker-test-run
make docker-test-teardown
```