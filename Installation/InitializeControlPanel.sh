#!/bin/bash

NICKNAME="$(hostname)"
echo "This installation wrapper will set up this pi as a control panel."
echo "Installation requires an update, installation of Apache2, and a local host."
echo "sudo priveledges are also required to update permissions."
echo 
echo 
echo 
 
echo "First, Updating device software to host online capabilities."

sudo apt-get update

sudo apt-get install apache2

sudo apt-get install php libapache2-mod-php

echo "Now updating permissions"
sudo chmod a+w /var
sudo chmod a+w /var/www
sudo chmod a+w /var/www/html/

echo "Setting up control panel"
sudo mv /home/pi/NishiDev1.0/OnlineControl /var/www/html

echo "Control panel should now be accessible at url: https://$NICKNAME.local/OnlineControl/control_panel.php"
