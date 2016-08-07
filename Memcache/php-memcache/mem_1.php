<?php
$memcache = new Memcache();

$host = '127.0.0.1';
$port = '11211';
$memcache->connect($host, $port);


// $memcache->set('class_name', 'shurong', 0, 3600);
// var_dump($memcache->get('class_name'));
// $memcache->flush();


# data type

// scalar 标量类型
// $result = $memcache->set('int', 42, 0, 3600);
// var_dump($result);echo '<br>';
// $result = $memcache->set('float', 42.24, 0, 3600);
// var_dump($result);echo '<br>';
// $result = $memcache->set('bool', false, 0, 3600);
// var_dump($result);echo '<br>';
// $result = $memcache->set('string', 'itcast', 0, 3600);
// var_dump($result);echo '<br>';

// $result = $memcache->set('array', array('title'=>'笑傲江湖', 'author'=>'金庸'), 0, 3600);
// var_dump($result);echo '<br>';
// $o = new StdClass;
// $o->title = 'php35';
// $o->address = '312';
// $result = $memcache->set('object', $o, 0, 3600);
// var_dump($result);echo '<br>';
// $result = $memcache->set('resource', mysql_connect('localhost:3306', 'root', '1234abcd'), 0, 3600);
// var_dump($result);echo '<br>';
// $result = $memcache->set('null', null, 0, 3600);
// var_dump($result);echo '<br>';

// $value = str_repeat('H', 400*1024);
// $result = $memcache->set('kang_item1', $value, 0, 3600);
// var_dump($result);

// $value = str_repeat('Z', 400*1024);
// $result = $memcache->set('kang_item2', $value, 0, 3600);
// var_dump($result);


// $value = str_repeat('K', 400*1024);
// $result = $memcache->set('kang_item3', $value, 0, 3600);
// var_dump($result);

$memcache->close();