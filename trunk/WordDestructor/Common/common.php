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

?>
