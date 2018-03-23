## PHP Mentoring App

[![Build Status](https://travis-ci.org/phpmentoring/webapp.svg?branch=master)](https://travis-ci.org/phpmentoring/webapp)
[![Codeship Status for phpmentoring/testapp](https://codeship.com/projects/a10984a0-df3f-0132-12f0-767a4e17443c/status?branch=master)](https://codeship.com/projects/80502)
[![Code Climate](https://codeclimate.com/github/phpmentoring/webapp/badges/gpa.svg)](https://codeclimate.com/github/phpmentoring/webapp)

### Requirements

You should have [Composer](http://getcomposer.org) installed and available. If you need to install composer, go to their _[Getting Started](https://getcomposer.org/doc/00-intro.md)_ docs. If you will be using [Vagrant](http://vagrantup.com) for local development, you'll need to have it installed as well.

### Setup

#### Using Docker

1. Clone this repo.
2. Go into the folder, and run `docker-compose up -d` to build and run the images
3. Add the following entry to your hosts file. On unix-like systems, its usually found at `/etc/hosts`: 

    127.0.0.1    www.mentoring.dev mentoring.dev

4. Run `chmod +x cb` to enable you to run the helper script (Sorry Windows users, but would love help with a PS script)
5. Run `./cb composer install`
6. [Configure Phinx](#phinx-configuration)
7. Run `./cb cli vendor/bin/phinx migrate` to run the database migrations.
8. Copy `app/config/parameters.yml.dist` to `app/config/parameters.yml` and configure for your setup
9. [Configure Github](#github)
10. Visit <http://localhost:8080> in your browser!

#### Running Without Vagrant

If you want to help out and cannot download or run Vagrant, you can run the application locally using PHP's built in dev server. You will need the following PHP extensions:

* php-curl
* php-intl

Steps:

1. Clone this repo.
2. Add the following entry to your hosts file. On unix-like systems, its usually found at `/etc/hosts`: 

    127.0.0.1    www.mentoring.dev mentoring.dev

3. Change directory to the directory where you cloned the project in step 1 and run `composer install`
4. Copy `app/config/parameters.yml.dist` to `app/config/parameters.yml` and configure for your setup
5. [Configure Phinx](#phinx-configuration)
8. Run `vendor/bin/phinx migrate` to run the database migrations.
9. [Configure Github](#github)
11. To run using PHP's built-in server, navigate to the root of the project and run:
   
    php -S mentoring.dev:8080 -t public public/index.php
   
12. Visit <http://mentoring.dev:8080> in your browser!

### Phinx Configuration

We use [Phinx](https://phinx.org/) for managing our database migrations. It provides a programmatic way for handling
table and data changes. It does require some initial setup however.

1. From the root project directory, run `./cb cli vendor/bin/phinx init` to generate a `phinx.yml` file.
2. Edit `./phinx.yml`'s development section (lines 19-21) with the following values for MySQL.

    ```{.yaml}
    host: mysql
    name: appdb
    user: dbuser
    pass: dbuser
    ```

    To use sqlite, in `./phinx.yml` change change the adapter to `sqlite` (line 17) the name (line 19) to `data/mentoring.db`.

3. Edit `./phinx.yml`'s `paths.migrations` value (line 2) to:

    ```
    %%PHINX_CONFIG_DIR%%/data/migrations
    ```

### App Configuration

All configuration is store in `app/config/parameters.yml`. The app ships with a `.dist` version that you will need to
copy/paste into the above path. You can then edit it to your needs.

#### Github

The application uses Github for authentication. If you are developing or working
on features that require a user login, you will need to set up a new Github Application
to generate a secret and a key.

Instructions for doing this are available at <https://github.com/settings/applications/new>.

Set the **Homepage** and **Authorization callback URL** to <http://mentoring.dev:8080> or <http://mentoring.dev>, depending on how you set up your `hosts` file.

Once you have that completed, you will need to add them to the `parameters.yml` file, like this:

```{.yaml}
github:
  api_key: 'keyfromgithub'
  api_secret: 'secretfromgithub'
```

#### Email in Development

Before you start the Docker containers, you need to change your `parameters.yml` file. To use mailhog, you'll want the following config:

```{.yaml}
mail:
  host: 'mailhog'
  port: 1025
```

You can then view all emails being sent out by the app in your host machine's browser at the following address:

`http://localhost:8025`

#### Database Configuration

This app uses the [Doctrine DBAL](http://silex.sensiolabs.org/doc/master/providers/doctrine.html) as it's underlying
database layer, so you can configure the database using the options in the Silex documentation.

Explicitly, we support MySQL and it's dialect of SQL. Others may work, like SQLite for development environments, but
most of the code is written with MySQL in mind.

You can configure the database settings under the `database:` key.