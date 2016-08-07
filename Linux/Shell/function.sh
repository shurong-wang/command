#!/bin/bash
HELP(){
	echo "Usage: sh function \$1 \$2 \$3"
}
if [ $# -ne 3 ]
then
	HELP
else
	echo "Thank for youe input, the three aguments is 1 2 3."
fi

