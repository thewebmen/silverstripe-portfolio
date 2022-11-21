ARG ALPINE_VERSION=3.16

ARG NODE_VERSION=18.12.1

FROM node:$NODE_VERSION-alpine$ALPINE_VERSION AS node
FROM php:8.1-cli-alpine$ALPINE_VERSION AS php-fpm

RUN apk update && apk upgrade\
   wget

RUN apk add php yarn --update
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache libstdc++
COPY --from=node /usr/local/bin/node /usr/local/bin/node
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s ../lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

RUN yarn install

WORKDIR /app
