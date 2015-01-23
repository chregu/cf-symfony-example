Symfony Example for Cloud Foundry
========================

This is a ready-to-run example to get Symfony Apps running on Cloud Foundry. This is for the php_buildpack 3.0 available here [https://github.com/cloudfoundry/php-buildpack](https://github.com/cloudfoundry/php-buildpack)

How have to change/add the following stuff to make it running

## manifest.yml


[manifest.yml](manifest.yml)

Actually, not really needed, but recommended. Saves you typing the same stuff into the commandline again and again.

## .bc-config/options.json

Here goes the main config for the buildpack. 
See [the config docs of the build pack](https://github.com/cloudfoundry/php-buildpack/blob/master/docs/config.md) for details and more

### "PHP_VERSION"

 "{PHP_55_LATEST}",

### "WEB_SERVER"

 "nginx",

### "ZEND_EXTENSIONS"

 ["opcache"],

### "PHP_EXTENSIONS"

 ["soap","json","curl","simplexml","pdo", "pdo_mysql"],
 
 you can also put them into composer.json in "require" as eg. "ext-mbstring"

### "WEBDIR"

 "web",
### "COMPOSER_VENDOR_DIR"

 "vendor/",

### "COMPOSER_INSTALL_OPTIONS"

 ["--no-scripts", "--no-dev"],
### "ADDITIONAL_PREPROCESS_CMDS"

 "php $HOME/php/bin/composer.phar install --no-dev --no-progress"

## .cfignore

maybe you don't want to upload the vendor/ dir or other files

## app/AppKernel.php

Cache and Log Files have to go into a tmp directory

## .bp-config/nginx/server-locations.conf

make app.php the default 

## .bp-config/php/php-fpm.conf

adjusting to your need (memory_limit for example)