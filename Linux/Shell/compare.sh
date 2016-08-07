#!/bin/sh
if [ $# -ne 2 ]; then
	echo "Please input 2 parameter"
	exit 0
fi
if [ $1 -eq $2 ]; then
	echo "$1 equals $2"
elif [ $1 -lt $2 ]; then
	echo "$1 litter than $2"
elif [ $1 -gt $2 ]; then
	echo "$1 greater than $2"
fi

