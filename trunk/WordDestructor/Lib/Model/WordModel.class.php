<?php
class WordModel extends Model
{
    public function addWord($eng, $chn, $libID)
    {
        $data["eng"] = $eng;
        $data["chn"] = $chn;
        $data["libID"] = $libID;
        return $this->add($data);
    }
    
    public function removeWordsByLibId($libId)
    {
        return $this->where("libId=".$libId)->delete();
    }
    
    public function removeWordById($wordId)
    {
        return $this->where("id=".$wordId)->delete();
    }
    
    public function getWordsByLibId($libId)
    {
        return $this->where("libId=".$libid)->select();
    }
    
    public function getWordById($wordId)
    {
        return $this->where("id=".$wordId)->find();
    }
}
?>
