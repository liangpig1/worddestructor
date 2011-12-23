<?php
class Permission {
	public $authority;
	public $state;
	public $map;
	public $userID;

	public function __construct($userID)
	{
		$this->initAuthority();
		$this->updateAuthority($userID);
	}

	public function updateAuthority($userID)
	{
		$this->userID = $userID;
		if ($this->userID==null) $this->userID = 0;
		if ($userID == null || $userID == "" || $userID == 0)
			$this->authority = 0;
		else {
			$user = D("User")->getUserByID($userID);
			$this->state = $user["state"];
			if ($user["authority"] == 0) {
				$this->authority = 1;
			}
			else if ($user["authority"] == 1) {
				$this->authority = 2;
			}
		}
	}
	public function initAuthority()
	{
		$this->map = array(
			"IndexAction.index"					=> 	0,
			"IndexAction.home"					=>	1,
			"UserAction.register"				=>	0,
			"UserAction.login"					=>	0,
			"UserAction.deleteUser"				=>	2,
			"UserAction.changePwd"				=>	1,
			"UserAction.showChangePwd"			=>	1,
			"UserAction.unlogin"				=>	1,
			"WordlibraryAction.show"			=>	1,
			"WordlibraryAction.removeLibrary"	=>	1,
			"WordlibraryAction.addLibrary"		=>	1,
			"WordlibraryAction.listAllLibrary"	=>	1,
			"WordlistAction.addListByLibrary"	=>	1,
			"WordlistAction.addList"			=>	1,
			"WordlistAction.addWordToList"		=>	1,
			"WordlistAction.removeList"			=>	1,
			"WordlistAction.viewList"			=>	1,
			"WordlistAction.deattachWordref"	=>	1,
			"WordlistAction.learnedWordref"		=>	1,
			"WordlistAction.giveupWordref"		=>	1,
			"WordlistAction.listWordListsByUser"=>	1,
			"WordlistAction.studyList"			=>	1,
			"WordlistAction.studyc2e"			=>	1,
			"WordlistAction.correctc2e"			=>	1,
			"WordlistAction.checkc2e"			=>	1,
			"WordlistAction.studye2c"			=>	1,
			"WordlistAction.correcte2c"			=>	1,
			"WordlistAction.checke2c"			=>	1,
			"AdminAction.freezeUser"			=>	2,
			"AdminAction.unfreezeUser"			=>	2,	
			"AdminAction.removeUser"			=>	2,	
			"AdminAction.listAllUser"			=>	2,	
			"AdminAction.listLog"				=>	2,
			"DictionaryAction.lookUpWord"		=>	0,
			"DictionaryAction.show"				=>	0,
		);
	}

	public function validate($className, $methodName)
	{
		if ($this->state == 1) throw new frozenException("用户被冻结");
		$classMethod = "$className.$methodName";
		if ($this->authority < $this->map[$classMethod])
		{
			if ($this->authority == 0) throw new unloginException("未登录");
			else  throw new unauthorizedException("没有足够权限");
		}
		else if ($this->authority > $this->map[$classMethod]){
			if ($methodName == "register" || $methodName == "login")
				throw new alreadyloginException("已登录");
		}
	}
}
?>
