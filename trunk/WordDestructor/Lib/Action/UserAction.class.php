<?php
class UserAction extends Action
{
	public function register($regInfo)
	{
		if (!$regInfo) 
			$regInfo = array("username"=>$_POST["username"], "pwd"=>$_POST["pwd"]);

		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
			$userDao = D("User");
			if (checkuserdata($regInfo)) {
				if ($userDao->getUserByName($regInfo["username"])) {
					$this->redirect("Home-Index/index", null, 1, "已存在该用户名");
				}
				else {
					$uid = $userDao->insertUser($regInfo);
					if ($userDao->getError()) {
						$this->redirect("Home-Index/home", null, 1, "添加用户失败");
					}
					else {
                        $wordrefDao = D("Wordref");
                        $wordrefDao->addWordrefsByUser($uid);
						echo "注册成功.正在登录中...<br/>";
						$this->login($regInfo);
					}
				}
			}
			else {
				$this->redirect("Home-Index/home", null, 1, "信息不全");
			}
		}
		catch (Exception $e)
		{
			$this->redirect("Home-Index/home", null, 1, "用户已经处于登录状态，无法注册");
		}
	}

	public function login($logInfo)
	{
		if (!$logInfo) 
			$logInfo = array("username"=>$_POST["username"], "pwd"=>$_POST["pwd"]);

		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
			if (checkuserdata($logInfo)) {
				$condition = array("username"=>$logInfo["username"], "pwd"=>$logInfo["pwd"]);
				$userDao = D("User");
				$user = $userDao->getUserByName($logInfo["username"]);
				if ($user && $user["pwd"] == md5($logInfo["pwd"])) {
					if ($user["state"] == 0) {
						$_SESSION["uid"]=$user["id"];
						$this->redirect("Home-Index/home", null, 1, "登录成功");
					}
					else {
						$this->redirect("Home-Index/index", null, 1, "用户已被冻结");
					}
				}
				else {
					$this->redirect("Home-Index/index", null, 1, "用户名或密码错误");
				}
			}
			else {
				$this->redirect("Home-Index/index", null, 1, "登录信息不全");
			}
		}
		catch (Exception $e)
		{
			$this->redirect("Home-Index/home", null, 1, "用户已经处于登录状态，返回登录前页面");
		}
	}

	public function changePwd()
	{
		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
			$pwd = $_POST["pwd"];
            $repeatpwd = $_POST["repeatpwd"];
            if ($pwd != $repeatpwd) {
				$this->redirect("Home-Index/home", null, 1, "两次密码输入不一样");
            } else if (strlen($pwd) < 6) {
                $this->redirect("Home-Index/home", null, 1, "密码过短哦亲~");
            } else {
                $udata = array("id"=>$_SESSION["uid"], "pwd"=>md5($pwd));
                $userDao = D("User");
                $ret = $userDao->updateUser($udata);
                if ($ret) {
                    $this->redirect("Home-Index/home", null, 1, "修改成功");
                } else {
                    $this->redirect("Home-Index/home", null, 1, "修改失败");
                }
            }
		}
		catch (Exception $e)
		{
			$this->redirect("Home-Index/index", null, 1, "未登录，无法修改密码");
		}
	}
    
    public function showChangePwd()
    {
		try {	
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
            $this->display(":user:changepwd");
        }
		catch (Exception $e) {
			$this->redirect("Home-Index/index", null, 1, "未登录，无法修改密码");
		}
    }

	public function unlogin()
	{
		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
			unset($_SESSION["uid"]);
			$this->redirect("Home-Index/index", null, 1, "已登出，自动跳转至首页...");
		}
		catch (Exception $e)
		{
			$this->redirect("Home-Index/index", null, 1, "未登录，无法登出");
		}
	}
/*
	public function islogin() 
	{
		if (isset($_SESSION["uid"]) && $_SESSION["uid"] != "")
			return true;
		else return false;
	}*/

	public function isUserExist($name) {
		if (!$name) $name = $_GET["username"];
		$userDao = D("User");
		if ($userDao->getUserByName($name)) echo 1;
		else echo 0;
	}
}
?>
