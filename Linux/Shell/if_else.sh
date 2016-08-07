#!/bin/sh

echo "please input a file name:"
read file_name
if [ -d $file_name ]; then
	echo "$file_name is a directory"
elif [ -f $file_name ]; then
	echo "$file_name is a common file"
elif [ -c $file_name -o -b $file_name ]; then
	echo "$file_name is a device file"
else
	echo "$file_name is an unknown file"
fi
