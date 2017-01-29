#!/usr/bin/env bash

sudo echo "nameserver 8.8.8.8" > /etc/resolve.conf
echo "[Info] Installing docker"
sudo apt-key adv --keyserver hkp://pgp.mit.edu:80 --recv-keys 58118E89F3A912897C070ADBF76221572C52609D
echo "deb https://apt.dockerproject.org/repo ubuntu-trusty main" > /etc/apt/sources.list.d/docker.list
sudo apt-get update
sudo apt-get -y install docker-engine
sudo apt-get -y install python-pip

sudo pip install docker-compose