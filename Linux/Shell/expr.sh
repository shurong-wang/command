#!/bin/sh
a=10
b=20
c=30
value1=`expr $a + $b + $c`
echo "The value of value1 is $value1"
value2=`expr $c / $b`
echo "The value of value2 is $value2"
value3=`expr $c \* $b`
echo "The value of value3 is $value3"
value4=`expr $a + $c / $b`
echo "The value of value4 is $value4"

