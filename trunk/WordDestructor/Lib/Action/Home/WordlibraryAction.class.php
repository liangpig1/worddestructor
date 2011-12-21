<?php
class WordlibraryAction extends Action
{
	public static $errMsg;

	public function show()
	{
		$this->display("Home:Wordlibrary:show");
	}

	public function removeLibrary($libraryID)
	{
		$libraryDao = D("Wordlibrary");
		$libraryDao->removeWordLibrary($libraryID);
	}
	
	public function addLibrary($name)
	{
		if (A("User")->islogin()) //TODO only admin can
		{
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
				$this->errMsg = "词库信息不完整";
				$this->redirect("Home-index/home", null, 1, $this->errMsg);
			}
			else {
				$libID = $libraryDao->addWordLibrary($libInfo);
				if ($libraryDao->getError())
				{
					$this->errMsg = "数据库添加词库出错";
					$this->redirect("Home-index/home", null, 1, $this->errMsg);
				}
				else 
				{
					foreach($listOfWords as $word)
					{
						$wordDao->addWord($word["eng"], $word["chn"], $libID);
						if ($wordDao->getError()) {
							$this->errMsg = "单词添加出错";
							$this->redirect("Home-index/home", null, 1, $this->errMsg);
							break;
						}
					}
					$this->errMsg = null;
					$this->redirect("Home-index/home", null, 1, "词库上传成功");
				}
			}
		}
		else {
			$this->errMsg = "未登录";
			$this->redirect("Home-Index/index", null, 1, $this->errMsg);
		}
	}
	
	public function listAllLibrary()
	{
	}
}
?>
