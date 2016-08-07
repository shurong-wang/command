#!/bin/sh

num=1
while [ $num -le 10 ]
do
	SUM=`expr $num \* $num`
	echo $SUM
	num=`expr $num + 1`
done
