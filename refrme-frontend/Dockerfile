# Use an official Node.js runtime as the base image
FROM node:20-alpine
RUN apk update && apk upgrade && apk add nginx
# Set the working directory
WORKDIR /app

# Copy package.json and package-lock.json (if present)
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the application code
COPY . .

# Build the application
RUN npm run build

COPY ./dist /usr/share/nginx/html/

# Copy custom nginx configuration
COPY ./docker/nginx.conf /etc/nginx/conf.d/default.conf

# Expose port 8080
EXPOSE 8080

# Start nginx
CMD ["nginx", "-g", "daemon off;"]
