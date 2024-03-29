<?php
class WordlistModel extends Model
{
    public function getListById($listId)
    {
        return $this->where("id=".$listId)->find();
    }
    
    public function getListByName($name)
    {
        return $this->where("name='".$name."'")->find();
    }

    public function getListsByUser($userId)
    {
        return $this->where("userId=".$userId)->select();
    }
    
    public function getAllLists()
    {
        return $this->select();
    }
    
    public function removeWordList($listId)
    {
        return $this->where("id=".$listId)->delete();
    }
    
    public function removeWordListByUser($userId)
    {
        return $this->where("userId=".$userId)->delete();
    }

    //new Empty WordList
    public function addWordList($listData)
    {
		$listData["progress"] = 0;
        return $this->add($listData);
    }
    
    public function updateWordList($listInfo)
    {
        return $this->save($listInfo);
    }
}
?>
