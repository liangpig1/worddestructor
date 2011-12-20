<?php
class UserModel extends Model
{
    public function getUserByID($id)
    {
        return $this->where("id=".$id)->find();
    }
    
    public function getUserByName($name)
    {
        return $this->where('username="'.$name.'"')->find();
    }
    
    public function getAllUsers()
    {
        return $this->select();
    }
    
    public function removeUserByID($id)
    {
        $this->where("id=".$id)->delete();
    }
    
    public function insertUser($info)
    {
        $this->add($info);
    }
    
    public function updateUser($info)
    {
        $this->save($info);
    }
}
?>
