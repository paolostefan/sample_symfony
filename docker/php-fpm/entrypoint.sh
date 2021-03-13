#!/usr/bin/env bash
set -euo pipefail

export XDEBUG_CONFIG="remote_host=$(ip route show | awk '/default/ { print $3 }')"

exec "$@"