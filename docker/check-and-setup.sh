#!/bin/bash

echo "Welcome to Musikcorps' wordpress-uberspace."
echo "Just making sure Wordpress and all dependencies are up to date..."

cd /var/www/virtual/docker/wordpress

if [[ -e vendor/bin/wp ]] && $(vendor/bin/wp core is-installed); then
  script/update
else
  echo "Installing Wordpress..."
  script/bootstrap
fi

echo "Now start the engines..."
