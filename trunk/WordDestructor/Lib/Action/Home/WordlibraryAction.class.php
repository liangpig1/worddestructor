<?php
class WordlibraryAction extends Action
{
	public function deleteLibrary($libraryID)
	{
		$libraryDao = D("library");
	}
	
	public function mergeLibrary($libraryID1,$libraryID2)
	{
	}
	
	public function newLibrary($listofword,$discription)
	{
	}
	
	public function listLibrariesByCondition($conditon = null)
	{
	}
	
	public function listLibraryByUser($userID)
	{
	}
	
}

?>