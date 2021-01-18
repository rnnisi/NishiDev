#!/bin/bash 
# make server to upload data on 

sudo apt-get update

sudo apt-get install apache2

sudo apt-get install php libapache2-mod-php

sudo chmod a+w /var
sudo chmod a+w /var/www
sudo chmod a+w /var/www/html/

mkdir /var/www/html/NICKNAME

sudo chmod a+w /var/www/html/NICKNAME
EXPLOG="/var/www/html/NICKNAME/ExpLog.php"
echo "<!DOCTYPE html>" > $EXPLOG
echo "<html>" >> $EXPLOG
echo "<body>" >> $EXPLOG
echo "<h1>Experiment Log</h1>" >> $EXPLOG
echo "</body>" >> $EXPLOG
echo "</html>" >>$EXPLOG

sudo chmod a+w $EXPLOG

sudo chmod a+w /etc/udev/rules.d
PERMISSION='SUBSYSTEMS=="usb", ATTRS{idVendor}=="1ab1", ATTRS{idProduct}=="0517", GROUP="users", MODE="0666"'
sudo echo $PERMISSION > /etc/udev/rules.d/33-RigolScope.rules

echo "Please reboot pi to finish configuration"

