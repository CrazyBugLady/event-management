<?php
	require_once("php/EventManager.php");

	$User = \EventManager\UserAuthenticator::getLoggedInUser();
	
	$currentPage = 1;
	$archive = false;
	$Genre = 0;
	$option = "";
	$idEvent = 0;
	
	if(array_key_exists("page", $_REQUEST))
	{
		$currentPage = $_REQUEST["page"];
	}
	
	if(array_key_exists("option", $_REQUEST))
	{
		$option = $_REQUEST["option"];
	}
	
	if(array_key_exists("id", $_REQUEST))
	{
		$idEvent = $_REQUEST["id"];
	}
	
	if(array_key_exists("selectedgenre", $_REQUEST))
	{
		$Genre = $_REQUEST["selectedgenre"];
	}
	
	if($option == "editpricegroups")
	{
		EventManager\EventManager::updatePricegroupsFromEvent($_REQUEST["pricegroups"], $idEvent);
	}
	
	EventManager\EventManager::showEvents($currentPage, $User, false, $Genre);
			
?>