# QuickConfigure [![Build Status](https://travis-ci.org/BenConstable/quick-configure.png?branch=master)](https://travis-ci.org/BenConstable/quick-configure)

QuickConfigure makes the tedious process of setting configuration options in
your builds quick and easy (for you and the people using your code!).

## Why?

Often frameworks or libraries require you to specify some basic config to get
started. This could be setting database details for example, or setting
the base URL for an application.

Usually, this involves either modifying a config file that's under version
control or manually creating a file (like [Laravel's .env files](http://laravel.com/docs/configuration#protecting-sensitive-configuration)). This is slow and unweildy, and makes automating
builds difficult.

QuickConfigure allows you to specify a simple JSON schema from which required
config can be generated. No manually creating config files, no dirty work tree,
and a simple script that can be incorporated into your build process. Easy!

## Installation

QuickConfigure is installed via [Composer](http://getcomposer.org):

```json
{
    "require": {
        "benconstable/quick-configure": "~1.0"
    }
}
```

## Basic usage

### quick-configure.json

QuickConfigure generates config from a `quick-configure.json` file. This file
has the format:

```json
{
    "field": {
        "description": "This field is important, please set it"
    },

    "other_field": {
        "description": "We need this to be configured, too"
    }
}
```

Each listed field will be configured and accessible in your app.

### Generating config

Generating config is done via the `configure` command. Just run:

```sh
$ vendor/bin/quick-configure configure
```

That's it!

### Using config

QuickConfigure provides a simple API for accessing generated config in your
application:

```php
<?php

// Load config
$config = new \QuickConfigure\Config();

// And do something with it!
$db = new Database($config->get('username'), $config->get('password'));
```

## Advanced usage

### Environments

QuickConfigure allows you easily to configure different environments from the
same `quick-configure.json` file.

By default, config will have no environment (otherwise known as the 'global'
environment). To specify an environment, just pass the `--env` flag to the
`configure` command, like so:

```sh
$ vendor/bin/quick-configure configure --env development
```

Then, you can access the config in your application like:

```php
<?php

// Load development config
$config = new \QuickConfigure\Config('development');

// And do something with it!
$db = new Database($config->get('username'), $config->get('password'));

// Then get some config from a different environment
$testKey = $config->setEnv('test')->get('key');
```

### Checking config

If you want to check the current config, just run:

```sh
$ vendor/bin/quick-configure show
```

and you can of course supply the `--env` flag to check environment config:

```sh
$ vendor/bin/quick-configure show --env development
```

### Dumping config

If you don't want to use the `\QuickConfigure\Config` class, you can dump config
to a file to use however you'd like in your application.

For example, to generate a [Laravel .env file](http://laravel.com/docs/configuration#protecting-sensitive-configuration)
, you can just run:

```sh
$ vendor/bin/quick-configure configure --env development
$ vendor/bin/quick-configure dump --env development --format php --name .env.development
```

This will create a file called `.env.development.php` in your current directory.

Alternatively, use the `--stdout` option to dump the config straight to STDOUT.

#### Dump formats

The `--format` option lets you specify the format of the dumped file. Currently,
QuickConfigure supports:

* `php`: A PHP array (unserialized)
* `json`: A JSON object

## Further help

QuickConfigure is built on the excellent [Symfony Console Component](http://symfony.com/doc/current/components/console/introduction.html)
, so you can make use of the built-in help functionality to get further
information about the utility:

```sh
# Show full help
$ vendor/bin/quick-configure

# Show help for the `configure` command
$ vendor/bin/quick-configure configure --help
```

## Developing & Contributing

Please feel free to fork and contribute to this repository, but do please
make sure that you:

* Provide tests (using Behat)
* Ensure you don't break the existing tests
* Comment your code with PHP docblocs, and stick to the coding style. This is
PSR-0 and PSR-1 with [Laravel's flavour](https://github.com/laravel/framework/blob/master/CONTRIBUTING.md#coding-guidelines) (I prefer the namespace and class declarations)

To execute the tests, run:

```sh
$ vendor/bin/Behat
```

###License

QuickConfigure is open-sourced software licensed under the MIT license
