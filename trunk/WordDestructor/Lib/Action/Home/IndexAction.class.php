<?php
class IndexAction extends Action
{
    public function index()
    {
		$this->assign("content", "Home:Index:index");
		$this->display("Home:Public:base");
    }

    /**
    +----------------------------------------------------------
    * 探针模式
    +----------------------------------------------------------
    */
    public function checkEnv()
    {
        load('pointer',THINK_PATH.'/Tpl/Autoindex');//载入探针函数
        $env_table = check_env();//根据当前函数获取当前环境
        echo $env_table;
    }

}
?>
