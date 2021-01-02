#!/usr/bin/env bash

set -eu

scriptDir="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
GLO_PROJECT_DIR="$( dirname "$scriptDir")"

pushd "$GLO_PROJECT_DIR/scripts/library" >/dev/null

. "common.sh"

popd >/dev/null

assert_command_exists "eog"

key="$1"
value="$2"

files=""

while IFS= read -r line; do
    files="$files ${line@Q}"
done <<< "$($GLO_CLIENT_EXEC c:g "$key" "$value")"

eval "eog $files"
