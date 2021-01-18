#!/bin/bash

# read from server instead of own pi so that this works on other pis. need static ip 

PROGPATH="LOCAL_DIR"
SERVER_PI="PANEL_IP"
RAW=$(ping ${SERVER_PI}.local -c 1)
echo $RAW
if [[ $(echo $RAW | grep "cannot\ resolve" | wc -l) -ne 0 ]]
then
    echo "Cannot find IP for pi hosting control panel."
    echo "If ${SERVER_PI} is not the correct name for the pi hosting the control panel server, please re-install control panel software on the desired raspberry pi."
    exit
fi

IP="$(echo $RAW | cut -d' ' -f11)"

STAT=$(sed '3q;d' ${PROGPATH}/memory.txt)
NOW=$(curl "${IP}/control_panel/requests.txt")
UPDATE="${IP}/control_panel/GetUpdate.php"
SELF="NICKNAME"
declare -i I_0=$(sed '2q;d' ${PROGPATH}/memory.txt)
declare -i I=$(echo $NOW | grep -o '!' | wc -l)
echo $NOW
echo 
echo "I_0 " $I_0
echo "I " $I
if [[ $I_0 -eq $I ]]
then	
	exit
fi

echo "Processing Request..."

${PROGPATH}/check_reset.sh >/dev/null 2>&1 &
FOO_PID=$!

for (( i=$I_0+1; $i <=$I; i++ )); do
	echo $i
	VAL="$(echo $NOW | cut -d'!' -f$i)"
	N=$(echo $VAL | grep "$SELF" | wc -l)
	GO=$(echo $VAL | grep "CANCELLED" | wc -l)
	LIVE=$(echo $VAL | grep "RESET" | wc -l)
	DONE=$(echo $VAL | grep "done" | wc -l)
	SUBMITTED=$(echo $VAL | grep "submitted" | wc -l)
	QUEUE=$(echo $VAL | grep "queued" | wc -l)
	IDLE=$(echo $VAL | grep "IDLE" | wc -l)
	if [[ $N -eq 0 ]] || [[ $GO -ne 0 ]] || [[ $DONE -ne 0 ]] || [[ $IDLE -ne 0 ]]
	then
		continue
	fi
	if [[ $LIVE -ne 0 ]]
	then 
		kill $FOO_PID
		exit
	fi
	REQN="$(echo $VAL | cut -d';' -f1)"
	REQN="$(echo $REQN | sed -e 's/\ //g')"
	if [[ "$STAT" == "busy" ]]	# if scope is not busy continue 
	then
		if [[ $SUBMITTED -eq 0 ]] 
		then
			echo "pi/scope pairing is busy"
			if [[ $QUEUE -eq 0 ]]
			then
				curl "${UPDATE}?ReqN=${REQN}&stat=queued&press=done"
			fi
			kill $FOO_PID
			exit
		else
			kill $FOO_PID
			exit
		fi
	fi
	if [[ $N -ne 0 ]]	# if nickname matches this pi continue
	then
		echo "Updating memory .... "
		RT="$(echo $VAL | cut -d';' -f3)"
		RT="$(echo $RT | sed -e 's/\ //g')"
		TRIG="$(echo $VAL | cut -d';' -f4)"
		TRIG="$(echo $TRIG | sed -e 's/\ //g')"
		CHANS="$(echo $VAL | cut -d';' -f5)"
		CHANS="$(echo $CHANS | sed -e 's/\ //g' | sed -e 's/,/\ /g')"
		echo "RunTime" $RT
		echo "Trigger" $TRIG
		echo "Channels" $CHANS
        curl "${UPDATE}?ReqN=${REQN}&stat=submitted&press=done"
		((ST=$(echo $SECONDS)))
		python3 ${PROGPATH}/USB_Run.py $RT $TRIG $CHANS >/dev/null 2>&1
		(( rt = $SECONDS-ST ))
		echo $rt
		if [[ (( $SECONDS-ST < $RT+10 )) ]]
		then
			curl "${UPDATE}?ReqN=${REQN}&stat=doneCrash&press=done"
		else
        	curl "${UPDATE}?ReqN=${REQN}&stat=done&press=done"
		fi
	fi
	echo 
done


EXIT_RAW=$(curl "${IP}/control_panel/requests.txt")
RS=$(echo $EXIT_RAW | grep "RESET" | wc -l)
EMPTY=$(echo $EXIT_RAW | grep "Req_" | wc -l)

kill $FOO_PID

if [[ $RS -ne 0 ]] || [[ $EMPTY -eq 0 ]]
then
	echo "requests empty"
	sed -i '2s/.*/requests=0/' ${PROGPATH}/memory.txt
else
	declare -i If=$(echo $EXIT_RAW | grep -o '!' | wc -l)
	sed -i '2s/.*/requests='$If'/' ${PROGPATH}/memory.txt
fi

exit
