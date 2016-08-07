<?php

$memcache = new Memcache;

# 添加服务器到集群
$host = '192.168.42.40';
$port = '11211';
$memcache->addServer($host, $port);

$host = '127.0.0.1';
$port = '11212';
$memcache->addServer($host, $port);

$host = '192.168.42.75';
$port = '11211';
$memcache->addServer($host, $port);

# 常规操作
$result = $memcache->set('kang_title', 'PHP-34', 0, time()+3600);
var_dump($result);

// $item = $memcache->get('kang_title');
// var_dump($item);


$memcache->close();
