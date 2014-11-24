<?php
	require_once("php/FormularCreator/formulargenerator.class.php");
	require_once("php/EventManager.php");

	$User = \EventManager\UserAuthenticator::getLoggedInUser();
	
	$currentPage = 1;
	
	if(array_key_exists("page", $_REQUEST))
	{
		$currentPage = $_REQUEST["page"];
	}
	
	EventManager\EventManager::showEvents($currentPage, $User);
			
?>