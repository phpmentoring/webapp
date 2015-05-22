## PHP Mentoring App

### Setup

1. Clone this repo
2. Go into the folder, and run `vagrant up` to start the VM. If you get an error about "Supervisord", it should be safe to ignore.
3. Add the following entry to your hosts file:

        192.168.56.101		www.mentoring.dev mentoring.dev
4. Run `vagrant ssh` to go into the VM
5. CD to `/var/www` and run 'composer update'
6. If you get an error like the following:

        Could not fetch https://api.github.com/repos/vlucas/phpdotenv/zipball/732d2adb7d916c9593b9d58c3b0d9ebefead07aa, please create a GitHub OAuth token to go over the API rate limit
        Head to https://github.com/settings/tokens/new?scopes=repo&description=Composer+on+packer-virtualbox-iso-1422588891+2015-05-22+0002 to retrieve a token.
        It will be stored in "/home/vagrant/.composer/auth.json" for future use by Composer.
You will need to navigate to the URL in a browser, authenticate with GitHub, and generate the access token.
6. From the same directory, run `vendor/bin/phinx init` to generate a phinx.yml file
7. Edit phinx.yml's development section with the following values:
    - name: mentoring
    - user: mentoring
    - pass: vagrant
8. Edit phinx.yml's "paths.migrations" value to: %%PHINX_CONFIG_DIR%%/data/migrations
9. Run `vendor/bin/phinx migrate` to run the database migrations
10. Copy `.env.example` to `.env`
11. Create a new Github Application at https://github.com/settings/applications/new
	- Set the Homepage and Authorization to `http://mentoring.dev`
12. Copy the Client ID and Client Secret into .env
13. Vist `http://mentoring.dev` in your browser!

#### Running Without Vagrant

If you want to help out and cannot download or run Vagrant, you can run the application locally using PHP's built in dev server. You will need the following packages:

* php-curl
* php-intl

For #7 above, you can set either run using MySQL or SQLite. If you would like to use sqlite, you can edit the .env file and change `DB_DRIVER` to `pdo_sqlite`, and in the phinx.yml file change the driver to `sqlite` and the name to `data/mentoring.db`. The application will then use SQLite instead of MySQL.

For #3 above, use 127.0.0.1 instead of 192.168.56.101 since we will be running locally.

To run using PHP's build in server, navigate to the root of the project and run:

    php -S localhost:8080 -t public public/index.php