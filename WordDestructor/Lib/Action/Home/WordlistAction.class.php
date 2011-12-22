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
	{//TODO random seed
		if (A("User")->islogin())
		{
			if (!$listName) $listName = $_POST["listName"];
			if (!$memConf) $memConf = $_POST["memConf"];
			$memConf = implode($memConf);
			if (!$wordnum) $wordnum = $_POST["wordsnumber"];
			if (!$libraryID) $libraryID = $_POST["librID"];
			$listDao = D("Wordlist");
			$userDao = D("User");
			$listData = array("name"=>$listName, "userID"=>$_SESSION["uid"], "memo"=>$memConf, "progress"=>0, "next"=>time());
			$listID = $listDao->addWordList($listData);
			if ($listID) {
				$wordrefDao = D("Wordref");
				$wordrefList = $wordrefDao->getWordrefsForStudy($_SESSION['uid'],$libraryID);
				if ($wordrefList) {
					if(count($wordrefList)<$wordnum) $wordnum = $wordrefList;
					$len = count($wordrefList)-1;
					for($i = 0 ; $i < $wordnum ;$i = $i + 1){
                        if ($len - $i  <= 0) {
                            break;
                        }
						$x = rand(0,$len - $i);
						$wordref = $wordrefList[$x];
						$wordrefDao->attachWordrefToList($wordref['id'],$listID);
						//swap the wordref to make sure no repeat
						$t = $wordrefList[$x];
						$wordrefList[$x] = $wordrefList[$len - $i];
						$wordrefList[$len - $i] = $t;
					}
					if ($wordrefDao->getError()) {
						echo "单词添加到词单失败";
					}
					else {
						echo $listID;
					}
				}
				else echo "从词库中提取单词失败";
			}
			else echo "添加失败";
		}
		else {
			echo "未登录";
			$this->redirect("Home-Index/index", null, 1, "未登录");
		}
	}

	public function addWordToList($listId, $wordId)
	{
		if (A("User")->islogin())
		{
            $userId = $_SESSION["uid"];
            $wordrefDao = D("Wordref");
            $ret = $wordrefDao->attachWordrefToList($userId, $wordId, $listId);
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
			$wordrefDao = D("Wordref");
			$wordsInList = $wordrefDao->getWordsrefsByLib($listID);
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
            $wordrefDao = D("Wordref");
            $wordrefs = $wordrefDao->getWordrefsByList($listID);
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
            $wordrefDao = D("Wordref");
            $ret = $wordrefDao->deattachWordrefById($wordrefId);
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
                $listsel = $wordrefDao->getWordrefsByList($item["id"]);
                $out["size"] = sizeof($listsel);
                $memo = $item["memo"];
                $out["progress"] = $item["progress"]."/".strlen($memo);
                $next = $item["next"];
                $out["next"] = format_time($next);
                if (time() > $next) {
                    $out["test"] = true;
                } else {
                    $out["test"] = false;
                }
                if ($item["progress"] > strlen($memo)) {
                    $out["done"] = true;
                } else {
                    $out["done"] = false;
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
