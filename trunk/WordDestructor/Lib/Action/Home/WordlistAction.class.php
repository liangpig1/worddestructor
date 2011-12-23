<?php
class WordlistAction extends Action
{
	public function addListByLibrary($libraryID)
	{
		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
			if (!$libraryID) $libraryID = $_GET["libraryID"];
			$this->assign("libraryID",$libraryID);
			$this->display("Home:WordList:addlist");
		}
		catch (Exception $e)
		{
			echo "没有权限添加词单";
		}
	}
	
	public function addList($listName, $memConf,$wordnum, $libraryID)
	{
		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
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
		catch (Exception $e)
		{
			echo "未登录";
		}
	}

	public function addWordToList($listId, $wordId)
	{
		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
            $userId = $_SESSION["uid"];
            $wordrefDao = D("Wordref");
            $ret = $wordrefDao->attachWordrefToList($userId, $wordId, $listId);
		}
		catch (Exception $e)
		{
			echo "未登录";
		}
	}

	public function removeList($listID)
	{
		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
			if (!$listID) $listID = $_GET["listID"];
			$listDao = D("Wordlist");
			$ret = $listDao->removeWordList($listID);
			if ($ret) {
				$this->redirect("Home-Index/home", null, 1, "词单删除成功");
			} else {
				$this->redirect("Home-Index/home", null, 1, "指定词单未找到");
			}
		}
		catch (Exception $e)
		{
			echo "未登录";
		}
	}

    public function viewList($listID)
    {
		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);

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
		} 	
		catch (Exception $e)
		{
			echo "未登录";
		}
    }
    
    public function deattachWordref($wordId)
    {
       try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);

            if (!$wordId) $wordId = $_GET["wordrefId"];
            $wordrefDao = D("Wordref");
            $ret = $wordrefDao->deattachWordrefById($wordId, $_SESSION["uid"]);
            if ($ret) {
				echo "词条删除成功";
			} else {
				echo  "删除错误";
			}
	   }
        catch (Exception $e)
		{
			echo "未登录";
		}
    }
    
    public function learnedWordref($wordId)
    {
       try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);

            if (!$wordId) $wordId = $_GET["wordrefId"];
            $wordrefDao = D("Wordref");
            $info["id"] = $wordId;
            $info["state"] = 1;
            $wordrefDao->setWordref($info);
            $ret = $wordrefDao->deattachWordrefById($wordId, $_SESSION["uid"]);
            if ($ret) {
				echo "词条删除成功";
			} else {
				echo "删除错误";
			}
	   } 
	   catch (Exception $e)
		{
			echo "未登录";
		}
    }
    
    public function giveupWordref($wordId)
    {
       try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
            if (!$wordId) $wordId = $_GET["wordrefId"];
            $wordrefDao = D("Wordref");
            $info["id"] = $wordId;
            $info["state"] = 2;
            $wordrefDao->setWordref($info);
            $ret = $wordrefDao->deattachWordrefById($wordId, $_SESSION["uid"]);
            if ($ret) {
				echo "词条删除成功";
			} else {
				echo "删除错误";
			}
	   }
        catch (Exception $e)
		{
			echo "未登录";
		}
    }
    
	public function listWordListsByUser()
	{
		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
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
		catch (Exception $e)
		{
			echo "未登录";
		}
	}
	
	public function isListExist($name)  {
		if(!$name) $name = $_GET["listname"];
		$listDao = D("Wordlist");
		if($listDao->getListByName($name)) echo 1;
		else echo 0;
	}
    
    public function studyList($listId) {
        try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
            if (!$listId) $listId = $_GET["listID"];
            
            $uid = $_SESSION["uid"];
            $data["test"] = $listId;
            $data["id"] = $uid;
            D("User")->updateUser($data);
            
            D("Test")->where("userId=".$uid)->delete();
            $reflist = D("Wordref")->getWordrefsByList($listId);
            $tests = Array();
            foreach ($reflist as $ref)
            {
                $testcase["wordId"] = $ref["wordId"];
                $testcase["userId"] = $ref["userId"];
                array_push($tests, $testcase);
            }
            D("Test")->addAll($tests);
            $this->display("Home:Wordlist:studysel");
        }
		catch (Exception $e)
		{
			echo "未登录";
		}
    }
    
    public function studyc2e() {
       try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
            $uid = $_SESSION["uid"];
            $user = D("User")->getUserByID($uid);
            $listId = $user["test"];
            $list = D("Wordlist")->getListById($listId);
            $info["lastans"] = "";
            D("Test")->where("userId=".$uid)->save($info);
            
            $condition["userId"] = $uid;
            $condition["correct"] = 0;
            $reflist = D("Test")->where($condition)->select();
            $studyList = Array();
            foreach ($reflist as $ref)
            {
                $word = D("Word")->getWordById($ref["wordId"]);
                $studycase["chn"] = $word["chn"];
                $studycase["eng"] = $word["eng"];
                $studycase["wordId"] = $word["id"];
                array_push($studyList, $studycase);
            }
            
            $size = count($studyList);
            for ($i = 0; $i < $size; ++$i) {
                $in1 = rand(0, $size - 1);
                $in2 = rand(0, $size - 1);
                $t = $studyList[$in1];
                $studyList[$in1] = $studyList[$in2];
                $studyList[$in2] = $t;
            }
            
            if ($size > 0) 
            {
                $listName = $list["name"];
                $this->assign("listName", $listName);
                $this->assign("studyList", $studyList);
                $this->display("Home:Wordlist:studyc2e");
            } else {
                D("Test")->where("userId=".$uid)->delete();
                $data["id"] = $uid;
                $data["test"] = 0;
                D("User")->updateUser($data);
                if ($list["next"] < time()) {
                    if ($update["progress"] >= strlen($list["memo"])) {
                        $day = time() + 24 * 60 * 60 * 10000;
                        D("Wordref")->setStateByList(1, $listId);
                    } else {
                        $day = 24 * 60 * 60 * ($list["memo"][$update["progress"]] - '0');
                    }
                    $update["progress"] = $list["progress"] + 1;
                    $update["next"] = time() + $day;
                    $update["id"] = $listId;
                    D("Wordlist")->updateWordList($update);
                }
                $this->display("Home:Wordlist:finishstudy");
            }
        }
		catch (Exception $e)
		{
			echo "未登录";
		}
    }
    
    public function correctc2e() {
        try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
            $uid = $_SESSION["uid"];
            $user = D("User")->getUserByID($uid);
            $listId = $user["test"];
            $reflist = D("Wordref")->getWordrefsByList($listId);
            
            $answers = $_POST["answer"];
            $wordIds = $_POST["wordId"];
            $condition["userId"] = $uid;
            
            for ($i = 0; $i < count($answers); ++$i) {
                $answer = $answers[$i];
                $wordId = $wordIds[$i];
                $word = D("Word")->getWordById($wordId);
                $condition["wordId"] = $wordId;
                
                if ($word["eng"] == $answer) {
                    $datac["correct"] = true;
                    $datac["lastans"] = $answer;
                    D("Test")->where($condition)->save($datac);
                } else {
                    $dataa["lastans"] = $answer;
                    D("Test")->where($condition)->save($dataa);
                }                
            }
		}
		catch (Exception $e)
		{
			echo "未登录";
		}
    }
    
    public function checkc2e() {
       try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
            $uid = $_SESSION["uid"];
            $user = D("User")->getUserByID($uid);
            $listId = $user["test"];
            $tests = D("Test")->where("userId=".$uid)->select();
            
            $resultList = Array();
            foreach ($tests as $test)
            {
                if (($test["lastans"] != "") || (!$test["correct"])) {
                    $wordId = $test["wordId"];
                    $word = D("Word")->getWordById($wordId);
                    $result["chn"] = $word["chn"];
                    $result["eng"] = $word["eng"];
                    if ($test["correct"]) {
                        $result["error"] = false;
                        $result["result"] = "正确";
                    } else {
                        $result["error"] = true;
                        $result["result"] = "错误，用户输入：".$test["lastans"];
                    }
                    array_push($resultList, $result);
                }
            }
            
            $this->assign("resultList", $resultList);
            $list = D("Wordlist")->getListById($listId);
            $listName = $list["name"];
            $this->assign("listName", $listName);
            $this->assign("studyList", $studyList);
            $this->display("Home:Wordlist:checkc2e");
	   } 
	   catch (Exception $e)
		{
			echo "未登录";
		}
    }
    
    public function studye2c() {
       try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
            $uid = $_SESSION["uid"];
            $user = D("User")->getUserByID($uid);
            $listId = $user["test"];
            $list = D("Wordlist")->getListById($listId);
            $info["lastans"] = "";
            D("Test")->where("userId=".$uid)->save($info);
            
            $condition["userId"] = $uid;
            $condition["correct"] = 0;
            $reflist = D("Test")->where($condition)->select();
            $studyList = Array();
            foreach ($reflist as $ref)
            {
                $word = D("Word")->getWordById($ref["wordId"]);
                $studycase["chn"] = $word["chn"];
                $studycase["eng"] = $word["eng"];
                $studycase["wordId"] = $word["id"];
                array_push($studyList, $studycase);
            }
            
            $size = count($studyList);
            for ($i = 0; $i < $size; ++$i) {
                $in1 = rand(0, $size - 1);
                $in2 = rand(0, $size - 1);
                $t = $studyList[$in1];
                $studyList[$in1] = $studyList[$in2];
                $studyList[$in2] = $t;
            }
            
            if ($size > 0) 
            {
                $listName = $list["name"];
                $this->assign("listName", $listName);
                $this->assign("studyList", $studyList);
                $this->display("Home:Wordlist:studye2c");
            } else {
                D("Test")->where("userId=".$uid)->delete();
                $data["id"] = $uid;
                $data["test"] = 0;
                D("User")->updateUser($data);
                if ($list["next"] < time()) {
                    if ($update["progress"] >= strlen($list["memo"])) {
                        $day = time() + 24 * 60 * 60 * 10000;
                        D("Wordref")->setStateByList(1, $listId);
                    } else {
                        $day = 24 * 60 * 60 * ($list["memo"][$update["progress"]] - '0');
                    }
                    $update["progress"] = $list["progress"] + 1;
                    $update["next"] = time() + $day;
                    $update["id"] = $listId;
                    D("Wordlist")->updateWordList($update);
                }
                $this->display("Home:Wordlist:finishstudy");
            }
        }
		catch (Exception $e)
		{
			echo "未登录";
		}
    }
    
    public function correcte2c()
    {
        try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
            $uid = $_SESSION["uid"];
            $user = D("User")->getUserByID($uid);
            $listId = $user["test"];
            $reflist = D("Wordref")->getWordrefsByList($listId);
            
            $answers = $_POST["answer"];
            $condition["userId"] = $uid;
            dump($answers);
            
            for ($i = 0; $i < count($answers); ++$i) {
                $wordId = $answers[$i];
                $word = D("Word")->getWordById($wordId);
                $condition["wordId"] = $wordId;
                
                $datac["correct"] = true;
                $datac["lastans"] = "biu";
                D("Test")->where($condition)->save($datac);          
            }
		} 
		catch (Exception $e)
		{
			echo "未登录";
		}
    }
    
    public function checke2c()
    {
        try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
            $uid = $_SESSION["uid"];
            $user = D("User")->getUserByID($uid);
            $listId = $user["test"];
            $tests = D("Test")->where("userId=".$uid)->select();
            
            $resultList = Array();
            foreach ($tests as $test)
            {
                if (($test["lastans"] == "biu") || (!$test["correct"])) {
                    $wordId = $test["wordId"];
                    $word = D("Word")->getWordById($wordId);
                    $result["chn"] = $word["chn"];
                    $result["eng"] = $word["eng"];
                    if ($test["correct"]) {
                        $result["error"] = false;
                        $result["result"] = "记得";
                    } else {
                        $result["error"] = true;
                        $result["result"] = "不记得";
                    }
                    array_push($resultList, $result);
                }
            }
            
            $this->assign("resultList", $resultList);
            $list = D("Wordlist")->getListById($listId);
            $listName = $list["name"];
            $this->assign("listName", $listName);
            $this->assign("studyList", $studyList);
            $this->display("Home:Wordlist:checke2c");
		} 
		catch (Exception $e)
		{
			echo "未登录";
		}
    }
}
?>
