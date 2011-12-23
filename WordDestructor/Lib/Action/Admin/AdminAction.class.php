<?php
class AdminAction extends Action
{
	public function freezeUser($userID)
	{
		if (!$userID) $userID = $_GET["userID"];
		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
			if ($userID == $_SESSION["uid"]) {
				echo "用户无法冻结自己";
			}
			else {
				$userDao = D("User");
				$user = $userDao->getUserByID($userID);
				if ($user) {
					$user['state'] = 1;
					$userDao->updateUser($user);
					echo "用户冻结成功";
				}
				else {
					echo "找不到指定用户";
				}
			}
		}
		catch (Exception $e){
			echo 0;
		}
	}

	public function unfreezeUser($userID)
	{
		if (!$userID) $userID = $_GET["userID"];
		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
			if ($userID == $_SESSION["uid"]) {
				echo "用户无法解冻自己";
			}
			else {
				$userDao = D("User");
				$user = $userDao->getUserByID($userID);
				if ($user) {
					$user['state'] = 0;
					$userDao->updateUser($user);
					echo "用户解冻成功";
				}
				else {
					echo "找不到指定用户";
				}
			}
		}
		catch (Exception $e){
			echo 0;
			//$this->redirect("Home-index/home", null, 1, $this->errMsg);
		}
	}

	public function removeUser($userID)
	{
		if (!$userID) $userID = $_GET["userID"];
		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
			if ($userID == $_SESSION["uid"]) {
				echo "用户无法删除自己";
			}
			else {
				$userDao = D("User");
				$userDao->removeUserByID($userID); //TODO connected with relational model
                $wordrefDao = D("Wordref");
                $wordrefDao->removeWordrefsByUser($userID);
                $wordlistDao = D("Wordlist");
                $wordlistDao->removeWordListByUser($userID);
                D("Test")->where("userId=".$userID)->delete();
				echo "删除成功";
				//$this->redirect("Home-index/home", null, 1, "删除成功");
			}
		}
		catch (Exception $e){
			echo 0;
			//$this->redirect("Home-index/home", null, 1, $this->errMsg);
		}
	}

	public function listAllUsers()
	{
		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
			$userDao = D("User");
			$userList = $userDao->getAllUsers();
			$this->assign("userList", $userList);
			$this->display("Admin:Admin:list");
		}
		catch (Exception $e){
			echo 0;
			//$this->redirect("Home-Index/home", null, 1, $this->errMsg);
		}
	}
}

?>
