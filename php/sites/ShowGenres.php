<?php
	require_once("php/GenresManager.php");

	require_once("php/inc/loginChecker.php");
	
	$id = 0;
	$option = "";
	
	$GenreForm = \EventManager\GenresManager::getGenreForm("create genre", "genre", array("ID"), array(), array());
	
	if(array_key_exists("option", $_REQUEST))
	{
		$option = $_REQUEST["option"];
	}
	
	if(array_key_exists(("id"), $_REQUEST))
	{
		$id = $_REQUEST["id"];
	}
	
	if($option == "create")
	{
		if(array_key_exists("submit", $_POST))
		{
			if(\EventManager\GenresManager::create($_REQUEST["tbname"]))
			{
				$GenreForm->showMessage("success", "Genre erstellen erfolgreich", "Du konntest das Genre erfolgreich erstellen. Zurück zur <a href='index.php?site=genres'>Übersicht</a><br>");
			}
			else
			{
				$GenreForm->showMessage("danger", "Genre erstellen nicht erfolgreich", "Das Genre konnte nicht erstellt werden.<br>");
			}
		}
	}	
	else if($option == "delete")
	{
		if(\EventManager\GenresManager::delete($_REQUEST["id"]))
		{
			$GenreForm->showMessage("success", "Genre löschen erfolgreich", "Das Genre konnte erfolgreich gelöscht werden.<br>");
		}
		else
		{
			$GenreForm->showMessage("danger", "Genre löschen nicht erfolgreich", "Das Genre konnte nicht gelöscht werden. Vermutlich ist es bereits einer Veranstaltung zugeordnet.<br>");
		}
	}
	
	
		\EventManager\GenresManager::showGenreSite($User != "", $option, $id);

		$GenreForm = \EventManager\GenresManager::getGenreForm("create genre", "genre", array("ID"), array(), array());
		$GenreForm->createForm("index.php?site=genres&option=create");
?>