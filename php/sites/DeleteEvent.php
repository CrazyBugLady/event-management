<?php
	require_once("php/FormularCreator/formulargenerator.class.php");
	require_once("php/EventManager.php");

	require_once("php/inc/loginChecker.php");
?>
	<h2>Event löschen</h2>
<?php
	if(array_key_exists("id", $_REQUEST))
	{
		if(\EventManager\EventManager::delete($_REQUEST["id"]))
		{
			
			?>
				<div class="panel panel-success">
					<div class="panel-heading">
						Löschen erfolgreich
					</div>
				
					<div class="panel-body">
						Das Event wurde erfolgreich gelöscht. Zurück zur <a href='index.php?site=show'>Eventübersicht?</a><br>
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
					Das Event konnte nicht gelöscht werden, melde dich beim Administrator.
				</div>
			</div>
			<?php
		}
	}
?>