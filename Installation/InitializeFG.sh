#!/bin/bash

# get specifications 

echo "This program is for a Siglent 1000X series function generator."
echo "Requires sudo power"
echo "May require installation, will require reboot and crontab"
echo 
echo 

echo "First, gathering data... "

read -p "Enter the name of master node: " MASTER
MASTER="$MASTER"
NICKNAME=$(hostname)
# customize software to specifications

LOCAL="/home/pi/NishiDev1.0/FG"

sed -i 's/SET_MASTER/'$MASTER/ $LOCAL/check_FGrequests.txt

sudo chmod +x $LOCAL/*.py
sudo chmod +x $LOCAL/*.sh

#Setup online Database
echo "Next, setting up crontabs. If never used before, may require user configuration aster installtion wrapper to choose editor for crontab."

# setup crontab
crontab -l > FGcron
echo "PATH="$(echo $PATH) >> FGcron
echo >> FGcron
echo "SHELL="$(echo $SHELL) >> FGcron
echo >> FGcron
echo "LOGNAME="$(echo $LOGNAME) >> FGcron
echo >> FGcron
echo "PYTHONPATH="$(echo $PYTHONPATH) >> FGcron
echo >> FGcron 
echo "* * * * * ${LOCAL}/FGcheck_requests.sh >/dev/null 2>&1" >> FGcron
crontab FGcron

echo 
echo
echo

echo "Now updating device software."
sudo apt-get update

sudo apt-get install apache2

sudo apt-get install php libapache2-mod-php

echo "Finally, setting up permissions and rule for USB device."

sudo chmod a+w /var
sudo chmod a+w /var/www
sudo chmod a+w /var/www/html/

mkdir /var/www/html/fromFG

PERMISSION='SUBSYSTEMS=="usb", ATTRS{idVendor}=="f4ec", ATTRS{idProduct}=="1103", GROUP="users", MODE="0666"'

sudo chmod a+w /etc/udev/rules.d
sudo echo $PERMISSION > /etc/udev/rules.d/33-SiglentFG.rules

echo "Installation for function generator control node, under master $MASTER complete. Please reboot device before proceeding."
