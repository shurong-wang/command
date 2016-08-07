<a href="add.php">注册</a>
<?php
require("redis.php");
if(empty($_COOKIE['auth'])){
?>
	<a href="login.php">登陆</a>
<?php
}else{
	$uid = $redis->get("auth:" . $_COOKIE['auth']);
	$username = $redis->hget("user:" . $uid, "username");
?>
	欢迎您，<?php echo $username?>，<a href="logout.php">退出</a>
<?php
//用户总数
$count = $redis->lsize("uid");
//每页记录数
$page_size = 2;
//总页数
$page_count = ceil($count / $page_size);
//当前页码
$page_num = (!empty($_GET['page'])) ? $_GET['page'] : 1;
$prev_num = (($page_num - 1) <= 1) ? 1 : ($page_num-1);
$next_num = (($page_num + 1) >= $page_count) ? $page_count : ($page_num + 1);
//分页取数据
$start = ($page_num-1) * $page_size;
$end = ($page_num-1) * $page_size + $page_size - 1;
$uids = $redis->lrange("uid", $start, $end);
//var_dump($uids);

foreach($uids as $user){
	$users[] = $redis->hgetall("user:" . $user);
}
//var_dump($users);
?>
<table border=1>
	<tr>
		<th>uid</th>
		<th>username</th>
		<th>age</th>
		<th>操作</th>
	<tr>
<?php foreach($users as $user){?>
	<tr>
		<td><?php echo  $user['uid']?></td>
		<td><?php echo  $user['username']?></td>
		<td><?php echo  $user['age']?></td>
		<td><a href="del.php?id=<?php echo $user['uid']?>">删除</a> <a href="mod.php?id=<?php echo $user['uid']?>">编辑</a> 
		<?php if($_COOKIE['auth'] && $user['uid'] != $uid){?>
			<a href="addfans.php?uid=<?php echo $uid?>&fid=<?php echo $user['uid']?>">加关注</a></td>
		<?php }?>
	</tr>
<?php }?>
<tr>
	<td colspan="4">
		<a href="?page=<?php echo $prev_num?>">上一页</a>
		<a href="?page=<?php echo $next_num?>">下一页</a>
		<a href="?page=1">首页</a>
		<a href="?page=<?php echo $page_count?>">尾页</a>
		当前<?php echo $page_num?>页
		总共<?php echo $page_count?>页
		总共<?php echo $count?>个用户
	</td>
</tr>
</table>

<table border=1>
	<caption>我的关注</caption>
	<?php 
		$users = $redis->smembers("user:" . $uid . ":following");
		foreach($users as $user){
			$row = $redis->hgetall("user:" . $user);
	?>
	<tr>
		<td><?php echo $row['uid']?></td>
		<td><?php echo $row['username']?></td>
		<td><?php echo $row['age']?></td>
	</tr>
	<?php
		}
	?>
</table>

<table border=1>
	<caption>我的粉丝</caption>
	<?php 
		$users = $redis->smembers("user:" . $uid . ":followers");
		foreach($users as $user){
			$row = $redis->hgetall("user:" . $user);
	?>
	<tr>
		<td><?php echo $row['uid']?></td>
		<td><?php echo $row['username']?></td>
		<td><?php echo $row['age']?></td>
	</tr>
	<?php
		}
	?>
</table>
<?php 
}
?>