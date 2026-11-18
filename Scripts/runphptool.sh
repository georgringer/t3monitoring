#!/usr/bin/env bash

set -euo pipefail

TOOL_RUN_DIR=${TOOL_RUN_DIR:-.}

export PHP_VERSION TOOL_DIR TOOL_PACKAGE TOOL_RUN_DIR TOOL_COMMAND

containercommand=""
if [[ -x $(which "docker") ]]; then
    containercommand="docker compose"
fi
if [[ -x $(which "podman") ]]; then
    containercommand="podman compose"
fi
if [[ -x $(which "podman-compose") ]]; then
    containercommand="podman-compose"
fi

echo "### Using this container command: >$containercommand<"

composerinvoker() {
    if [[ -n $containercommand ]]; then
        $containercommand run --rm \
            -e "TOOL_DIR=$TOOL_DIR" \
            -e "TOOL_PACKAGE=$TOOL_PACKAGE" \
            tools $@
    else
        $@
    fi
}

phpinvoker() {
    if [[ -n $containercommand ]]; then
        $containercommand run --rm \
            -e "TOOL_DIR=$TOOL_DIR" \
            -e "TOOL_RUN_DIR=$TOOL_RUN_DIR" \
            -e "TOOL_COMMAND=$TOOL_COMMAND" \
            tools $@
    else
        $@
    fi
}

echo "### Installing tool"
composerinvoker Scripts/container/runcomposer.sh

echo "### Running tool"
phpinvoker Scripts/container/runphp.sh "$@"
result=$?

exit $result
