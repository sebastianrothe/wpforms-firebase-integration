#!/bin/sh
rm -rf wpforms-firebase-integration/vendor
composer install --no-dev --optimize-autoloader --classmap-authoritative
cp .htaccess.deny.all wpforms-firebase-integration/vendor/.htaccess