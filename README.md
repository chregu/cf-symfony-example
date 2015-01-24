Symfony Example for Cloud Foundry
========================

This is a ready-to-run example to get Symfony Apps running on Cloud Foundry. This is for the php_buildpack 3.0 available here [https://github.com/cloudfoundry/php-buildpack](https://github.com/cloudfoundry/php-buildpack)

It doesn't work with the 1.0 php buildpack.

You have to change/add the following stuff to make it running

## manifest.yml


[manifest.yml](manifest.yml)

Not mandatory, but recommended. Saves you typing the same stuff into the commandline again and again.

Be aware that env variablias from manifest.yml are not set in the composer install stage of the buildpack, see below for details.

## .bc-config/options.json

[.bc-config/options.json](.bc-config/options.json)

Here goes the main config for the buildpack. 
See [the config docs of the build pack](https://github.com/cloudfoundry/php-buildpack/blob/master/docs/config.md) for details and more

### "PHP_VERSION"

 "{PHP_55_LATEST}",

### "WEB_SERVER"

 "nginx",

### "ZEND_EXTENSIONS"

 ["opcache"],

### "PHP_EXTENSIONS"

Here, you can define needed PHP extensions, eg

`["json","curl","simplexml","pdo", "pdo_mysql"]`
 
But I prefer defining them in composer.json with eg. `"ext-json": "*"`


### "WEBDIR"

 "web",
### "COMPOSER_VENDOR_DIR"

 "vendor/",

### "COMPOSER_INSTALL_OPTIONS"

 ["--no-scripts", "--no-dev"]
 
 Needed because there's no environment from manifest.yml set in the first run of composer.phar install. And there for some things fail in AppKernel.php only defined for dev installations. But they are removed in composer.phar install. We run composer a second time with the following command (this time with running the scripts)
 
### "ADDITIONAL_PREPROCESS_CMDS"

 "php $HOME/php/bin/composer.phar install --no-dev --no-progress"
 
 `composer.phar run-script post-install-cmd`


## .cfignore

[.cfignore](.cfignore)
maybe you don't want to upload the vendor/ dir or other files

## app/AppKernel.php

[app/AppKernel.php](https://github.com/chregu/cf-symfony-example/commit/5d69d4c05379510f5e7206272b7529de478fb372#diff-c23da96dc8986a4cee89980f3aad30e0)


Cache and Log Files have to go into a tmp directory

And also a way to parse VCAP_SERVICES and easily access them in parameters.yml with eg. `%vcap.mysql-service.hostname%`

## .bp-config/nginx/server-locations.conf

[.bp-config/nginx/server-locations.conf](.bp-config/nginx/server-locations.conf)

make app.php the default in nginx

## .bp-config/php/php-fpm.conf

[.bp-config/php/php-fpm.conf](.bp-config/php/php-fpm.conf)

adjusting to your needs (memory_limit for example)