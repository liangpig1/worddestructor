<?php
class WordlistAction extends Action
{
	public function addListByLibrary($libraryID)
	{
		$this->assign("libraryID",$libraryID);
		$this->display("Home:WordList:addlist");
	}
	
	public function addList($listName, $memConf)
	{
		if (A("User")->islogin())
		{
			if (!$listName) $listName = $_POST["listName"];
			if (!$memConf) $memConf = $_POST["memConf"];
			$listDao = D("WordList");
			$userDao = D("User");

			$listData = array("name"=>$listName, "userID"=>$_SESSION["uid"], "memo"=>$memConf);
			$ret = $listDao->addWordList($listData);
			if ($ret) {
				$WordRefDao = D("WordRef");
				
				$this->redirect("Home-Index/home", null, 1, "添加成功");
			}
			else {
				$this->redirect("Home-Index/home", null, 1, "添加失败");
			}
		}
		else {
			$this->redirect("Home-Index/index", null, 1, "未登录");
		}
	}

	public function addWordToList($listId, $wordId)
	{
		if (A("User")->islogin())
		{
            $userId = $_SESSION["uid"];
            $wordrefDao = D("WordRef");
            $ret = $wordrefDao->attachWordRefToList($userId, $wordId, $listId);
		}
		else {
        	$this->redirect("Home-Index/home", null, 1, "未登录");
		}
	}

	public function removeList($listID)
	{
		if (A("User")->islogin())
		{
			if (!$listID) $listID = $_GET["listID"];
			$listDao = D("Wordlist");
			$ret = $listDao->removeWordList($listID);
			if ($ret) {
				$this->redirect("Home-Index/home", null, 1, "词单删除成功");
			} else {
				$this->redirect("Home-Index/home", null, 1, "指定词单未找到");
			}
		} else {
			$this->redirect("Home-Index/index", null, 1,"未登录");
		}
	}

	public function studyList($listID)
	{
		if (A("User")->islogin())
		{
			$userDao = D("User");
			$wordRefDao = D("Wordref");
			$wordsInList = $wordRefDao->getWordsRefsByLib($listID);
			$this->assign("wlist", $wordsInList);
			$this->display("Home-Wordlist/studyList");
		}
		else {
			$this->redirect("Home-Index/index", null, 1, "未登录");
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
			$this->redirect("Home-Index/index", null, 1, "未登录");
        }
    }
    
    public function deattachWordref($wordrefId)
    {
        if (A("User")->islogin())
        {
            if (!$wordrefId) $wordrefId = $_GET["wordrefId"];
            $wordrefDao = D("WordRef");
            $ret = $wordrefDao->deattachWordRefById($wordrefId);
			$errMsg = "";
            if ($ret) {
				$errMsg = "词条删除成功";
			} else {
				$errMsg = "删除错误";
			}
        } else {
            $errMsg = "未登录";
        }
        $this->redirect("Home-Index/index", null, 1, $errMsg);
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
			$this->redirect("Home-Index/index", null, 1, "未登录");
		}
	}
	
	public function isListExist($name){
		if(!$name) $name = $_GET["listname"];
		$listDao = D("List");
		if($listDao ->getListByName($name)) echo 1;
		else echo 0;
	}
}
?>