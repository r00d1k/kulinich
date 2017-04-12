#!/bin/bash
#

echo "[Info] Installing postgres"
sudo apt-get -y install postgresql postgresql-contrib

sed -i 's/\#listen_addresses/listen_addresses/g' /etc/postgresql/9.3/main/postgresql.conf
sed -i '/listen_addresses/s/localhost/*/' /etc/postgresql/9.3/main/postgresql.conf

echo "host    all    all    0.0.0.0/0    md5" >> /etc/postgresql/9.3/main/pg_hba.conf
sudo -u postgres psql template1 -c "ALTER USER postgres with encrypted password 'postgres';"
service postgresql restart
