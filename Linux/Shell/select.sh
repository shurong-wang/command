#!/bin/sh
# "select" Usage

echo "What is your favourite OS?"

select var in "Linux" "UNIX" "Windows" "Other"
do
	break
done

echo "You have selected $var"

