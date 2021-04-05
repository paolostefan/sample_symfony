#!/usr/bin/env bash
set -euo pipefail

export XDEBUG_CONFIG="client_host=$(ip route show | awk '/default/ { print $3 }')"

# Per Symfony in esecuzione lato shell sotto utente developer
mkdir -p /var/www/html/guybrush/var/log /var/www/html/guybrush/var/cache
touch /var/www/html/guybrush/var/log/xdebug.log
chown -R developer: /var/www/html/guybrush

exec "$@"
