<?php
require("redis.php");
$username = $_POST['username'];
$password = md5($_POST['password']);
$age = $_POST['age'];
$uid = $redis->incr("userid"); //主键自增
$redis->hmset("user:" . $uid, array("uid"=>$uid, "username"=>$username, "password"=>$password, "age"=>$age));
$redis->rpush("uid", $uid);	   //列表分页
$redis->set("username:" . $username, $uid);	//判断用户是否存在
header("location:list.php");