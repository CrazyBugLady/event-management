<?php
	require_once("php/FormularCreator/formulargenerator.class.php");
	require_once("php/BusinessObjects/Event.php");
	require_once("php/Models/EventDbModel.php");
	require_once("php/EventManager.php");

	require_once("php/inc/loginChecker.php");
	
	$Name = "";
	$Besetzung = "";
	$Beschreibung = "";
	$Dauer = "";
	$idGenre = 0;
	
	if(array_key_exists("submit", $_REQUEST))
	{
		$Name = $_REQUEST["tbname"];
		$Besetzung = $_REQUEST["tbbesetzung"];
		$Beschreibung = $_REQUEST["txtbeschreibung"];
		$Dauer = $_REQUEST["tbdauer"];
	}
	
	// hinzunehmen des im Modal ausgewählten Genres
	if(array_key_exists("selectedgenre", $_REQUEST))
	{
		$idGenre = $_REQUEST["selectedgenre"];
	}
	
	$EventForm = \EventManager\EventManager::getEventForm("create event", "veranstaltung", array("ID", "idGenre", "bearbeitungsdatum", "erstelldatum", "bild", "bildbeschreibung"), array(), array($Name, $Besetzung, $Beschreibung, $Dauer));
	$EventForm->createForm("index.php?site=sign&selectedgenre=" . $idGenre);
	
	if(array_key_exists("submit", $_REQUEST))
	{
		if($EventForm->validationSuccessful(array($Name, $Besetzung, $Beschreibung, $Dauer)) && $idGenre != 0)
		{
			if(\EventManager\EventManager::create($Name, $Besetzung, $Beschreibung, $Dauer, $idGenre))
			{
				$EventForm->showMessage("success", "Event erstellen erfolgreich", "Du konntest das Event erfolgreich erstellen. Zurück zur <a href='index.php?site=show'>Übersicht</a><br>");
			}
			else
			{
				$EventForm->showMessage("danger", "Event erstellen nicht erfolgreich", "Du konntest das Event nicht erstellen. Zurück zur <a href='index.php?site=show'>Übersicht</a><br>");
			}
		}
		else
		{
			if($EventForm->validationSuccessful(array($Name, $Besetzung, $Beschreibung, $Dauer)) == false)
			{
				$EventForm->showMessage("danger", "Fehler", $EventForm->showValidationResult(array($Name, $Besetzung, $Beschreibung, $Dauer)));
			}
			else
			{
				$EventForm->showMessage("danger", "Fehler", "Du Schlingel, dachtest wohl, kannst hier mal lockerflockig nen Event ohne Genre erstellen. Aber hey, is nich.");
			}
		}
	}
	else
	{
		echo "<a href='index.php?site=show'>Zurück zur Eventübersicht</a>";
	}
?>