#!/bin/sh

echo "*****************************"
echo "Please select your operation:"
echo " Press "C" to Copy"
echo " Press "D" to Delete"
echo " Press "B" to Backup"
echo "******************************"
read op
case $op in
	C)
		echo "your selection is Copy"
		;;
	D)
		echo "your selection is Delete"
		;;
	B)
		echo "your selection is Backup"
		;;
	*)
		echo "invalide selection"
esac
