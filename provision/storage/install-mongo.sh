#!/bin/bash
#

echo "[Info] Installing MongoDb"
sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv EA312927
echo "deb http://repo.mongodb.com/apt/ubuntu trusty/mongodb-enterprise/stable multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-enterprise.list

sudo apt-get update

sudo apt-get install -y mongodb-enterprise
