<?php
class WordlibraryModel extends Model
{
	public function getLibrary($libraryID)
	{
		return $this->where("id=".$libraryID)->select();
	}
	
	public function getLibraryByID($libraryID)
	{
		return $this->where("id=".$libraryID)->select();
	}
	
	public function getAllLibraries()
	{
		return $this->select();
	}
	
	public function removeWordLibrary($libraryID)
	{
		return $this->where("id=".$libraryID)->delete();
	}
	
	//�½�һ���մʿ�
	public function addWordLibrary($libraryInfo)
	{
		return $this->add($libraryInfo);
	}
	
	public function updateWordLibrary($libraryInfo)
	{
		return $this->save($libraryInfo);
	}
}
?>
