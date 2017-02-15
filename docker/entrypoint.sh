#!/bin/bash

# Wait for MySQL to become available
echo "Waiting for database at host mysql to become ready..."
while true; do
  mysql -h mysql -u root -pa -e "select 1" >/dev/null 2>&1 && break
  sleep 1
done

# check if Wordpress needs to be installed
FOLDER=$(dirname $0)
sudo -u www-data $FOLDER/check-and-setup.sh

exec "$@"
