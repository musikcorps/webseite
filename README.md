# Wordpress project layout

## Inspired by:
 * [roots/bedrock](https://github.com/roots/bedrock)
 * [johnpbloch/wordpress](https://github.com/johnpbloch/wordpress)
 * [Seravo/wordpress](https://github.com/Seravo/wordpress)


## Local deployment

Edit `config/docker.env` according to your site. Then just run:

```
docker-compose up
```

This will build the Docker-container using the Dockerfile in the docker directory and start two containers. After a short time, you should be able to visit your new Wordpress site at http://localhost:8000/

The container models some aspects (directory structure) of Uberspace hosts and ensures development is done in an environment similar to the deploy target.


## Deployment on uberspace.de hosts

Clone the repository to a new folder within `/var/www/virtual/$USER`:

```
cd ~/html/..
git clone https://github.com/musikcorps/wordpress
cd wordpress
```

Adjust the webserver's document root settings by some symlinks. This is done by executing

```
script/uberspace-setup
```

Now create a suitable config file:

```
cp config/env.example config/production.env
ln -s config/production.env .env
```

Install dependencies and setup Wordpress:

```
script/bootstrap
```

The website should now be accessible at your Uberspace.


## License

Copyright 2017 Johannes Lauinger <johannes@lauinger-it.de>  
Released under the terms of the MIT license

