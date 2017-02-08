# Wordpress project layout

## Inspired by:
 * [roots/bedrock](https://github.com/roots/bedrock)
 * [johnpbloch/wordpress](https://github.com/johnpbloch/wordpress)
 * [Seravo/wordpress](https://github.com/Seravo/wordpress)


## Deployment

Setup environment config:

```shell
cd config
cp env.example env
```

Edit `config/env` according to your site. Then just run:

```shell
script/bootstrap
```

The script prints out the address of your blog. Point a webserver with PHP support to the html folder and visit your site. The `bootstrap` script does the following, which you can also run manually:

```
composer install
vendor/bin/wp core install
```


## License

Copyright 2017 Johannes Lauinger <johannes@lauinger-it.de>  
Released under the terms of the MIT license

