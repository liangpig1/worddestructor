<?php
class UserAction extends Action
{
	$this->ErrorMsg = "";

	public function register($regInfo)
	{
		if (!$regInfo) 
			$regInfo = array("username"=>$_POST["username"], "pwd"=>$_POST["pwd"]);
		if ($this->islogin()) {
			echo "logined";
		}
		else {
			$userDao = D("User");
			if (checkuserdata($regInfo)) {
				if ($userDao->where("username=".$regInfo["username"])->select()) {
					echo "Register Fail";
					return false;
				}
				else {
					$userDao->add($regInfo);
					if ($userDao->getError()) {
						$this->ErrorMsg = "Add Fail";
						return false;
					}
					else {
						$this->ErrorMsg = null;
						return true;
					}
				}
			}
			else echo "Data Not Complete";
		}
	}

	public function login($logInfo)
	{
		if (!$logInfo) 
			$logInfo = array("username"=>$_POST["username"], "pwd"=>$_POST["pwd"]);
		if ($this->isLogin()) {
			return true;
		}
		else {
			if (checkuserdata($logInfo)) {
				$condition = array("username"=>$logInfo["username"], "pwd"=>$logInfo["pwd"]);
				$userDao = D("User");
				if ($userDao->where($condition)->select()) {
					echo "login Sucessful";
					$SESSION["uid"]=$userID;
					return true;
				}
				else {
					echo "Wrong username or password";
					return false;
				}
			}
			else {
				echo "Data Not Complete";
				return false;
			}
		}
	}

	public function unlogin()
	{
		if ($this->isLogin()) {
			unset($SESSION["uid"]);
			echo "unlogin successful";
		}
		else echo "not login";
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
