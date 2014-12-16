<?php
	require_once("php/UserAuthenticator.php");
	
	if(\EventManager\UserAuthenticator::logOut())
	{
		header('Location: http://localhost/Git/event-management/index.php?site=start');
		exit;
	}
?>