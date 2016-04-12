# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

    config.vm.box = "scotch/box"
    config.vm.network "private_network", ip: "192.168.56.101"
    config.vm.hostname = "scotchbox"
    config.vm.synced_folder ".", "/var/www", :nfs => { :mount_options => ["dmode=777","fmode=666"] }

    config.vm.provision "shell", path: "vagrant/shell/puppet.sh"
    config.vm.provision :puppet do |puppet|
        puppet.module_path = "vagrant/puppet/modules"
        puppet.manifests_path = "vagrant/puppet/manifests"
        puppet.manifest_file = "base.pp"
    end
end