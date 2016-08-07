<?php
//利用反射感知redis类中可以操作的成员方法
$method = new ReflectionClass('Redis');	//通过Redis类实例化一个反射类对象
$rst = $method -> getMethods();			//获得Redis类中所有的成员方法
print_r($rst);
