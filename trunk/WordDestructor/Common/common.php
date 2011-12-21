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
}

?>
