<?php
class AdminAction extends Action
{
	public static $errMsg;

	public function authorize($userID)
	{
		if (!$userID) $userID = $_SESSION["uid"];
		$userDao = D("User");
		$user = $userDao->getUserByID($userID);
		if ($user = $userDao->getUserByID($userID))
		{
			if ($user["authority"]==true) return true; else return false;
		}
		else return false;
	}

	public function freezeUser($userID)
	{
		if (!$userID) $userID = $_GET["userID"];
		if (A("User")->islogin() && $this->authorize()) {
			$userDao = D("User");
			$user = $userDao->getUserByID($userID);
			if ($user) {
				dump($user);
				$user['state'] = 1;
				$userDao->updateUser($user);
				$this->redirect("Home-index/home", null, 1, "用户冻结成功");
			}
			else {
				$this->errMsg = "找不到指定用户";
				$this->redirect("Home-index/home", null, 1, $this->errMsg);
			}
		}
		else {
			$this->errMsg = "无权限冻结用户";
			$this->redirect("Home-index/home", null, 1, $this->errMsg);
		}
	}

	public function unfreezeUser($userID)
	{
		if (!$userID) $userID = $_GET["userID"];
		if (A("User")->islogin() && $this->authorize()) {
			$userDao = D("User");
			$user = $userDao->getUserByID($userID);
			if ($user) {
				dump($user);
				$user['state'] = 0;
				$userDao->updateUser($user);
				$this->redirect("Home-index/home", null, 1, "用户解冻成功");
			}
			else {
				$this->errMsg = "找不到指定用户";
				$this->redirect("Home-index/home", null, 1, $this->errMsg);
			}
		}
		else {
			$this->errMsg = "无权限解冻用户";
			$this->redirect("Home-index/home", null, 1, $this->errMsg);
		}
	}

	public function removeUser($userID)
	{
		if (!$userID) $userID = $_GET["userID"];
		if (A("User")->islogin() && $this->authorize()) {
			$userDao = D("User");
			$userDao->removeUserByID($userID); //TODO connected with relational model
			$this->redirect("Home-index/home", null, 1, "删除成功");
		}
		else {
			$this->errMsg = "无权限删除用户";
			$this->redirect("Home-index/home", null, 1, $this->errMsg);
		}
	}

	public function listAllUsers()
	{
		if (A("User")->islogin() && $this->authorize()) {
			$userDao = D("User");
			$userList = $userDao->getAllUsers();
			$this->assign("userList", $userList);
			$this->display("Admin:Admin:list");
		}
		else {
			$this->errMsg = "无权限查询用户";
			$this->redirect("Home-Index/home", null, 1, $this->errMsg);
		}
	}
}

?>
