# -*- mode: ruby -*-
# vi: set ft=ruby :

#REQUIRED:
#vagrant plugin install vagrant-hostsupdater
#vagrant plugin install vagrant-vbguest

#Vagrantfile API/syntax version. Don't touch unless you know what you're doing!

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    config.vm.box = "ubuntu/trusty64"

    config.vm.provider "virtualbox" do |vb|
        vb.memory = 4098
        vb.cpus = 2
        vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
      end

    config.vm.network :private_network, ip: "192.168.57.31"
    config.vm.hostname = "app"
    config.hostsupdater.aliases = ["app.int", "www.app.int" , "api.app.int"]
#    config.vm.synced_folder "../vt-symfony-fe", "/vagrant-fe", owner: "vagrant", group: "www-data"

    config.vm.provision "docker" do |d|
        d.pull_images "mysql/mysql-server:latest"
        d.pull_images "elasticsearch:latest"
    end

    config.vm.provision "docker" do |d|
        d.run "mysql",
            image: "mysql/mysql-server:5.7",
            restart: "always",
            demonize: true,
            args: "-e MYSQL_ROOT_PASSWORD=root -e MYSQL_ROOT_HOST=% -p 3306:3306"
        d.run "elastic",
            image: "elasticsearch",
            restart: "always",
            demonize: true,
            args: "-p 9200:9200"
    end

    config.vm.provision "shell", path: "./provision/install-software.sh"
    config.vm.provision "shell", path: "./provision/tools/install-php5.sh"
    config.vm.provision "shell", path: "./provision/tools/install-nodejs.sh"
    config.vm.provision "shell", path: "./provision/reload-configs.sh", run: "always"
end
