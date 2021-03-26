GOAL="16:33:47"

difference=$(($(date -d $GOAL +%s) - $(date +%s)))
echo $GOAL $difference
if [ $difference -lt 0 ]
then
    sleep $((86400 + difference))
else
    sleep $difference
fi

echo $(date)
