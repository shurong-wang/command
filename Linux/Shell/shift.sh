#!/bin/sh
if [ $# -le 0 ]
then
	echo "Not enough parameters!"
	exit 0
fi
sum=0
while [ $# -gt 0 ]
do
	sum=`expr $sum + $1`
	shift
done
echo $sum
