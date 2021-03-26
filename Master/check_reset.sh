#!/bin/bash

# read from server instead of own pi so that this works on other pis. need static ip 

SERVER_PI="rnnishiPI0w"
SELF="rnnishiPI0w"
PROGPATH="/home/pi/NishiDev1.0/Master"
RAW=$(ping ${SERVER_PI}.local -c 1)
echo $RAW
if [[ $(echo $RAW | grep "cannot\ resolve" | wc -l) -ne 0 ]]
then
    echo "Cannot find IP for pi hosting control panel."
    echo "If ${SERVER_PI} is not the correct name for the pi hosting the control panel server, please re-install control panel software on the desired raspberry pi."
    exit
fi

IP="$(echo $RAW | cut -d' ' -f11)"


while true
do 
	NOW=$(curl "${IP}/control_panel/requests.txt")
	if [[ $(echo $NOW | grep "RESET" | wc -l) -ne 0 ]]
	then
		sed -i '2s/.*/requests=0/' ${PROGPATH}/memory.txt
	else
		sleep 1
		echo "No Reset" >> ${PROGPATH}/reset.txt
	fi
	idle='false'
	if [[ $idle -eq 'false' ]]
	then
		if [[ $(echo $NOW | grep "$SELF IDLE" | wc -l) -ne 0 ]]
		then
			sed -i '3s/.*/idle/' ${PROGPATH}/memory.txt
			idle='true'
		fi
	fi
done
