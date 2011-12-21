<?php
class WordRefModel extends Model
{
    //生成新用户$userId的所有wordRef
    public function addWordRefsByUser($userId)
    {
        $wordDao = D("Word");
        $list = $wordDao->select();
        foreach ($list as $word)
        {
            $data["wordId"] = $word["id"];
            $data["state"] = 0; //state == 0表示未学习
            $data["listId"] = 0; //listId == 0表示未在任何词单内
            $data["libId"] = $word["libId"];
            $data["userId"] = $userId;
            $id = $this->add($data);
        }
        return $id;
    }
    
    //对新词库libId，对所有用户生成相对应的所有wordRef
    public function addWordRefsByLib($libId)
    {
        $wordDao = D("Word");
        $wordlist = $wordDao->getWordsByLibId($libId);
        $userDao = D("User");
        $userlist = $userDao->getAllUsers();
        foreach ($wordlist as $word)
        {
            $data["state"] = 0;
            $data["listId"] = 0;
            $data["libId"] = $libId;
            foreach ($userlist as $user)
            {
                $data["wordId"] = $word["id"];
                $data["userId"] = $user["id"];
                $id = $this->add($data);
            }
        }
        return $id;
    }
    
    public function getWordRefsByUser($userId)
    {
        return $this->where("userId=".$userId)->select();
    }
    
    public function removeWordRefsByUser($userId)
    {
        return $this->where("userId=".$userId)->delete();
    }
    
    public function getWordRefsByLib($libId)
    {
        return $this->where("libId=".$libId)->select();
    }
    
    public function removeWordRefsByLib($libId)
    {
        return $this->where("libId=".$libId)->delete();
    }
    
    public function getWordRefsByList($listId)
    {
        return $this->where("listId=".$listId)->select();
    }
    
    public function attachWordRefToList($userId, $wordId, $listId)
    {
        $condition["userId"] = $userId;
        $condition["wordId"] = $wordId;
        $info["listId"] = $listId;
        return $this->where($condition)->save($info);
    }
    
    public function deattachWordRefsByList($listId)
    {
        $info["listId"] = 0;
        return $this->where("listId=".$listId)->save($info);
    }
    
    public function getWordRefsByState($state)
    {
        return $this->where("state=".$state)->select();
    }
    
    public function setStateByList($state, $listId)
    {
        $info["state"] = $state;
        return $this->where("listId=".$listId)->save($info);
    }
    
    public function getWordRefById($id)
    {
        return $this->where("id=".$id)->find();
    }
    
    public function getWordRefsByWord($wordId)
    {
        return $this->where("wordId=".$wordId)->select();
    }
}
?>
