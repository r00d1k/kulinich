#!/bin/bash
#

echo "[Info] Installing mysql"
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'
sudo apt-get -y install mysql-server
sudo apt-get -y install mysql-client
sed -i 's/bind-address/#bind-address/g' /etc/mysql/my.cnf
mysql -u root -proot -e "UPDATE mysql.user SET Host='%' WHERE Host='vagrant-ubuntu-trusty-64' AND User='root';"
mysql -u root -proot -e "UPDATE mysql.user SET Host='%' WHERE Host='app' AND User='root';"
mysql -u root -proot -e "UPDATE mysql.user SET Host='%' WHERE Host='app.int' AND User='root';"
mysql -u root -proot -e "FLUSH PRIVILEGES;"
