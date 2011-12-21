<?php
class IndexAction extends Action
{
	public static $errMsg;

    public function index()
    {
		$this->assign("content", "Home:Index:index");
		$this->display("Home:Public:base");
    }

	public function home()
	{
		if (A("User")->islogin()) {
			$userDao = D("User");
			$user = $userDao->getUserByID($_SESSION["uid"]);
			$this->assign("authority", $user["authority"]);
			$this->assign("content", "Home:Index:home");
			$this->display("Home:Public:base");
		}
		else {
			$this->errMsg = "未登录，跳转至首页";
			$this->redirect("Home-Index/index", 1, null, $this->errMsg);
		}
	}
}
?>
