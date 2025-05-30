FROM node:16-alpine3.14 AS build-stage
WORKDIR /app
ENV NODE_OPTIONS="--max-old-space-size=8192"
COPY web .
RUN npm install --force
RUN npm rebuild esbuild
RUN npm run build

RUN rm -rf ./node_modules
RUN rm -rf ./src