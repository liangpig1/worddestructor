<?php
class AdminAction extends Action
{
	public function freezeUser($userID)
	{
		$userDao = D("User");
		$data['id'] = $userID;
		$data['stat'] = false;
		$userDao->where('id='.$userID)->save($data);
	}

	public function unfreezeUser($userID)
	{
		$userDao = D("User");
		$data['id'] = $userID;
		$data['stat'] = true;
		$userDao->where('id='.$userID)->save($data);
	}

	public function deleteUser($userID)
	{
		$userDao = D("User");
		$userDao->where('id='.$userDao)->delete(); //TODO connected with relational model
	}

	public function getUsersByCondition($condition = null)
	{
		$userDao = D("User");
		$userList = $userDao->where($condition)->select(); //TODO condition will be detail at last
		return $userList;
	}
}

?>
