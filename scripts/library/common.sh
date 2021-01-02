#!/usr/bin/env bash

##
# internal
##

function __common_setup_env {
    GLO_CLIENT_EXEC="$GLO_PROJECT_DIR/apps/client/app.php"
}

##
# export
##

function assert_command_exists {
    local command="$1"

    if ! command -v "$command" &> /dev/null; then
        throw_error "Required command \"$command\" could not be found"
    fi
}

function throw_error {
    local message="$1"

    echo "Error: $message"
    exit 1
}

# shellcheck disable=SC2034  # exported
GLO_CLIENT_EXEC=""

__common_setup_env
