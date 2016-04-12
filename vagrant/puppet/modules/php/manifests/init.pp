class php(
  $php_packages = ['php5', 'php5-mysql', 'php5-gd', 'php5-xdebug', 'php5-mcrypt', 'php5-cli', 'php5-dev','php5-curl', 'php-pear']
) {
    package { $php_packages:
        ensure => 'present',
        notify => Service['apache2'],
        require => Exec['apt-get update']
    }

    file {'/etc/php5/mods-available/xdebug.ini':
        source => "puppet:///modules/php/xdebug.ini"
    }

    file {'/etc/php5/apache2/conf.d/xdebug.ini':
        source => "puppet:///modules/php/xdebug.ini",
        notify => Service['apache2'],
        require => Package['apache2'],
    }

    file {'/etc/php5/apache2/conf.d/custom.ini':
        source => "puppet:///modules/php/custom.ini",
        notify => Service['apache2'],
        require => Package['apache2'],
    }

    file {'/etc/php5/cli/conf.d/xdebug.ini':
        source => "puppet:///modules/php/xdebug.ini"
    }
}