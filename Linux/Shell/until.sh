#!/bin/sh
# "read" and "until" usage
echo "Press Y/y to stop..."
read input
until [ "$input" = "Y" ] || [ "$input" = "y" ]
do
	echo "Error input,Please try again..."
	read input
done 
echo "Stop here!"
