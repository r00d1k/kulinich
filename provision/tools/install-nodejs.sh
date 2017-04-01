#!/bin/bash
#
echo "[Info] Installing Node.js"
curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
sudo apt-get install -y nodejs

sudo npm install -g gulp bower
