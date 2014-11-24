<?php
	require_once("php/GenresManager.php");

	require_once("php/inc/loginChecker.php");
	
	$id = 0;
	$option = "";
	
	if(array_key_exists("option", $_REQUEST))
	{
		$option = $_REQUEST["option"];
	}
	
	if(array_key_exists(("id"), $_REQUEST))
	{
		$id = $_REQUEST["id"];
	}
	
	\EventManager\GenresManager::showGenreSite($User != "", $option, $id);
			
?>