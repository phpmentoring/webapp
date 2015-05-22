## PHP Mentoring App

### Requirements

You should have [Composer](http://getcomposer.org) installed and available. If you need to install composer, go to their _[Getting Started](https://getcomposer.org/doc/00-intro.md)_ docs. If you will be using [Vagrant](http://vagrantup.com) for local development, you'll need to have it installed as well.

### Setup

#### Using Vagrant

1. Clone this repo.
2. Go into the folder, and run `vagrant up` to start the VM.
3. Add the following entry to your hosts file: 

    192.168.56.101    www.mentoring.dev mentoring.dev

4. Run `vagrant ssh` to go into the VM.
5. CD to `/var/www` and run `composer install`
6. From the same directory, run `vendor/bin/phinx init` to generate a `phinx.yml` file.
7. If you get an error like the following:

        Could not fetch https://api.github.com/repos/vlucas/phpdotenv/zipball/732d2adb7d916c9593b9d58c3b0d9ebefead07aa, please create a GitHub OAuth token to go over the API rate limit
        Head to https://github.com/settings/tokens/new?scopes=repo&description=Composer+on+packer-virtualbox-iso-1422588891+2015-05-22+0002 to retrieve a token.
        It will be stored in "/home/vagrant/.composer/auth.json" for future use by Composer.
        
You will need to navigate to the URL in a browser, authenticate with GitHub, and generate the access token.

8. Edit `phinx.yml`'s development section with the following values for MySQL credentials:

```{.yaml}
    - name: mentoring
    - user: mentoring
    - pass: vagrant
```

9. Edit `phinx.yml`'s `paths.migrations` value to: 

    %%PHINX_CONFIG_DIR%%/data/migrations

10. Run `vendor/bin/phinx migrate` to run the database migrations.
11. Copy `.env.example` to `.env`
12. Create a new Github Application at <https://github.com/settings/applications/new>
    - Set the **Homepage** and **Authorization callback URL** to <http://mentoring.dev>
13. Update lines 7 and 8 for `GITHUB_API_KEY` and `GITHUB_API_SECRET` in your `.env` file with the Client ID and Client Secret for your app.
14. Visit <http://mentoring.dev> in your browser!

#### Running Without Vagrant

If you want to help out and cannot download or run Vagrant, you can run the application locally using PHP's built in dev server. You will need the following packages:

* php-curl
* php-intl

1. Clone this repo.
2. Add the following entry to your hosts file. On unix-like systems, its usually found at `/etc/hosts`: 

    127.0.0.1    www.mentoring.dev mentoring.dev


3. CD to the directory where you cloned the project in step 1 and run `composer install`
4. From the same directory, run `vendor/bin/phinx init` to generate a `phinx.yml` file.
5. Edit `phinx.yml`'s development section (lines 19-21) with the following values for MySQL.

```{.yaml}
    - name: mentoring
    - user: mentoring
    - pass: vagrant
```

To use sqlite, in `phinx.yml` change change the adapter to `sqlite` (line 17) the name (line 19) to `data/mentoring.db`. In `.env` change `DB_DRIVER` to `pdo_sqlite` on line 1.

6. Edit `phinx.yml`'s `paths.migrations` value (line 2) to: 

    %%PHINX_CONFIG_DIR%%/data/migrations

7. Run `vendor/bin/phinx migrate` to run the database migrations.
8. Copy `.env.example` to `.env`
9. Create a new Github Application at <https://github.com/settings/applications/new>
    - Set the **Homepage** and **Authorization callback URL** to <http://mentoring.dev:8080>
10. Update lines 7 and 8 for `GITHUB_API_KEY` and `GITHUB_API_SECRET` in your `.env` file with the Client ID and Client Secret for your app.
11 To run using PHP's built-in server, navigate to the root of the project and run:
   
    php -S mentoring.dev:8080 -t public public/index.php
   
12. Visit <http://mentoring.dev:8080> in your browser!

For #5 above, you can set either run using MySQL or SQLite. If you would like to use sqlite, you can edit the `.env` file and change `DB_DRIVER` to `pdo_sqlite`, and in the `phinx.yml` file change the driver to `sqlite` and the name to `data/mentoring.db`. The application will then use SQLite instead of MySQL.q
