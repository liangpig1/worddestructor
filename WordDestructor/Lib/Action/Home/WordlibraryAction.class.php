<?php
class WordlibraryAction extends Action
{
	public function removeLibrary($libraryID)
	{
		$libraryDao = D("Wordlibrary");
		$libraryDao->removeWordLibrary($libraryID);
	}
	
	public function addLibrary($name,$listOfWord)
	{
		$libraryDao = D("Wordlibrary");
		$libID = $libraryDao->getLastInsID();
		$wordDao = D("Word");
		$libInfo["name"] = $name;
		$libraryDao->addWordLibrary($libInfo);
		foreach($word as $listOfWord){
			$wordDao->addWord($word["eng"],$word["chn"],$libDao);
		}
	}
	
	public function listLibrariesByCondition($conditon = null)
	{
	}
	
	public function listLibraryByUser($userID)
	{
	}
	
}

?>