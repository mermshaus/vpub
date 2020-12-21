#!/usr/bin/env bash

set -ue

server="localhost"
port=8080

scriptDir="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
serverDir="$(dirname "$scriptDir")/apps/server"
serverPublicDir="$serverDir/public"

echo "Starting server at $server:$port (docroot: $serverPublicDir)..."
echo "Press Ctrl+C to stop"
echo ""

php -S "$server:$port" -t "$serverPublicDir"
