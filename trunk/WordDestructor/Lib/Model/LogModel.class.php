<?php
class LogModel extends Model {
	public function insertLog($moduleName, $actionName, $userID)
	{
		return $this->add(array("module"=>$moduleName, "action"=>$actionName,"userID"=>$userID));
	}

	public function removeLog($logID)
	{
		return $this->where("id=".$logID)->delete();
	}

	public function listAllLogs()
	{
		return $this->select();
	}

	public function listAllLogByUser($userID)
	{
		return $this->where("userID=".$userID)->select();
	}
}

?>
