<?php
ini_set('default_socket_timeout', -1);
error_reporting(E_ERROR);
//实例化
$redis = new Redis();

//连接服务器
$redis->connect("127.0.0.1");

//授权
//$redis->auth("password");

//选择库
$redis->select("1");

//查看键
//$data = $redis->keys("*");
//var_dump($data);