#!/bin/bash
#Last updated 1/17/2021
#rnnishide@gmail.com


# get specifications 
read -p "Enter the number of channels the scope has as an integer : " CHANNELS
CHANNELS="$CHANNELS"
read -p "Enter the serial number of the scope : " SERIAL
SERIAL="$SERIAL" 
read -p "Enter a nickname for this scope/pi pair : " NICKNAME
NICKNAME="$NICKNAME"

# customize software to specifications
sed -i 's/CHANNELS/'$CHANNELS/ RigolDS1000Z.py
sed -i 's/SERIAL/'$SERIAL/ RigolDS1000Z.py
sed -i 's/NICKNAME/'$NICKNAME/ check_reset.sh RigolDS1000Z.py ConfigureOnlineDB.sh DataTransfer.py check_requests.sh

LOCAL_DIR="$(pwd)"

sed -i 's/LOCAL_DIR/'$LOCAL_DIR/ RigolDS1000Z.py ConfigureOnlineDB.sh DataTransfer.py check_requests.sh

sudo chmod +x ./*_Run.py ./*.sh ./Get*.php ./Submit*.php


#Setup online Database
sudo ./ConfigureOnlineDB.sh


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
echo "* * * * * ${LOCAL_DIR}/check_requests.sh >/dev/null 2>&1" >> mycron
echo >> mycron
echo "* * * * * ${LOCAL_DIR}/check_reset.sh >/dev/null 2>&1" >> mycron
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
	mv -r ./UserEnd /var/www/html/control_panel
else
	rm -r ./UserEnd
fi


echo "Initialization done"
echo "Please set up crontab by entering crontab -e in the command line if not done yet"
echo "Finally, please reboot pi to reset permissions."
