<?php
class ProxyFactory {
	var $permission;

	public function __construct(&$Permission)
	{
		$this->permission = $Permission;
	}

	public function process(&$obj, $actionName)
	{
		$logDao = D("Log");
		$this->permission = new Permission($_SESSION["uid"]);
		$class = get_class($obj);
		$sclass = substr($class, 0, strlen($class)-6);
		$logDao->insertLog($sclass, $actionName, $this->permission->userID);
		try {
			$this->permission->validate($class, $actionName);
		}
		catch (unloginException $e)
		{
			throw $e;
		}
		catch (unauthorizedException $e)
		{
			throw $e;
		}
	}

	public static function startProxy()
	{
		$proxy = new ProxyFactory($perm);
		$_SESSION["proxy"]=$proxy;
	}

	public static function getInstance()
	{
		return $_SESSION["proxy"];
	}
}

?>
