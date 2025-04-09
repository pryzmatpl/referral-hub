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

# Frontend README for refhub

## Project Overview
This repository contains the frontend application for the PRIZM project. Built with Vue.js, Webpack, and modern JavaScript, it provides a responsive, user-friendly interface designed to seamlessly integrate with the backend infrastructure.

## Prerequisites
- **Node.js**: Ensure Node.js (v16.x or higher) and npm are installed.
- **Webpack**: Bundles and optimizes assets for development and production.
- **Environment Variables**: Set up a `.env` file for environment-specific configurations.

## Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/pryzmatpl/referral-hub.git
   cd referral-hub/frontend
   ```
2. Install dependencies:
   ```bash
   npm install
   ```
3. Create a `.env` file in the root directory and populate it with:
   ```env
   VUE_APP_API_URL=http://localhost:80
   VUE_APP_DEBUG=true
   ```

## Scripts
The following npm scripts are available:
- **Development Mode**:
  ```bash
  npm run dev
  ```
- **Production Build**:
  ```bash
  npm run build
  ```
- **Unit Testing**:
  ```bash
  npm run test
  ```
- **Start Server**:
  ```bash
  npm run start
  ```
- **Update Dependencies**:
  ```bash
  npm run update:packages
  ```

## Directory Structure
- `src/`: Contains application source code.
    - `components/`: Vue.js components.
    - `assets/`: Static assets such as images and stylesheets.
    - `router/`: Application routing configuration.
    - `store/`: Vuex state management files.
    - `views/`: Main application views.
- `build/`: Webpack configuration files.
- `config/`: Environment-specific settings.
- `public/`: Static files served as-is.
- `test/`: Unit testing configuration and specs.

## Webpack Configuration
Webpack handles asset bundling and optimization. Key configurations are located in:
- `build/webpack.base.conf.js`: Common configuration.
- `build/webpack.dev.conf.js`: Development-specific configuration.
- `build/webpack.prod.conf.js`: Production-specific configuration.

## Development Workflow
1. Start the development server:
   ```bash
   npm run dev
   ```
2. Access the application at `http://localhost:8080`.
3. Modify files in the `src/` directory, and the changes will be reflected in real-time.

## Building for Production
1. Build the production-ready files:
   ```bash
   npm run build
   ```
2. The output will be stored in the `dist/` folder, ready for deployment.

## Testing
Unit tests are configured using Jest. Run tests with:
```bash
npm run test
```
Test files are located in the `test/` directory.

## Linting
Code linting is enforced via ESLint. Run linting with:
```bash
npm run lint
```

## Debugging
1. Start the development server using `npm run dev`.
2. Use browser dev tools to inspect components and network requests.
3. Vue DevTools extension is highly recommended for debugging Vue.js applications.

## Contribution
1. Fork the repository and create a feature branch.
2. Follow best practices for Vue.js and JavaScript.
3. Submit a pull request for review.

## License
**PRIZM Frontend** © Piotr Słupski 2023-2024. All rights reserved.

## Contact
For issues or feature requests, contact the maintainer:
- **Name**: Piotr Słupski
- **Email**: [piotr.slupski@pryzmat.pl](mailto:piotr.slupski@pryzmat.pl)

