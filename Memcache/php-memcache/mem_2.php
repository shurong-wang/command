<?php
$memcache = new Memcache();

$host = '192.168.42.40';
$port = '11211';
$memcache->connect($host, $port);


// $result = $memcache->get('int');
// var_dump($result); echo '<br>';
// $result = $memcache->get('float');
// var_dump($result); echo '<br>';
// $result = $memcache->get('bool');
// var_dump($result); echo '<br>';
// $result = $memcache->get('string');
// var_dump($result); echo '<br>';
$result = $memcache->get('array');
var_dump($result); echo '<br>';
$result = $memcache->get('object');
var_dump($result); echo '<br>';
$result = $memcache->get('resource');
var_dump($result); echo '<br>';
$result = $memcache->get('null');
var_dump($result); echo '<br>';


$memcache->close();