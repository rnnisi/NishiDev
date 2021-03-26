#!/bin/bash
##############################################
##############################################
##############################################
#THIS DOES NOT WORK, IS INCOMPLETE. 3/25/21
##############################################
##############################################
##############################################
# get specifications 
read -p "Enter the number of channels the scope has as an integer : " CHANNELS
CHANNELS="$CHANNELS"
read -p "Enter the serial number of the scope : " SERIAL
SERIAL="$SERIAL" 

NICKNAME=$(hostname)

# customize software to specifications
sed -i 's/CHANNELS/'$CHANNELS/ RigolDS1000Z.py
sed -i 's/SERIAL/'$SERIAL/ RigolDS1000Z.py
sed -i 's/NICKNAME/'$NICKNAME/ RigolDS1000Z.py ConfigureOnlineDB.sh DataTransfer.py check_requests.sh

LOCAL="/home/pi/NishiDev1.0/Master"

sed -i 's/LOCAL/'$LOCAL/ RigolDS1000Z.py ConfigureOnlineDB.sh DataTransfer.py check_requests.sh

sudo chmod +x ./*_Run.py ./*.sh ./Get*.php ./Submit*.php


#Setup online Database
sudo ./ConfigureOnlineDB.sh

mkdir /var/www/html/forFG

# setup crontab
crontab -l > mycron
echo "PATH="$(echo $PATH) >> mycron
echo >> mycron
echo "SHELL="$(echo $SHELL) >> mycron
echo >> mycron
echo "LOGNAME="$(echo $LOGNAME) >> mycron
echo >> mycron
echo "PYTHONPATH="$(echo $PYTHONPATH) >> mycron
echo >> mycron 
echo "* * * * * ${LOCAL}/check_requests.sh >/dev/null 2>&1" >> mycron
echo >> mycron
echo "* * * * * ${LOCAL}/check_reset.sh >/dev/null 2>&1" >> mycron
crontab mycron
rm mycron


echo 
echo
echo


# Setup remote control 
echo "Is this Pi intended to host the control panel? Only one Pi should host the control panel."
read -p "Enter YES if this Pi should set up the control panel now: " CONTROL
CONTROL=$CONTROl
if [[ $CONTROL = "YES" ]] 
then
	read -p "To verify, please type the name of this Pi as it appears on the network: " PANEL_IP
	PANEL_IP="$PANEL_IP"
else
	echo 
	echo "This Pi will be added as an option to the control panel."
	read -p "Please type the name of the Pi hosting the control panel, as it appears on the network: " PANEL_IP
	PANEL_IP="$PANEL_IP"
fi 	

sed -i 's/PANEL_IP/'$PANEL_IP/ check_requests.sh check_reset.sh

if [[ $CONTROL -eq "YES" ]]
then
	mkdir /var/www/html/control_panel
	sudo chmod a+w /var/www/html/control_panel
	mv *.php /var/www/html/control_panel
else
	rm *.php 
fi


echo "Please type crontab -e and hit enter into command line to finish setting up crontab if it has not been used on this device before"
echo "Finally, please reboot this device and add the device to the control panel."
