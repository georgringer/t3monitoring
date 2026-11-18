#!/usr/bin/env bash

if [[ -x $(which "podman-compose") ]]; then
    composecommand="podman-compose"
else
    composecommand="podman compose"
fi

$composecommand run --rm tools composer "$@"
result=$?
$composecommand down
exit $result
