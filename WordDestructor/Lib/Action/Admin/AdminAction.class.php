<?php
class AdminAction extends Action
{
	public function freezeUser($userID)
	{
		$userDao = D("User");
		if ($userID != '') {
			echo "Invalid UserID.";
		}
		else {
			$data['id'] = $userID;
			$data['stat'] = false;
			$userDao->updateUser($data); //TODO whether find....
			if ($userDao->getError()) 
				echo $userDao->getError();
		}
	}

	public function unfreezeUser($userID)
	{
		$userDao = D("User");
		$data['id'] = $userID;
		$data['stat'] = true;
		//$userDao->where('id='.$userID)->save($data);
		$userDao->updateUser($data);
	}

	public function deleteUser($userID)
	{
		$userDao = D("User");
		$userDao->removeUserByID($userID) //TODO connected with relational model
	}

	public function getUsersByCondition($condition = null)
	{
		$userDao = D("User");
		//$userList = $userDao->where($condition)->select(); //TODO condition will be detail at last
		return $userList;
	}
}

?>
