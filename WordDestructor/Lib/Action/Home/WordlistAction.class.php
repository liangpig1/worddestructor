<?php
class WordlistAction extends Action
{
	public function addlist()
	{
	}

	public function removelist($listID)
	{
		$listDao = D("Wordlist");
		$userDao = D("User");
		$listdata = $listDao->where("id=".$listID)->select();
	}

	public function selectList($userID, $listID)
	{
		$userDao = D("User");
		$userdata = $userDao->where("id=".$userID)->select();
		if ($userInfo) {
			$userdata["currentlistid"] = $listID;
			$userDao->save($userdata);
		}
		else {
			echo "User not Found";
		}
	}
}
?>
