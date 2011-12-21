<?php
class WordlistAction extends Action
{
	public static $errMsg;

	public function addList($listName)
	{
		if (A("User")->islogin())
		{
		}
		else {
			$this->errMsg = "未登录";
			$this->redirect("Home-index/index", 1, null, $this->errMsg);
		}
	}

	public function removeList($listID)
	{
		if (A("User")->islogin())
			if (!$listID) $listID = $_GET["listID"];
			$listDao = D("Wordlist");
			$ret = $listDao->removeWordList($listID);
			if ($ret) {
				$this->errMsg = null;
				$this->redirect("Home-Index/home", 1, null, "词单删除成功");
			}
			else {
				$this->errMsg = "指定词单未找到";
				$this->redirect("Home-Index/home", 1, null, $this->errMsg);
			}
		}
		else {
			$this->errMsg = "未登录";
			$this->redirect("Home-Index/index", 1, null, $this->errMsg);
		}
	}

	public function studyList($listID)
	{
		if (A("User")->islogin())
		{
			$userDao = D("User");
			$userdata = $userDao->where("id=".$userID)->select();
			if ($userInfo) {
				$userdata["currentlistid"] = $listID;
				$userDao->save($userdata);
			}
		}
		else {
			$this->errMsg = "未登录";
			$this->redirect("Home-Index/index", 1, null, $this->errMsg);
		}
	}
?>
