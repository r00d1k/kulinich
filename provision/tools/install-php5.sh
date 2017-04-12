#!/bin/bash
#
sudo echo "nameserver 8.8.8.8" > /etc/resolve.conf
sudo apt-get -y install php5 php5-fpm php5-ldap php-apc php-pear php5-cli php5-curl php5-dev php5-gd php5-imagick php5-mcrypt php5-memcache php5-pgsql php5-pspell php5-sqlite php5-tidy php5-xdebug php5-xmlrpc php5-json php5-xsl php5-intl php5-mysql php5-mongo

curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

echo "[Info] Installing phing"
sudo pear channel-discover pear.phing.info
sudo pear install phing/phing

