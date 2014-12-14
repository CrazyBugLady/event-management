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
	$idGenre = 1;
	
	if(array_key_exists("submit", $_REQUEST))
	{
		$Name = $_REQUEST["tbname"];
		$Besetzung = $_REQUEST["tbbesetzung"];
		$Beschreibung = $_REQUEST["txtbeschreibung"];
		$Dauer = $_REQUEST["tbdauer"];
	}
	
	if(array_key_exists("idGenre", $_REQUEST))
	{
		$idGenre = $_REQUEST["idGenre"];
	}
	
	$EventForm = \EventManager\EventManager::getEventForm("Veranstaltung erstellen", "veranstaltung", array("ID", "idGenre", "bearbeitungsdatum", "erstelldatum", "bild", "bildbeschreibung"), array(), array($Name, $Besetzung, $Beschreibung, $Dauer));
	$EventForm->createForm("index.php?site=sign&idGenre=" . $idGenre);
	
	echo "<a href='index.php?site=show'>Zurück zur Eventübersicht</a>";
	
	if(array_key_exists("submit", $_REQUEST))
	{
		if($EventForm->validationSuccessful(array($Name, $Besetzung, $Beschreibung, $Dauer)))
		{
			if(\EventManager\EventManager::create($Name, $Besetzung, $Beschreibung, $Dauer, $idGenre))
			{
				?>
					<div class="panel panel-success">
						<div class="panel-heading">
							Event erstellen erfolgreich
						</div>
			
						<div class="panel-body">
							Du konntest das Event erfolgreich erstellen. Zurück zur <a href='index.php?site=show'>Übersicht</a><br>
						</div>
					</div>
				<?php
			}
			else
			{
				?>
					<div class="panel panel-danger">
						<div class="panel-heading">
							Event erstellen nicht erfolgreich
						</div>
			
						<div class="panel-body">
							Du konntest das Event nicht erstellen. Zurück zur <a href='index.php?site=show'>Übersicht</a><br>
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
							$EntryForm->showValidationResult(array($Name, $Besetzung, $Beschreibung, $Dauer));
						?>
					</div>
				</div>
			
			<?php
		}
	}
?>