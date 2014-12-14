<?php
	require_once("php/EventManager.php");

	$User = \EventManager\UserAuthenticator::getLoggedInUser();
	
	$currentPage = 1;
	$archive = false;
	$Genre = 0;
	
	if(array_key_exists("page", $_REQUEST))
	{
		$currentPage = $_REQUEST["page"];
	}
	
	if(array_key_exists("selectedgenre", $_REQUEST))
	{
		$Genre = $_REQUEST["selectedgenre"];
	}
	
	EventManager\EventManager::showEvents($currentPage, $User, false, $Genre);
			
?>