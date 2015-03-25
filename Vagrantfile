# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
    config.vm.box = "ubuntu/trusty64"

    config.vm.synced_folder "./", "/vagrant/", id: "vagrant-root",
        owner: "vagrant",
        group: "www-data",
        mount_options: ["dmode=775"]

    config.vm.hostname = 'test.dev'
    config.vm.network "private_network", ip: "33.33.33.11"
    config.hostmanager.aliases = %w(www.test.dev)
    config.hostmanager.enabled = true
    config.hostmanager.manage_host = true

    config.vm.provision :shell, :path => "vagrant/bootstrap.sh"
end
