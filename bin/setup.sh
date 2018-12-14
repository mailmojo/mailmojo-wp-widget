#!/usr/bin/env bash

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
SRC="${DIR}/src"

echo "Installing composer dependencies ... "
docker run --rm -ti -v $SRC:/app composer install --ignore-platform-reqs --no-scripts &>/dev/null
