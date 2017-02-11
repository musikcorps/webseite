# Wordpress project layout

## Inspired by:
 * [roots/bedrock](https://github.com/roots/bedrock)
 * [johnpbloch/wordpress](https://github.com/johnpbloch/wordpress)
 * [Seravo/wordpress](https://github.com/Seravo/wordpress)


## Deployment

Setup environment config:

```shell
cp config/env.example config/env
ln -s config/env .env
```

Edit `config/env` according to your site. Then just run:

```
script/bootstrap
```

The script prints out the address of your blog. Point a webserver with PHP support to the html folder and visit your site. The `bootstrap` script does the following, which you can also run manually:

```
composer install
vendor/bin/wp core install
```


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

Now proceed with the regular deployment.


## License

Copyright 2017 Johannes Lauinger <johannes@lauinger-it.de>  
Released under the terms of the MIT license

