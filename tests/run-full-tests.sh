#!/bin/bash

/var/www/vendor/bin/phpunit --coverage-html=coverage/
/var/www/vendor/bin/phpcs --standard=PSR2 /var/www/src/ --ignore="*/test/*,autoload_classmap.php,*.js"
# /var/www/vendor/bin/phpmd /var/www/src text phpmd.xml --exclude "*/test/*,*/autoload_classmap.php,*.js"
