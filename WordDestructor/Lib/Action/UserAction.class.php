<?php
class UserAction extends Action
{
	public static $errMsg;

	public function register($regInfo)
	{
		if (!$regInfo) 
			$regInfo = array("username"=>$_POST["username"], "pwd"=>$_POST["pwd"]);
		if ($this->islogin()) {
			$this->errMsg = null;
			$this->redirect("Home-Index/home", null, 1, "已登录，自动跳转至前页面...");
		}
		else {
			$userDao = D("User");
			if (checkuserdata($regInfo)) {
				if ($userDao->getUserByName($regInfo["username"])) {
					$this->errMsg = "已存在该用户名";
					$this->redirect("Home-Index/index", null, 1, $this->errMsg);
				}
				else {
					$userDao->insertUser($regInfo);
					if ($userDao->getError()) {
						$this->errMsg = "添加用户失败";
						$this->redirect("Home-Index/home", null, 1, $this->errMsg);
					}
					else {
						$this->errMsg = null;
						echo "注册成功.正在登录中...<br/>";
						$this->login($loginData);
					}
				}
			}
			else {
				$this->errMsg = "信息不全";
				$this->redirect(U("Home-Index/home"), null, 1, $this->errMsg);
			}
		}
	}

	public function login($logInfo)
	{
		if (!$logInfo) 
			$logInfo = array("username"=>$_POST["username"], "pwd"=>$_POST["pwd"]);
		if ($this->isLogin()) {
			$this->errMsg = null;
			$this->redirect("Home-Index/home", null, 1, "已登录，自动登录前页面...");
		}
		else {
			if (checkuserdata($logInfo)) {
				$condition = array("username"=>$logInfo["username"], "pwd"=>$logInfo["pwd"]);
				$userDao = D("User");
				$user = $userDao->getUserByName($logInfo["username"]);
				dump($user);
				if ($user["pwd"] == $logInfo["pwd"]) {
					$this->errMsg = null;
					$_SESSION["uid"]=$user["id"];
					$this->redirect("Home-Index/home", null, 1, "已登录，自动登录前页面...");
				}
				else {
					$this->errMsg = "用户名或密码错误";
					$this->redirect("Home-Index/index", null, 1, $this->errMsg);
				}
			}
			else {
				$this->errMsg = "登录信息不全";
				$this->redirect("Home-Index/index", null, 1, $this->errMsg);
			}
		}
	}

	public function unlogin()
	{
		if ($this->islogin()) {
			unset($_SESSION["uid"]);
			$this->errMsg = null;
			$this->redirect("Home-Index/index", null, 1, "已登出，自动跳转至首页...");
		}
		else {
			$this->errMsg = "未登录，无法登出";
			$this->redirect(U("Home-Index/index"), null, 1, $this->errMsg);
		}
	}

	public function islogin() 
	{
		if (isset($_SESSION["uid"]) && $_SESSION["uid"] != "")
			return true;
		else return false;
	}

	public function getError()
	{
		return $this->errMsg;
	}
}
?>
