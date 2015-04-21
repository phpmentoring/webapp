## PHP Mentoring App

# Setup

1. Clone this repo
2. Go into the folder, and run `vagrant up` to start the VM
3. Add the following entry to your hosts file: 192.168.56.101		www.mentoring.dev mentoring.dev
4. Run `vagrant ssh` to go into the VM
5. CD to `/var/www` and run 'composer update'
6. From the same directory, run `vendor/bin/phinx init` to generate a phinx.yml file
7. Edit phinx.yml's development section with the following values:
    - name: mentoring
    - user: mentoring
    - pass: vagrant
8. Run `vendor/bin/phinx migrate` to run the database migrations
9. Copy `.env.example` to `.env`
10. Create a new Github Application at https://github.com/settings/applications/new
	- Set the Homepage and Authorization to `http://mentoring.dev`
11. Copy the Client ID and Client Secret into .env
12. Vist `http://mentoring.dev` in your browser!