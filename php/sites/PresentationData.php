<?php
	require_once("php/EventManager.php");

	$User = \EventManager\UserAuthenticator::getLoggedInUser();
	$id = 1;
	
	if(array_key_exists("id", $_REQUEST))
	{
		$id = $_REQUEST["id"];
	}
	
	\EventManager\EventManager::showCalendar($id);
	
	if(array_key_exists("submit", $_REQUEST))
	{
		$Zeit = $_REQUEST["tbzeit"];
		$Datum = $_REQUEST["tbdatum"];
		
		$PresentationDate = new \EventManager\BusinessObjects\PresentationDate(0, $Zeit, "", $id);
		$PresentationDate->setPresentationDate($Datum);
		
		if($PresentationDate->create())
		{
			?>
				<div class="panel panel-success">
					<div class="panel-heading">
						Vorstellung erstellen erfolgreich
					</div>
					<div class="panel-body">
						Du konntest die Vorstellung erfolgreich erstellen. Zurück zur <a href='index.php?site=show'>Übersicht</a><br>
					</div>
				</div>
			<?php
		}
		else
		{
			?>
				<div class="panel panel-danger">
					<div class="panel-heading">
						Vorstellung erstellen nicht erfolgreich
					</div>
					<div class="panel-body">
						Du konntest die Vorstellung nicht erstellen. Zurück zur <a href='index.php?site=show'>Übersicht</a><br>
					</div>
				</div>
			<?php
		}
	}
?>