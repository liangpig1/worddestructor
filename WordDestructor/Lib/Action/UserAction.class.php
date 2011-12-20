<?php
class UserAction extends Action
{
	public function register($regInfo)
	{
		if ($this->islogin()) {
			echo "logined";
		}
		else {
			$userDao = D("User");
			if (checkuserdata($regInfo)) {
				if ($userDao->where("username=".$regInfo["username"])->select()) {
					echo "Register Fail";
				}
				else {
					$userDao->add($regInfo);
					if ($userDao->getError()) {
						echo "Add Fail";
					}
					else echo "Success";
				}
			}
			else echo "Data Not Complete";
		}
	}

	public function login($logInfo)
	{
		if ($this->isLogin()) {
			echo "logined";
		}
		else {
			if (checkuserdata($logInfo)) {
				$condition = array("username"=>$logInfo["username"], "pwd"=>$logInfo["pwd"]);
				$userDao = D("User");
				if ($userDao->where($condition)->select()) {
					echo "login Sucessful";
					$SESSION["uid"]=$userID;
				}
				else echo "Wrong username or password";
			}
			else echo "Data Not Complete";
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

	public function selectList($userID, $listID)
	{
		$userDao = D("User");
		$userdata = $userDao->where("id=".$userID)->select();
		if ($userInfo) {
			$userdata["currentlistid"] = $listID;
			$userDao->save($userdata);
		}
		else {
			echo "User not Found";
		}
	}
}
?>
