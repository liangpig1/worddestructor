<?php
class UserModel extends Model
{
    public function getUserByID($id)
    {
        return D("User")->where("UserID=".$id)->find();
    }
    
    public function getUsersByName($name)
    {
        return D("User")->where('UserName="'.$name.'"')->select();
    }
    
    public function getUsers()
    {
        return D("User")->select();
    }
    
    public function removeUser($id)
    {
        D("User")->where("UserID=".$id)->delete();
    }
    
    public function insertUser($info)
    {
        D("User")->add($info);
    }
    
    public function updateUserInfo($info)
    {
        D("User")->save($info);
    }
    
    public function validateUser($name, $pass) 
    {
        $catalog = D("user");
        $condition["UserName"] = '"'.$name.'"';
        $condition["Password"] = '"'.$pass.'"';
        $user = $catalog->where($condition)->find();
        if ($user && $user["isFrozen"] == false)
            return true;
        return false;
    }
}
?>