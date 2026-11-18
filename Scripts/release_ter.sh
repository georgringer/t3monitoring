#!/usr/bin/env bash

set -e

if [ -z $1 ]; then
    GIT_REF=$(git tag --sort version:refname | tail -1)
else
    GIT_REF="$1"
fi

EXT_VERSION=${GIT_REF/v/}
EXT_KEY=${PWD##*/}
ZIP_FILE_NAME="${EXT_KEY}_${EXT_VERSION}.zip"

echo "Creating zip for version $EXT_VERSION"

git archive -o $ZIP_FILE_NAME $GIT_REF

PHP_VERSION=8.5
TOOL_DIR=.Build/tools/tailor
TOOL_PACKAGE="typo3/tailor"
TOOL_COMMAND="tailor ter:publish --artefact $ZIP_FILE_NAME --comment 'See https://github.com/georgringer/t3monitoring/releases for release notes' -- $EXT_VERSION $EXT_KEY"

source Scripts/runphptool.sh

rm $ZIP_FILE_NAME
