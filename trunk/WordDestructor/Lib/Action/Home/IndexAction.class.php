<?php
class IndexAction extends Action
{
    public function index()
    {
		if (isset($_POST['username']) && isset($_POST['pwd']))
		{
			$logInfo = array("username"=>$_POST["username"], "pwd"=>$_POST["pwd"]);
			A("User")->login($logInfo);
		}
		else {
			if (A("User")->islogin()) {
				$this->assign("content", "Home:Index:home");
			}
			else $this->assign("content", "Home:Index:index");
			$this->display("Home:Public:base");
		}
    }
}
?>
