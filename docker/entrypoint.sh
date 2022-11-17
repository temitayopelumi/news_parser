#!/usr/bin/env bash
 
composer install -n
composer update
echo "bolu"
bin/console doc:mig:mig --no-interaction
bin/console doc:fix:load --no-interaction
 
exec "$@"