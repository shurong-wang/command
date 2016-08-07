<?php
require("redis.php");
if(empty($_POST['username']) || empty($_POST['password'])){
?>
<form action="" method="post">
	用户:<input type="text" name="username" /><br />
	密码:<input type="password" name="password" /><br />
	<input type="submit" value="登陆" /> 
</form>
<?php
}else{
	$username = $_POST['username'];
	$pass = $_POST['password'];
	$uid = $redis->get("username:" . $username);
	if($uid){
		$password = $redis->hget("user:" . $uid, "password");
		if(md5($pass) == $password){
			$auth = md5(uniqid($username, true));
			$redis->set("auth:" . $auth, $uid);
			setcookie("auth", $auth, time() + 86400);
			header("location:list.php");
		}else{
?>
	<p>密码错误，<a href="login.php">请重新登录！</a></p>
<?php
		}
	}else{
?>
	<p>用户不存在，<a href="login.php">请重新输入！</a></p>
<?php
	}
}
?>
