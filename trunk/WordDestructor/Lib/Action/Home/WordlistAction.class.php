<?php
class WordlistAction extends Action
{
	public function addListByLibrary($libraryID)
	{
		if (!$libraryID) $libraryID = $_GET["libraryID"];
		$this->assign("libraryID",$libraryID);
		$this->display("Home:WordList:addlist");
	}
	
	public function addList($listName, $memConf,$wordnum, $libraryID)
	{
		if (A("User")->islogin())
		{
			if (!$listName) $listName = $_POST["listName"];
			if (!$memConf) $memConf = $_POST["memConf"];
			if (!$wordnum) $wordnum = $_POST["wordsnumber"];
			if (!$libraryID) $libraryID = $_POST["librID"];
			$listDao = D("WordList");
			$userDao = D("User");
			dump($_POST);
			$listData = array("name"=>$listName, "userID"=>$_SESSION["uid"], "memo"=>$memConf);
			$ret = $listDao->addWordList($listData);
			if ($ret) {
				$wordRefDao = D("WordRef");
				$wordRefList = $wordRefDao->getWordRefsForStudy($_SESSION['uid'],$libraryID);
				if(count($wordRefList) < $wordnum ) 
					$wordnum = $wordRefList;
				for($i = 0 ; $i < $wordnum ;$i = $i + 1){
					$wordRef = $wordRefList[rand(0,count($wordRefList)-1)];
					$wordRefDao->attachWordRefToList($_SESSION['uid'],$wordRef["wordId"],$ret);
				}
				
				$this->viewList($ret);
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
			$this->assign("listID", $listID);
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
			$lists = $listDao->getListsByUser($_SESSION["uid"]);
            $wordLists = Array();
            foreach ($lists as $item)
            {
                $out["name"] = $item["name"];
                $wordrefDao = D("Wordref");
                $listsel = $wordrefDao->getWordRefsByList($item["id"]);
                $out["size"] = sizeof($listsel);
                $memo = $item["memo"];
                $out["progress"] = $item["progress"]."/".strlen($memo);
                $next = $time["next"];
                //$next = time();
                $out["next"] = format_time($next);
                if (time() > $next) {
                    $out["test"] = true;
                } else {
                    $out["test"] = false;
                }
                $out["id"] = $item["id"];
                array_push($wordLists, $out);
            }
			$this->assign("wordLists", $wordLists);
			$this->display("Home:Wordlist:list");
		}
		else {
			$this->redirect("Home-Index/index", null, 1, "未登录");
		}
	}
	
	public function isListExist($name){
		if(!$name) $name = $_GET["listname"];
		$listDao = D("Wordlist");
		if($listDao->getListByName($name)) echo 1;
		else echo 0;
	}
}
?>
