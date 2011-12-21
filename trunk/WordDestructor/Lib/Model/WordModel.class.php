<?php
class WordModel extends Model
{
    public function addWord($eng, $chn, $libId)
    {
        $data["eng"] = $eng;
        $data["chn"] = $chn;
        $data["libId"] = $libId;
        $this->add($data);
    }
    
    public function delWordsByLibId($libId)
    {
        $this->where("libId=".$libId)->delete();
    }
    
    public function delWordById($wordId)
    {
        $this->where("id=".$wordId)->delete();
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
