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
	
	public function addLibrary($name,$listOfWord)
	{
		$libraryDao = D("Wordlibrary");
		$wordDao = D("Word");
		$libInfo["name"] = $name;
		
		$libID = $libraryDao->addWordLibrary($libInfo);
		foreach($word as $listOfWord){
			$wordDao->addWord($word["eng"],$word["chn"],$libID);
		}
	}
	
	public function listAllLibrary()
	{
	}
}
?>
