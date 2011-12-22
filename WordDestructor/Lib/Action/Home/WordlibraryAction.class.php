<?php
class WordlibraryAction extends Action
{
	public function show()
	{
		if (A("User")->islogin()) //TODO: Admin Authorize (xyt)
        {
            $libDao = D("Wordlibrary");
            $liblist = $libDao->getAllLibraries();
            $this->assign("libs", $liblist);
            $this->display("Home:Wordlibrary:show");
        } else {
            $errMsg = "无权限操作";
            echo 0;
			//$this->redirect("Home-Index/index", null, 1, $errMsg);
        }
	}
    
	public function removeLibrary($libraryID)
	{
        if (A("User")->islogin()) //TODO: Admin Authorize (xyt)
        {
            if (!$libraryID) $libraryID = $_GET["libraryId"];
            $libraryDao = D("Wordlibrary");
            $ret = $libraryDao->removeWordLibrary($libraryID);
            if ($ret) {
				echo "词库删除成功";
			} else {
				echo "删除失败";
			}
        } else {
            $errMsg = "无权限操作";
            echo 0;
			//$this->redirect("Home-Index/index", null, 1, $errMsg);
        }
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
				$this->redirect("Home-Index/home", null, 1, "信息不完整");
			}
			else {
				$libID = $libraryDao->addWordLibrary($libInfo);
				if ($libraryDao->getError())
				{
					$this->redirect("Home-Index/home", null, 1, "添加失败");
				}
				else 
				{
					foreach($listOfWords as $word)
					{
						$wordDao->addWord($word["eng"], $word["chn"], $libID);
						if ($wordDao->getError()) {
							break;
						}
					}
					$this->redirect("Home-Index/home", null, 1, "词库上传成功");
				}
			}
		}
		else {
			$this->redirect("Home-Index/index", null, 1, "无权限");
		}
	}
}
?>
