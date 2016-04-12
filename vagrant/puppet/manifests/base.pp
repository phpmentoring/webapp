Exec { path => ["/bin/", "/sbin/", "/usr/bin/", "/usr/sbin/" ] }

exec { "apt-get update": }

$system_packages = ['curl', 'git', 'vim', 'unzip', 'screen', 'telnet']

package {$system_packages:
  ensure => present,
  require => Exec['apt-get update']
}

class { "apache2":
  doc_root => "/var/www/public",
  domain => "mentoring.dev"
}

class {"php":
  php_packages => ['php5-cli', 'php5-intl', 'php5-mcrypt', 'php5-gd', 'php5-xdebug']
}