#!/usr/bin/env bash

set -euo pipefail

ABS_RUN_DIR="$(realpath $TOOL_RUN_DIR)"
ABS_TOOL_DIR="$(realpath $TOOL_DIR)"
cd $ABS_RUN_DIR
echo "working dir: $(pwd)"
php -d memory_limit=512M $ABS_TOOL_DIR/vendor/bin/$TOOL_COMMAND "$@"
