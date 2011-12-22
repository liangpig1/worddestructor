<?php
class WordlistAction extends Action
{
	public static $errMsg;

	public function addList($listName, $memConf)
	{
		if (A("User")->islogin())
		{
			if (!$listName) $listName = $_POST["listName"];
			if (!$listConf) $listConf = $_POST["listConf"];
			$listDao = D("WordList");
			$userDao = D("User");

			$listData = array("name"=>$listName, "userID"=>$_SESSION["uid"], "memo"=>$memConf);
			$ret = $listDao->addWordList($listData);
			if ($ret) {
				$this->errMsg = null;
				$this->redirect("Home-Index/home", null, 1, $this->errMsg);
			}
			else {
				$this->errMsg = "添加失败";
				$this->redirect("Home-Index/home", null, 1, $this->errMsg);
			}
		}
		else {
			$this->errMsg = "未登录";
			$this->redirect("Home-Index/index", null, 1, $this->errMsg);
		}
	}

	public function addWordToList($listId, $wordId)
	{
		if (A("User")->islogin())
		{
            $userId = $_SESSION["uid"];
            $wordrefDao = D("WordRef");
            $ret = $wordrefDao->attachWordRefToList($userId, $wordId, $listId);
            if ($ret) {
                $this->errMsg = null;
            } else {
                $this->errMsg = "添加失败";
            }
		}
		else {
			$this->errMsg = "未登录";
		}
        $this->redirect("Home-Index/home", null, 1, $this->errMsg);
	}

	public function removeList($listID)
	{
		if (A("User")->islogin())
		{
			if (!$listID) $listID = $_GET["listID"];
			$listDao = D("Wordlist");
			$ret = $listDao->removeWordList($listID);
			if ($ret) {
				$this->errMsg = null;
				$this->redirect("Home-Index/home", null, 1, "词单删除成功");
			} else {
				$this->errMsg = "指定词单未找到";
				$this->redirect("Home-Index/home", null, 1, $this->errMsg);
			}
		} else {
			$this->errMsg = "未登录";
			$this->redirect("Home-Index/index", null, 1, $this->errMsg);
		}
	}

	public function studyList($listID)
	{
		if (A("User")->islogin())
		{
			$userDao = D("User");
			$userdata = $userDao->where("id=".$userID)->select();
			if ($userdata) {
				$userdata["currentlistid"] = $listID;
				$userDao->save($userdata);
			}
		}
		else {
			$this->errMsg = "未登录";
			$this->redirect("Home-Index/index", null, 1, $this->errMsg);
		}
	}
    
    public function viewList($listID)
    {
        if (A("User")->islogin())
        {
            if (!$listID) $listID = $_GET["listID"];
            $wordrefDao = D("WordRef");
            $wordrefs = $wordrefDao->getWordRefsByList($listID);
            $wordDao = D("Word");
            $words = Array();
            foreach ($wordrefs as $wordref)
            {
                array_push($words, $wordDao->getWordById($wordref["wordId"]));
            }
            $this->assign("words", $words);
            $this->display("Home:Wordlist:viewlist");
        } else {
            $this->errMsg = "未登录";
			$this->redirect("Home-Index/index", null, 1, $this->errMsg);
        }
    }
    
    public function deattachWordref($wordrefId)
    {
        if (A("User")->islogin())
        {
            if (!$wordrefId) $wordrefId = $_GET["wordrefId"];
            $wordrefDao = D("WordRef");
            $ret = $wordrefDao->deattachWordRefById($wordrefId);
            if ($ret) {
				$this->errMsg = "词条删除成功";
			} else {
				$this->errMsg = "删除错误";
			}
        } else {
            $this->errMsg = "未登录";
        }
        $this->redirect("Home-Index/index", null, 1, $this->errMsg);
    }
    
	public function listWordListsByUser()
	{
		if (A("User")->islogin())
		{
			$listDao = D("Wordlist");
			$wordLists = $listDao->getListsByUser($_SESSION["uid"]);
			$this->assign("wordLists", $wordLists);
			$this->display("Home:Wordlist:list");
		}
		else {
			$this->errMsg = "未登录";
			$this->redirect("Home-Index/index", null, 1, $this->errMsg);
		}
	}
}
?>
