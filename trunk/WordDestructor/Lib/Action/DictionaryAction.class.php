<?php
class DictionaryAction extends Action
{
	public function show(){
		try {
			ProxyCollection::getInstance()->process($this, __FUNCTION__);
			$this->display(":Dictionary:show");
		}
		catch (Exception $e)
		{
		}
	}
	
	public function lookUpWord($searchContent){
		try {
			ProxyCollection::getInstance()->process($this, __FUNCTION__);

			if(!$searchContent) $searchContent = $_GET["searchContent"];
			$WordDao = D("Word");
			$isEnglish = true;
			$resultList = array();

			for($i = 0 ; $i < strlen($searchContent);$i = $i + 1){
				if(('a'<=$searchContent[$i] && $searchContent[$i]<='z') || ('A'<=$searchContent[$i] && $searchContent[$i]<='Z') || $searchContent[$i] == ' ' || $searchContent[$i] == '-' ){}
				else $isEnglish = false;
			}
			if($isEnglish){
				$resultList = $WordDao->getWordByEng($searchContent);
			}
			else
				$resultList = $WordDao->getWordbyChn($searchContent);

			$WordlibraryDao = D("Wordlibrary");
			for($i = 0 ; $i < count($resultList) ; $i = $i + 1){
				$libOfWord = $WordlibraryDao->getLibraryByID($resultList[$i]["libID"]);
				$resultList[$i]["libname"] = $libOfWord[0]["name"];

			}
			$this->assign("resultList", $resultList);
			$this->assign("isEnglish", $isEnglish);
			$this->assign("length",count( $resultList));
			$this->display(":Dictionary:listresult");
		}
		catch (Exception $e)
		{
		}
	}
}
?>
