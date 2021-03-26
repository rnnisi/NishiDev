#!/bin/bash

SERVER_PI=$1
RAW=$(ping ${SERVER_PI}.local -c 1)
if [[ $(echo $RAW | grep "cannot\ resolve" | wc -l) -ne 0 ]]
then
	echo "Cannot find IP for pi hosting control panel."
	echo "If ${SERVER_PI} is not the correct name for the pi hosting the control panel server, please re-install control panel software on the desired raspberry pi."
	exit
fi

FL="$(echo $RAW | cut -d' ' -f11)"
echo "${FL}"


