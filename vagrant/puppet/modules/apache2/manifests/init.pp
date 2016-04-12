class apache2 (
    $doc_root = '/vagrant',
    $domain = 'local.dev'
) {
    package {'apache2':
        ensure => 'present'
    }

    service {'apache2':
        ensure => 'running',
        require => Package['apache2'],
    }

    exec {'a2enmod ssl':
        creates => '/etc/apache2/mods-enabled/ssl.conf'
    }

    file {'/etc/ssl/private/local.dev.crt':
        source => "puppet:///modules/apache2/local.dev.crt"
    }

    file {'/etc/apache2/sites-enabled/vhost.conf':
        owner  => root,
        group  => root,
        mode   => 664,
        content => template('apache2/vhost.conf.erb'),
        notify => Service['apache2'],
        require => Package['apache2'],
    }

    file {'/etc/apache2/conf-enabled/enablesendfile.conf':
        owner  => root,
        group  => root,
        mode   => 664,
        content => template('apache2/enablesendfile.conf.erb'),
        notify => Service['apache2'],
        require => Package['apache2'],
    }
}