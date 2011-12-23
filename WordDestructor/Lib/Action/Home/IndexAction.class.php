<?php
class IndexAction extends Action
{
    public function index()
    {
		try {
			ProxyCollection::getInstance()->process($this, __FUNCTION__);
			$this->assign("content", "Home:Index:index");
			$this->display("Home:Public:base");
		}
		catch (Exception $e)
		{
			$this->redirect("Home-index/index", null, 1, $e->getMessage());
		}
    }

	public function home()
	{
		try {
			ProxyCollection::getInstance()->process($this, __FUNCTION__);
			$userDao = D("User");
			$user = $userDao->getUserByID($_SESSION["uid"]);
			$this->assign("authority", $user["authority"]);
			$this->assign("content", "Home:Index:home");
			$this->display("Home:Public:base");
		}
		catch (Exception $e) {
			//$this->redirect("Home-Index/index", null, 1, $e->getMessage());
		}
	}
}
?>
