<?php
class WordlibraryModel extends Model
{
	public function getLibrary($libraryID)
	{
		return $this->where("ID=".$libraryID)->select();
	}
	
	public function getAllLibraries()
	{
		return $this->select();
	}
	
	public function removeWordLibrary($libraryID)
	{
		$this->where("ID=".$libraryID)->delete();
	}
	
	//�½�һ���մʿ�
	public function addWordLibrary($libraryInfo)
	{
		return $this->add($libraryInfo);
	}
	
	public function updateWordLibrary($libraryInfo)
	{
		$this->save($libraryInfo);
	}
}
?>