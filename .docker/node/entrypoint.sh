#!/bin/bash

uid=$(stat -c %u /app)
gid=$(stat -c %g /app)

sed -ri "s/node:x:[0-9]+:[0-9]+:/node:x:$uid:$gid:/g" /etc/passwd
sed -ri "s/node:x:[0-9]+:/node:x:$gid:/g" /etc/group
chown -R node:node /home/node

user=$(grep ":x:$uid:" /etc/passwd | cut -d: -f1)
if [ $# -eq 0 ]; then
    echo exec "$@"
    exec exec "$@"
else
    echo gosu $user "$@"
    exec gosu $user "$@"
fi