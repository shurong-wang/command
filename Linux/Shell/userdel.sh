#!/bin/bash
echo "Please input username:"
read name
echo "Please onput number:"
read num

sum=0
while [ $sum -lt $num ]
do
	sum=`expr $sum + 1`
	/usr/sbin/userdel -r $name$sum
done
