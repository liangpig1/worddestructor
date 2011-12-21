<?php
class WordlibraryModel extends Model
{
	public function getLibrary($libraryID)
	{
		return $this->where("libID=".$libraryID)->select();
	}
	
	public function getAllLibraries()
	{
		return $this->select();
	}
	
	public function removeWordLibrary($libraryID)
	{
		$this->where("libID=".$libraryID)->delete();
	}
	
	//新建一个空词库
	public function addWordLibrary($libraryInfo)
	{
		$this->add($libraryInfo);
	}
	
	public function updateWordLibrary($libraryInfo)
	{
		$this->save($libraryInfo);
	}
}
?>