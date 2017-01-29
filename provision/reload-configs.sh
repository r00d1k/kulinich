#!/bin/bash
#

unlink /etc/nginx/sites-enabled/app.conf
unlink /etc/nginx/sites-available/app.conf

cp /vagrant/provision/nginx-hosts/app.conf /etc/nginx/sites-available/app.conf
ln -s /etc/nginx/sites-available/app.conf /etc/nginx/sites-enabled/app.conf

unlink /etc/php/7.0/fpm/pool.d/app.conf
cp /vagrant/provision/php-fpm-pool/app.conf /etc/php/7.0/fpm/pool.d/app.conf


service php7.0-fpm restart
service nginx restart
