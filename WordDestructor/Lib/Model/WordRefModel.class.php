<?php
class WordrefModel extends Model
{
    //生成新用户$userId的所有wordref
    public function addWordrefsByUser($userId)
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
    
    //对新词库libId，对所有用户生成相对应的所有wordref
    public function addWordrefsByLib($libId)
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
    
    public function getWordrefsForStudy($userId, $libId)
    {
        $condition["userId"] = $userId;
        $condition["libId"] = $libId;
        $condition["listId"] = 0;
        return $this->where($condition)->select();
    }
    
    public function getWordrefsByUser($userId)
    {
        return $this->where("userId=".$userId)->select();
    }
    
    public function removeWordrefsByUser($userId)
    {
        return $this->where("userId=".$userId)->delete();
    }
    
    public function getWordrefsByLib($libId)
    {
        return $this->where("libId=".$libId)->select();
    }
    
    public function removeWordrefsByLib($libId)
    {
        return $this->where("libId=".$libId)->delete();
    }
    
    public function getWordrefsByList($listId)
    {
        return $this->where("listId=".$listId)->select();
    }
    
    public function attachWordrefToList($userId, $wordId, $listId)
    {
        $condition["userId"] = $userId;
        $condition["wordId"] = $wordId;
        $info["listId"] = $listId;
        return $this->where($condition)->save($info);
    }
    
    public function deattachWordrefById($id)
    {
        $info["listId"] = 0;
        return $this->where("id=".$id)->save($info);
    }
    
    public function deattachWordrefsByList($listId)
    {
        $info["listId"] = 0;
        return $this->where("listId=".$listId)->save($info);
    }
    
    public function getWordrefsByState($state)
    {
        return $this->where("state=".$state)->select();
    }
    
    public function setStateByList($state, $listId)
    {
        $info["state"] = $state;
        return $this->where("listId=".$listId)->save($info);
    }
    
    public function getWordrefById($id)
    {
        return $this->where("id=".$id)->find();
    }
    
    public function getWordrefsByWord($wordId)
    {
        return $this->where("wordId=".$wordId)->select();
    }
}
?>
