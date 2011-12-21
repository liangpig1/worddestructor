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
			foreach ($_FILES as $file)
			{
				$fsrc = $file["tmp_name"];
				$fbuf = file_get_contents($fsrc);
				$wordList = fileParse($fbuf);
			}

			//$libraryDao = D("Wordlibrary");
			//$wordDao = D("Word");
			//$libInfo["name"] = $name;
		
			//$libID = $libraryDao->addWordLibrary($libInfo);
			//foreach($word as $listOfWord){
				//$wordDao->addWord($word["eng"],$word["chn"],$libID);
			//}
		}
		else {
		}
	}
	
	public function listAllLibrary()
	{
	}
}
?>
