<?php
	require_once("php/EventManager.php");

	$User = \EventManager\UserAuthenticator::getLoggedInUser();
	$id = 1;
	
	if(array_key_exists("id", $_REQUEST))
	{
		$id = $_REQUEST["id"];
	}
	
	\EventManager\EventManager::showCalendar($id);
			
?>