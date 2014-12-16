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
	
	if(array_key_exists("link-url", $_REQUEST))
	{
		\EventManager\EventManager::updateLinksFromEvent($_REQUEST["link-url"], $_REQUEST["link-name"], $Event->idEvent);
	}
	
?>

<div role="tabpanel">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">General</a></li>
    <li role="presentation"><a href="#pricegroups" aria-controls="pricegroups" role="tab" data-toggle="tab">Pricegroups</a></li>
    <li role="presentation"><a href="#links" aria-controls="links" role="tab" data-toggle="tab">Links</a></li>
	<li role="presentation"><a href="#file" aria-controls="file" role="tab" data-toggle="tab">File</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
  
    <div role="tabpanel" class="tab-pane active" id="general">
<?php
			
	// kreieren des Formulars zur Bearbeitung eines Eintrags
	$EventForm = EventManager\EventManager::getEventForm("Edit Event", "veranstaltung", array("ID", "idGenre", "bearbeitungsdatum", "erstelldatum", "bild", "bildbeschreibung"), array(), array($Event->Name, $Event->Persons, $Event->Description, $Event->Duration));
	$EventForm->createForm("index.php?site=edit&id=" . $Event->idEvent);
	
	if(array_key_exists("tbname", $_REQUEST))
	{
		$isUpdating = true;
		if($EventForm->validationSuccessful(array($_REQUEST["tbname"], $_REQUEST["tbbesetzung"], $_REQUEST["txtbeschreibung"], $_REQUEST["tbdauer"])))
		{
			$Event->Name = $_REQUEST["tbname"];
			$Event->Persons = $_REQUEST["tbbesetzung"];
			$Event->Description = $_REQUEST["txtbeschreibung"];
			$Event->Duration = $_REQUEST["tbdauer"];
			
			if(\EventManager\EventManager::update($Event))
			{
				$EventForm->showMessage("success", "Update erfolgreich", "Du konntest das Event erfolgreich updaten.");
			}
			else
			{
				$EventForm->showMessage("danger", "Update nicht erfolgreich", "Das Event konnte nicht erfolgreich geupdatet werden.");
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
	</div>
    
	<div role="tabpanel" class="tab-pane" id="pricegroups">
	<h3>Edit Pricegroups</h3>
		<?php
			if(array_key_exists("pricegroups", $_REQUEST))
			{
				if(\EventManager\EventManager::updatePricegroupsFromEvent($_REQUEST["pricegroups"], $Event->idEvent))
				{
					$EventForm->showMessage("success", "Preisgruppen erfolgreich verwaltet", "Preisgruppen dieses Events konnten erfolgreich verwaltet werden.");
				}
				else
				{
					$EventForm->showMessage("danger", "Preisgruppen nicht erfolgreich verwaltet", "Preisgruppen dieses Events konnten leider nicht erfolgreich verwaltet werden.");
				}
			}
			
			echo \EventManager\EventManager::getPricegroupCheckboxes($Event->getPricegroups(), $Event->idEvent);
		?>
	</div>
    
	<div role="tabpanel" class="tab-pane" id="links">
	<h3>Edit Links</h3>
	
	<?php
	if(array_key_exists("link-url", $_REQUEST))
	{
		if(\EventManager\EventManager::updateLinksFromEvent($_REQUEST["link-url"], $_REQUEST["link-name"], $Event->idEvent))
		{
			$EventForm->showMessage("success", "Links erfolgreich verwaltet", "Links dieses Events konnten erfolgreich verwaltet werden.");
		}
		else
		{
			$EventForm->showMessage("danger", "Links nicht erfolgreich verwaltet", "Links dieses Events konnten leider nicht erfolgreich verwaltet werden.");
		}
	}
	?>
	
	<form action='index.php?site=edit&id=<?php echo $Event->idEvent; ?>' method='post'>
		<table id="links" class='table table-responsive'>
			<thead>
				<th>Name</th>
				<th>Link</th>
				<th>Option</th>
			</thead>
			<tbody>
			
			<?php
				echo \EventManager\EventManager::getLinksTextboxes($Event->getLinks(), $Event->idEvent);
			?>
			
			</tbody>
		</table>
		
		<input type='button' class='btn btn-success' id='addlink' value='add link'>
		<input type='submit' class='btn btn-primary' value='save'>
		</form>
	</div>
	
	<div role="tabpanel" class="tab-pane" id="file">
	<h3>Edit Image</h3>
	<?php
		include_once("editEventImage.php");
	?>
	</div>
  
  </div>

</div>