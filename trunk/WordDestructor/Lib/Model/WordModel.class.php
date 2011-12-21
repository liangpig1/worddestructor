<?php
class WordModel extends Model
{
    public function getWordByList($id)
    {
        return $this->where("listId=".$id)->select();
    }
    
    public function getWordByLibrary($id)
    {
        return $this->where("libId=".$id)->select();
    }
    
    public function removeWord($id)
    {
        $this->where("id=".$id)->delete();
    }
    
    public function insertWord($info)
    {
        $this->add($info);
    }
}
?>
