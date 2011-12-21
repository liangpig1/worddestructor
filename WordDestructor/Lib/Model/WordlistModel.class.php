<?php
class WordlistModel extends Model
{
    public function getListById($listId)
    {
        return $this->where("id=".$listId)->find();
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
    
    //新建一个空词单
    public function addWordList($listInfo)
    {
        return $this->add($listInfo);
    }
    
    public function updateWordList($listInfo)
    {
        return $this->save($listInfo);
    }
}
?>
