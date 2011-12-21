<?php
class WordlistModel extends Model
{
    public function getListsByUser($userId)
    {
        return $this->where("userId=".$userId)->select();
    }
    
    public function getAllLists()
    {
        return $this->select();
    }
    
    public function deleteWordList($listId)
    {
        $this->where("id=".$listId)->delete();
    }
    
    //新建一个空词单
    public function addWordList($listInfo)
    {
        $this->add($listInfo);
    }
    
    public function updateWordList($listInfo)
    {
        $this->save($listInfo);
    }
}

?>
