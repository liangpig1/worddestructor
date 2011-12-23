<?php
class ProxyFactory {
	var $permission;

	public function __construct(&$Permission)
	{
		$this->permission = $Permission;
	}

	public function process(&$obj, $actionName)
	{
		try {
			$this->permission = new Permission($_SESSION["uid"]);
			$this->permission->validate(get_class($obj), $actionName);
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
