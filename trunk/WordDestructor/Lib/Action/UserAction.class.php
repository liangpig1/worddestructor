<?php
class UserAction extends Action
{
	public function register($regInfo)
	{
		if (!$regInfo) 
			$regInfo = array("username"=>$_POST["username"], "pwd"=>$_POST["pwd"]);
		if ($this->islogin()) {
			$this->redirect("Home-Index/home", null, 1, "已登录，自动跳转至前页面...");
		}
		else {
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
                        $wordrefDao->addWordRefsByUser($uid);
						echo "注册成功.正在登录中...<br/>";
						$this->login($regInfo);
					}
				}
			}
			else {
				$this->redirect("Home-Index/home", null, 1, "信息不全");
			}
		}
	}

	public function login($logInfo)
	{
		if (!$logInfo) 
			$logInfo = array("username"=>$_POST["username"], "pwd"=>$_POST["pwd"]);
		if ($this->isLogin()) {
			$this->redirect("Home-Index/home", null, 1, "已登录，自动登录前页面...");
		}
		else {
			if (checkuserdata($logInfo)) {
				$condition = array("username"=>$logInfo["username"], "pwd"=>$logInfo["pwd"]);
				$userDao = D("User");
				$user = $userDao->getUserByName($logInfo["username"]);
				if ($user && $user["pwd"] == md5($logInfo["pwd"])) {
					if ($user["state"] == 0) {
						$_SESSION["uid"]=$user["id"];
						$this->redirect("Home-Index/home", null, 1, "已登录，自动登录前页面...");
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
	}

	public function changePwd()
	{
		if ($this->islogin())
		{
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
		else {
			$this->redirect("Home-Index/index", null, 1, "未登录，无法修改密码");
		}
	}
    
    public function showChangePwd()
    {
        if ($this->islogin())
		{
            $this->display(":user:changepwd");
        }
		else {
			$this->redirect("Home-Index/index", null, 1, "未登录，无法修改密码");
		}
    }

	public function unlogin()
	{
		if ($this->islogin()) {
			unset($_SESSION["uid"]);
			$this->redirect("Home-Index/index", null, 1, "已登出，自动跳转至首页...");
		}
		else {
			$this->redirect("Home-Index/index", null, 1, "未登录，无法登出");
		}
	}

	public function islogin() 
	{
		if (isset($_SESSION["uid"]) && $_SESSION["uid"] != "")
			return true;
		else return false;
	}

	public function isUserExist($name) {
		if (!$name) $name = $_GET["username"];
		$userDao = D("User");
		if ($userDao->getUserByName($name)) echo 1;
		else echo 0;
	}
}
?>