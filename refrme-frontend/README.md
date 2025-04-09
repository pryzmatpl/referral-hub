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
3. Create an  `.env` file in the ./build directory and investigate the .env.example to set your environment

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

