<?php
class WordlibraryAction extends Action
{
	public static $errMsg;
	
	public function removeLibrary($libraryID)
	{
		if(A("User")->islogin()){
			if(!$listID) $listID = $_GET["listID"];
			$libraryDao = D("Wordlibrary");
			$ret = $libraryDao->removeWordLibrary($libraryID);
			if($rest){
				$this->errMsg = null;
				$this->redirect("Home-Index/home",1,null,"词库删除成功");
			}
			else{
				$this->errMsg = "指定词库未找到";
				$this->redirect("Home-Index/home",1,null,$this->errMsg);
			}
		}
		else{
			$this->errMsg = "未登录";
			$this-redirect("Home-Index/index",1,null,$this->errMsg);
		}
	}
	
	public function addLibrary($libInfo)
	{
		if(A("User")->islogin()){
			if(!$libInfo)
				$libInfo = array("name"=>$_POST["name"],"listOfWord"=>$_POST["listOfWord"]);
			
			$libraryDao = D("Wordlibrary");	
			$wordDao = D("Word");
		
			$libID = $libraryDao->addWordLibrary($libInfo["name"]);
		
			foreach($word as $libInfo["listOfWord}"]){
				$wordDao->addWord($word["eng"],$word["chn"],$libID);
			}
		}
		else{
			$this->errMsg = "未登录";
			$this-redirect("Home-Index/index",1,null,$this->errMsg);
		}
		
	}
	
	public function getAllLibrary($libraryID)
	{
		if(A("User")->islogin()){
			if(!$listID) $listID = $_GET["listID"];
			$libraryDao = D("Wordlibrary");
			$libraryList = $libraryDao->getAllLibraries();
			$this->assign("libraryList", $libraryList);
			$this->display("Home:Library:list");
		}
		else{
			$this->errMsg = "未登录";
			$this-redirect("Home-Index/index",1,null,$this->errMsg);
		}
	}
}

?>