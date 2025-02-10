# Backend README for refhub

## Project Overview
This repository contains the backend infrastructure for the PRIZM project. It is built using PHP 8.2 and Docker, providing an environment for scalable, reliable, and secure web applications. The backend includes multiple services such as MySQL, Redis, Meilisearch, Selenium, and Mailhog, alongside robust development and debugging setups.

## Prerequisites
- **Docker**: Ensure Docker and Docker Compose are installed.
- **Environment Variables**: Create a `.env` file with the required variables. Use `.env.example` if available.
- **PHP 8.2**: For local CLI use, ensure PHP 8.2 is installed.

## Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/pryzmatpl/referral-hub
   cd referral-hub
   ```
2. Set up your environment variables by creating a `.env` file and populating it with:
   ```env
   APP_PORT=80
   DB_DATABASE=prism
   DB_USERNAME=root
   DB_PASSWORD=1tJ3nBCxy
   FORWARD_DB_PORT=3306
   FORWARD_REDIS_PORT=6379
   FORWARD_MEILISEARCH_PORT=7700
   FORWARD_MAILHOG_PORT=1025
   FORWARD_MAILHOG_DASHBOARD_PORT=8025
   MOCKSERVER_PORT=4000
   ```

## Docker Usage
### Starting Services
- **Production Mode**:
  ```bash
  make up
  ```
- **Debug Mode**:
  ```bash
  make debug
  ```

### Stopping Services
```bash
make down
```

### Cleaning Up
- Remove unused volumes:
  ```bash
  make clean
  ```
- Remove all unused resources:
  ```bash
  make prune
  ```

## CLI Usage
The backend includes a CLI tool for managing migrations and seeding:
- Run migrations:
  ```bash
  make migrate
  ```
- Seed the database:
  ```bash
  make seed
  ```

## Directory Structure
- `docker/`: Contains Dockerfiles and configuration for different services.
- `db/`: Migration and seed files for database management.
- `cli.php`: Entry point for custom CLI commands.
- `Makefile`: Automates common Docker operations.

## Services
### Aimatch
Main backend service built with PHP.
- **Ports**: `80`, `443`
- **Environment Variables**: Supports customization for upload limits, Redis host, and time zone.

### MySQL
Database service.
- **Image**: `mysql/mysql-server:8.0`
- **Ports**: `3306`
- **Healthcheck**: Monitors database health.

### Redis
Key-value store for caching.
- **Image**: `redis:alpine`
- **Ports**: `6379`
- **Healthcheck**: Ensures Redis is running.

### Meilisearch
Search engine for fast indexing and querying.
- **Image**: `getmeili/meilisearch:latest`
- **Ports**: `7700`

### Selenium
For browser automation and testing.
- **Image**: `selenium/standalone-chrome`
- **Ports**: `4444` (WebDriver), `5900` (VNC)

### Mailhog
Email testing tool.
- **Image**: `mailhog/mailhog:latest`
- **Ports**: `1025`, `8025`

### Mockserver
Mock server for API testing.
- **Image**: `node`
- **Port**: `4000`
- **Command**: Installs dependencies and starts the server.

### PhpMyAdmin
Web-based database management tool.
- **Image**: `phpmyadmin/phpmyadmin`
- **Port**: `8081`

## Debug Setup
Debugging is supported via separate Dockerfiles and Xdebug configuration.
- Use `make debug` to start the debug environment.
- Xdebug settings can be customized in `docker/8.2/config/xdebug.ini`.

## Development Workflow
1. Start the services (`make up` or `make debug`).
2. Use the CLI for migrations and seeding.
3. Access services via:
    - Application: `http://localhost`
    - PhpMyAdmin: `http://localhost:8081`
    - Mailhog: `http://localhost:8025`
4. Debug using your preferred IDE with Xdebug enabled.

## Contribution
1. Fork the repository and create a feature branch.
2. Follow best practices for PHP and Docker.
3. Submit a pull request for review.

## License
**PRIZM** © Piotr Słupski 2023-2024. All rights reserved.

## Contact
For issues or feature requests, contact the maintainer:
- **Name**: Piotr Słupski
- **Email**: [piotr.slupski@pryzmat.pl](mailto:piotr.slupski@pryzmat.pl)

