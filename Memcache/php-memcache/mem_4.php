<?php

$server_num = 3;//服务器数量 node_0, node_1, node_2

$key = 'kang_title';// KEY

# 先通过KEY，得到一个数， crc32()循环冗余算法。
$crc32_key = crc32($key);
echo $crc32_key;

echo '<br />';

echo $crc32_key % $server_num;

