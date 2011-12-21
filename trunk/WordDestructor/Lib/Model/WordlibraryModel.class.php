<?php
class WordlibraryModel extends Model
{
	public function getLibrary($libraryID)
	{
		return $this->where("libID=".$libraryID)->select();
	}
	
	public function removeLibrary($libraryID)
	{
		$this->where("libID=".$libraryID)->delete();
	}
	
	public function insertLibrary($description,$listOfWord)
	{
		
	}
}
?>