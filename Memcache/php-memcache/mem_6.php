<?php

# memcache扩展定义好的memcache，session存储处理器
ini_set('session.save_handler', 'memcache');

# 所使用的memcached服务器信息
ini_set('session.save_path', 'tcp://127.0.0.1:11211');

// ini_set('session.save_path', 'tcp://127.0.0.1:11211;tcp://host2:port2;tcp://host3:port3');
session_start();

$_SESSION['class_name'] = 'PHP34-itcast';

echo session_id();
