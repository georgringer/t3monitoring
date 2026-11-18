#!/usr/bin/env bash

set -euo pipefail

mkdir -p $TOOL_DIR

cd $TOOL_DIR

if [[ ! -f composer.json ]]; then
    composer init --no-interaction --stability=stable --name="rx/tool"
    composer config --no-interaction allow-plugins true
    composer config --no-interaction lock false
fi
composer req --dev -a -W $TOOL_PACKAGE
