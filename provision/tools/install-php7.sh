#!/bin/bash
#
sudo echo "nameserver 8.8.8.8" > /etc/resolve.conf
echo "[Info] Installing php 7"

sudo add-apt-repository -y ppa:ondrej/php
sudo apt-get update

sudo apt-get -y install build-essential libmemcached-dev --force-yes
sudo apt-get -y install php7.0 php7.0-cli php7.0-fpm php7.0-dev php7.0-xml php7.0-mbstring php7.0-mysql php7.0-intl php-pear php-curl php7.0-gd php7.0-pgsql php7.0-json php7.0-ldap php-mbstring --force-yes

sudo pecl channel-update pecl.php.net
sudo pecl install apcu

echo "------------------------------"
sudo service php7.0-fpm status
echo "------------------------------"

echo "[Info] Installing phing"
sudo pear channel-discover pear.phing.info
sudo pear install phing/phing

echo "[Info] Installing composer"
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

echo "[Info] Installing phpunit"
cd /root
wget https://phar.phpunit.de/phpunit.phar
chmod +x phpunit.phar
sudo mv phpunit.phar /usr/local/bin/phpunit
