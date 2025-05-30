FROM node:16-alpine3.14 AS build-stage
WORKDIR /app
ENV NODE_OPTIONS="--max-old-space-size=8192"
COPY web .
RUN npm install --force
RUN npm rebuild esbuild
RUN npm run build

WORKDIR /app
FROM nginx:stable-alpine as production-stage
COPY --from=build-stage /app/dist /usr/share/nginx/html
COPY ./web/src/config/nginx.conf /etc/nginx/nginx.conf
COPY ./web/src/config/haproxy.cfg /etc/haproxy.cfg
RUN apk add  haproxy
# env

EXPOSE 80
EXPOSE 443
CMD ["nginx", "-g", "daemon off;"]
