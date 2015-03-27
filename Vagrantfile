# -*- mode: ruby -*-
# vi: set ft=ruby :


# Check required plugins
#REQUIRED_PLUGINS = %w(vagrant-hostmanager)
#exit unless REQUIRED_PLUGINS.all? do |plugin|
#  Vagrant.has_plugin?(plugin) || (
#    puts "The #{plugin} plugin is required. Please install it with:"
#    puts "$ vagrant plugin install #{plugin}"
#    false
#  )
#end


required_plugins = %w(vagrant-hostmanager)
required_plugins.each do |plugin|
  system "vagrant plugin install #{plugin}" unless Vagrant.has_plugin? plugin
end

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
