<?php
class WordlibraryAction extends Action
{
	public function show()
	{
		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
            $libDao = D("Wordlibrary");
            $liblist = $libDao->getAllLibraries();
            $this->assign("libs", $liblist);
            $this->display("Home:Wordlibrary:show");
		} 
		catch (Exception $e){
            $errMsg = "无权限操作";
            echo 0;
			//$this->redirect("Home-Index/index", null, 1, $errMsg);
        }
	}
    
	public function removeLibrary($libraryID)
	{
		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
            if (!$libraryID) $libraryID = $_GET["libraryId"];
            $wordrefDao = D("Wordref");
            $wordrefDao->removeWordrefsByLib($libraryID);
            $wordDao = D("Word");
            $wordDao->removeWordsByLibId($libraryID);
            $ret = $libraryDao = D("Wordlibrary");
            $libraryDao->removeWordLibrary($libraryID);
            if ($ret) {
				echo "词库删除成功";
			} else {
				echo "删除失败";
			}
		} 
		catch (Exception $e)
		{
            $errMsg = "无权限操作";
            echo 0;
			//$this->redirect("Home-Index/index", null, 1, $errMsg);
        }
	}
	
	public function addLibrary($name)
	{
		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
			if (!$name) $name = $_POST["name"];
			foreach ($_FILES as $file)
			{
				$fsrc = $file["tmp_name"];
				$fbuf = file_get_contents($fsrc);
				$listOfWords = fileParse($fbuf);
			}
			$libraryDao = D("Wordlibrary");
			$wordDao = D("Word");
			$libInfo["name"] = $name;
			if (!$name || !$listOfWords) {
				$this->redirect("Home-Index/home", null, 1, "信息不完整");
			}
			else {
				$libID = $libraryDao->addWordLibrary($libInfo);
				if ($libraryDao->getError())
					$this->redirect("Home-Index/home", null, 1, "添加失败");
				else 
				{
					$wordrefDao = D("Wordref");
					foreach($listOfWords as &$word) $word["libID"] = $libID;
					if (!$wordDao->addWordList($listOfWords)) $this->redirect("Home-Index/home", null, 1, "单词添加失败");
					$ret = $wordrefDao->addWordrefsByLib($libID);
					if ($ret) $this->redirect("Home-Index/home", null, 1, "词库上传成功");
					else $this->redirect("Home-Index/home", null, 1, "单词关联建立失败");
				}
			}
		}
		catch (Exception $e)
		{
			$this->redirect("Home-Index/index", null, 1, "无权限");
		}
	}
	
	public function listAllLibrary()
	{
		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
			$libraryDao = D("Wordlibrary");
			$wordLibraries = $libraryDao->getAllLibraries();
			$this->assign("wordLibraries", $wordLibraries);
			$this->display("Home:Wordlibrary:select_library");
		}
		catch (Exception $e)
		{
			$this->errMsg = "未登录";
			$this->redirect("Home-Index/index", null, 1, $this->errMsg);
		}
	}
}
?>
