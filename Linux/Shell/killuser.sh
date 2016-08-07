#!/bin/sh
# The script to kill logined user.

if [ $# -ne 1 ]; then
	echo "Please input 1 parameter"
	exit 0
fi

username="$1"

/bin/ps aux | /bin/grep $username | /bin/awk '{print $2}' > /tmp/temp.pid

killed=`cat /tmp/temp.pid`

for PID in $killed
do
	/bin/kill -9 $PID 2> /dev/null
done
