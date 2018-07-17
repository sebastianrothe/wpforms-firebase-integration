#!/bin/sh
rm -rf wpforms-firebase-integration/vendor
composer install --no-dev --optimize-autoloader --classmap-authoritative
cp .htaccess.deny.all wpforms-firebase-integration/vendor/.htaccess

zip -r -9 build/wpforms-firebase-integration-MASTER.zip wpforms-firebase-integration \
    -x */.DS_Store \
    -x */.git \
    -x */.svn \
    -x */.idea \
    -X */__MACOSX