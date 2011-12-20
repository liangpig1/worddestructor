<?php
class UserAction extends Action
{
	var $errMsg;

	public function register($regInfo)
	{
		if (!$regInfo) 
			$regInfo = array("username"=>$_POST["username"], "pwd"=>$_POST["pwd"]);
		if ($this->islogin()) {
			$this->errMsg = null;
			return true;
		}
		else {
			$userDao = D("User");
			if (checkuserdata($regInfo)) {
				if ($userDao->where("username=".$regInfo["username"])->select()) {
					$this->errMsg = "Register Fail";
					return false;
				}
				else {
					$userDao->add($regInfo);
					if ($userDao->getError()) {
						$this->errMsg = "Add Fail";
						return false;
					}
					else {
						$this->errMsg = null;
						return true;
					}
				}
			}
			else {
				$this->errMsg = "Register Data not complete";
				return false;
			}
		}
	}

	public function login($logInfo)
	{
		if (!$logInfo) 
			$logInfo = array("username"=>$_POST["username"], "pwd"=>$_POST["pwd"]);
		if ($this->isLogin()) {
			$this->errMsg = null;
			return true;
		}
		else {
			if (checkuserdata($logInfo)) {
				$condition = array("username"=>$logInfo["username"], "pwd"=>$logInfo["pwd"]);
				$userDao = D("User");
				if ($userDao->where($condition)->select()) {
					$this->errMsg = null;
					$SESSION["uid"]=$userID;
					return true;
				}
				else {
					$this->errMsg = "Wrong username or password";
					return false;
				}
			}
			else {
				$this->errMsg = "Login Data not Complete";
				return false;
			}
		}
	}

	public function unlogin()
	{
		if ($this->islogin()) {
			unset($SESSION["uid"]);
			$this->errMsg = null;
			return true;
		}
		else {
			$this->errMsg = "Not login";
			return false;
		}
	}

	public function islogin() 
	{
		if (isset($SESSION["uid"]) && $SESSION["uid"] != "")
			return true;
		else return false;
	}

	public function getError()
	{
	}
}
?>
