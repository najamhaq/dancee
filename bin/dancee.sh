#!/bin/sh

grep -R "use" /var/www/bookarb/current/app/Services/* \
| grep -v "Exception" \
| grep -v "Monolog" \
| grep -v "Models" \
| grep -v "Carbon" \
| grep -v "Illuminate" \
| php src/index.php
