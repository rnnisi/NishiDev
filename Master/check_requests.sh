#!/bin/bash

# read from server instead of own pi so that this works on other pis. need static ip 
# Run on master pi (connected to scope)
SERVER_PI="rnnishiPI0w"
RAW=$(ping ${SERVER_PI}.local -c 1)
LOCAL="/home/pi/Master"
if [[ $(echo $RAW | grep "cannot\ resolve" | wc -l) -ne 0 ]]
then
    echo "Cannot find IP for pi hosting control panel."
    echo "If ${SERVER_PI} is not the correct name for the pi hosting the control panel server, please re-install control panel software on the desired raspberry pi."
    exit
fi

IP="$(echo $RAW | cut -d' ' -f11)"

STAT=$(sed '3q;d' $LOCAL/memory.txt)
NOW=$(curl "${IP}/control_panel/requests.txt")
UPDATE="${SERVER_PI}.local/control_panel/GetUpdate.php"
SELF="rnnishipi0w"
declare -i I_0=$(sed '2q;d' $LOCAL/memory.txt)
declare -i I=$(echo $NOW | grep -o ' $ ' | wc -l)+1
echo "now" $NOW
echo 
echo "I_0 " $I_0
echo "I " $I
#I_0=0 ## REMOVE LATER
#I=1001 ## REMOVE LATER
if [[ $I_0 -eq $I ]]
then	
	exit
fi

echo "Processing Request..."

$LOCAL/check_reset.sh >/dev/null 2>&1 &
FOO_PID=$!

for (( i=$I_0+1; $i <=$I; i++ )); do
	echo $i
	VAL="$(echo $NOW | cut -d'$' -f$i)"
	echo "val" $VAL
	FG="$(echo $VAL | cut -d'!' -f2)"
	echo "FG" $FG
	if [[ $(echo $FG | grep "&" | wc -l) -ne 0 ]]
	then 
		fgg="True"
	fi
	SCOPE="$(echo $VAL | cut -d'!' -f1)"
	echo "scope" : $SCOPE
	N=$(echo $VAL | grep "$SELF" | wc -l)
	echo "N" $N
	GO=$(echo $VAL | grep "CANCELLED" | wc -l)
	LIVE=$(echo $VAL | grep "RESET" | wc -l)
	DONE=$(echo $VAL | grep "done" | wc -l)
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
	REQN="$(echo $SCOPE | cut -d';' -f1)"
	REQN="$(echo $REQN | sed -e 's/\ //g')"
	echo $REQN
	#STAT="go" ## REMOVE LATER
	if [[ "$STAT" == "busy" ]]	# if scope is not busy continue 
	then
		echo "pi/scope pairing is busy"
		curl "${UPDATE}?ReqN=${REQN}&stat=queued&press=done"
		kill $FOO_PID
		exit
	fi
	if [[ $N -ne 0 ]]	# if nickname matches this pi continue
	then
		echo "accepting job"
		RT="$(echo $SCOPE | cut -d';' -f3)"
		RT="$(echo $RT | sed -e 's/\ //g')"
		TRIG="$(echo $SCOPE | cut -d';' -f4)"
		TRIG="$(echo $TRIG | sed -e 's/\ //g')"
		CHANS="$(echo $SCOPE | cut -d';' -f5)"
		CHANS="$(echo $CHANS | sed -e 's/\ //g' | sed -e 's/,/\ /g')"
		echo "RunTime" $RT
		echo "Trigger" $TRIG
		echo "Channels" $CHANS
        echo "$fgg"
		if [[ "$fgg" == "True" ]]
		then
			echo $fgg
			echo "ID_$REQN*$FG" > /var/www/html/forFG/RunSpecs.txt
		fi
		
		curl "${UPDATE}?ReqN=${REQN}&stat=submitted&press=done"
		((ST=$(echo $SECONDS)))
		python3 $LOCAL/USB_Run.py $RT $TRIG $CHANS >/dev/null 2>&1
		(( rt = $SECONDS-ST ))
		echo "Data Collection Time: " $rt
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
EMPTY=$(echo $EXIT_RAW | grep "Req_ " | wc -l)

kill $FOO_PID

if [[ $EMPTY -eq 0 ]]
then
	if [[ $RS -ne 0 ]]
	then
		sed -i '2s/.*/requests=0/' $LOCAL/memory.txt
	fi
else
	sed -i '2s/.*/requests='$I'/' $LOCAL/memory.txt
fi

exit
