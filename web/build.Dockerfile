FROM node:18-alpine

ENV NODE_OPTIONS="--max-old-space-size=8192"

WORKDIR /app

# Copy package files
COPY web/web/package.json web/web/package-lock.json* ./

# Debug: Show what we copied
RUN ls -la
RUN cat package.json

# Install dependencies
RUN npm install --verbose

# Debug: Show what was installed
RUN ls -la node_modules/

# Copy source code
COPY web/web ./

CMD ["npm", "run", "dev"]

# FROM node:18-alpine

# ENV NODE_OPTIONS="--max-old-space-size=8192"

# WORKDIR /app

# # Устанавливаем зависимости
# COPY web/web/package.json web/web/package-lock.json* ./
# RUN npm install --force

# # Устанавливаем pinia отдельно, если не в package.json
# # RUN npm install pinia

# # Install all dependencies
# RUN npm install --force

# # Copy the rest of the project
# COPY web/web ./

# # Install dependencies again to be sure
# RUN npm install --force

# # Запуск в dev-режиме (vite)
# CMD ["npm", "run", "dev", "--host", "0.0.0.0", "--port", "8099"]