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
	
	//�½�һ���մʿ�
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