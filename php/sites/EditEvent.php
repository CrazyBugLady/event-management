<?php
	require_once("php/FormularCreator/formulargenerator.class.php");
	require_once("php/EventManager.php");

	require_once("php/inc/loginChecker.php");
	
	// Eintrag erfragen
	$Event = "";
	
	if(array_key_exists("id", $_REQUEST))
	{
		$Event = \EventManager\Models\EventDbModel::read($_REQUEST["id"]);
	}
	
	// kreieren des Formulars zur Bearbeitung eines Eintrags
	$EventForm = EventManager\EventManager::getEventForm("Edit Event", "veranstaltung", array("ID", "idGenre", "bearbeitungsdatum", "erstelldatum", "bild", "bildbeschreibung"), array(), array($Event->Name, $Event->Persons, $Event->Description, $Event->Duration));
	$EventForm->createForm("index.php?site=edit&id=" . $Event->idEvent);
	
	if(array_key_exists("submit", $_REQUEST))
	{
		if($EventForm->validationSuccessful(array($_REQUEST["tbname"], $_REQUEST["tbbesetzung"], $_REQUEST["txtbeschreibung"], $_REQUEST["tbdauer"])))
		{
			$Event->Name = $_REQUEST["tbname"];
			$Event->Persons = $_REQUEST["tbbesetzung"];
			$Event->Description = $_REQUEST["txtbeschreibung"];
			$Event->Duration = $_REQUEST["tbdauer"];
			
			if(\EventManager\EventManager::update($Event))
			{
			?>
				<div class="panel panel-success">
					<div class="panel-heading">
						Update erfolgreich
					</div>
					
					<div class="panel-body">
						Das Event konnte geändert werden. Zurück zur <a href="index.php?site=show">Übersicht</a>?<br>
					<div>
				</div>
			<?php
			}
			else
			{
			?>
				<div class="panel panel-danger">
					<div class="panel-heading">
						Fehler
					</div>
					
					<div class="panel-body">
						Ein Fehler ist aufgetreten. Kontaktiere den Administrator.	
					</div>
				</div>
			<?php
			}
	}
	else
	{
		?>
			<div class="panel panel-danger">
				<div class="panel-heading">
					Fehler
				</div>
				
				<div>
					<?php 
						echo $EventForm->showValidationResult(array($_REQUEST["tbname"], $_REQUEST["tbbesetzung"], $_REQUEST["txtbeschreibung"], $_REQUEST["tbdauer"]));
					?>
				</div>
			</div>
		<?php
	}
}
	
?>