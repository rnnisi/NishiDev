#!/bin/bash 

MasterNode="rnnishipi0w"
post=$(curl $MasterNode.local/forFG/RunSpecs.txt)
echo $post
if [[ $(echo $post | grep ";" | wc -l) -eq 0 ]]
then
	exit
fi 

exp=$(curl $MasterNode.local/forFG/Exp.txt)
LOCAL="/home/pi/NishiDev/FG"

last="$(head -1 $LOCAL/lastRunParams.txt)"

STAT=$(sed '3q;d' $LOCAL/memory.txt)

if [[ $STAT == "busy" ]]
then
	echo "busy"
	exit
fi 
echo $last
if [[ $(echo $post | grep -o $last | wc -l) -eq 0 ]]
then
	new=$(echo $post | cut -d'*' -f2)
	echo $new > $LOCAL/RunSpecs.txt
	update=$(echo $post | cut -d'*' -f1)
	echo $update > $LOCAL/lastRunParams.txt
	echo "Update: $update, Exp: $exp"
	sleep 3s
	echo $exp $update $MasterNode
	python3 $LOCAL/FGRun_USB.py $exp $update $MasterNode >/dev/null 2>&1
fi

 
