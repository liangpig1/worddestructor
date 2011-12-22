<?php
function checkuserdata($data)
{
	if (isset($data['username']) && isset($data['pwd']))
		return true;
	else return false;
}

function md5hash()
{
}

function fileParse($buffer)
{
	$lineBufArr = explode("\n", $buffer); //TODO need to be perfected
	$wordList = array();
	foreach ($lineBufArr as $lineBuf)
	{
		$lineBufp = trim($lineBuf);
		if (empty($lineBufp)) continue;
		for ($i = 0; $i < strlen($lineBufp); $i++)
			if ($lineBufp[$i] == " ") break;
		$word_en = substr($lineBufp, 0, $i);
		for (;$i < strlen($lineBufp); $i++)
			if ($lineBufp[$i] != " ") break;
		$word_cn = substr($lineBufp, $i);
		$wordList[] = array("eng"=>$word_en, "chn"=>$word_cn);
	}
	return $wordList;
}

function format_time($time, $mode=0) {
	date_default_timezone_set ( "ASIA/SHANGHAI" ); 
	if ($mode)
	{
		$today = date("Y-m-d", time());
		$date = date("Y-m-d", $time);
		if ($date != $today) return $date; 
			else return date("H:i", $time);
	}
	else
	{
		return date("Y-m-d H:i", $time);
	}
}

?>
