#!/usr/bin/env bash
composer install --ignore-platform-reqs --optimize-autoloader

tail -F -n 100 /var/www/workdir/storage/logs/logger.log
