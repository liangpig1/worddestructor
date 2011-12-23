<?php
class IndexAction extends Action
{
    public function index()
    {
		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
			$this->assign("content", "Home:Index:index");
			$this->display("Home:Public:base");
		}
		catch (Exception $e)
		{
		}
    }

	public function home()
	{
		try {
			ProxyFactory::getInstance()->process($this, __FUNCTION__);
			$userDao = D("User");
			$user = $userDao->getUserByID($_SESSION["uid"]);
			$this->assign("username", $user["username"]);
			$this->assign("authority", $user["authority"]);
			$this->assign("content", "Home:Index:home");
			$this->display("Home:Public:base");
		}
		catch (Exception $e) {
			$this->redirect("Home-Index/index", null, 1, "用户未登录");
		}
	}
}
?>
