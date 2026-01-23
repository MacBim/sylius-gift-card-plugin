#!/bin/sh

yarn install --pure-lockfile
GULP_ENV=prod yarn build
