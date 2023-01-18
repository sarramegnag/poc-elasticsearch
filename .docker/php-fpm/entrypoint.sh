#!/bin/bash

uid=$(stat -c %u /app)
gid=$(stat -c %g /app)

if [ $uid == 0 ] && [ $gid == 0 ]; then
    if [ $# -eq 0 ]; then
        php-fpm --allow-to-run-as-root
    else
        echo "$@"
        exec "$@"
    fi
fi
  
sed -ri "s/www-data:x:[0-9]+:[0-9]+:/www-data:x:$uid:$gid:/g" /etc/passwd
sed -ri "s/www-data:x:[0-9]+:/www-data:x:$gid:/g" /etc/group

user=$(grep ":x:$uid:" /etc/passwd | cut -d: -f1)
if [ $# -eq 0 ]; then
    php-fpm
else
    echo gosu $user "$@"
    exec gosu $user "$@"
fi