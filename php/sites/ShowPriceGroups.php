<?php
	require_once("php/PricegroupsManager.php");

	require_once("php/inc/loginChecker.php");
	
	$id = 0;
	$option = "";
	
	if(array_key_exists("option", $_REQUEST))
	{
		$option = $_REQUEST["option"];
	}
	
	if(array_key_exists("id", $_REQUEST))
	{
		$id = $_REQUEST["id"];
	}
	
	if($option == "create")
	{
		if(array_key_exists("submit", $_POST))
		{
			if(\EventManager\PricegroupsManager::create($_REQUEST["tbname"], $_REQUEST["tbpreis"]))
			{
				?>
					<div class="panel panel-success">
						<div class="panel-heading">
							Preisgruppe erstellen erfolgreich
						</div>
			
						<div class="panel-body">
							Du konntest die Preisgruppe erfolgreich erstellen. Zurück zur <a href='index.php?site=pricegroups'>Übersicht</a><br>
						</div>
					</div>
				<?php
			}
			else
			{
				?>
					<div class="panel panel-danger">
						<div class="panel-heading">
							Preisgruppe erstellen nicht erfolgreich
						</div>
			
						<div class="panel-body">
							Die Preisgruppe konnte nicht erstellt werden.<br>
						</div>
					</div>
				<?php

			}
		}
	}	
	else if($option == "delete")
	{
		if(\EventManager\PricegroupsManager::delete($_REQUEST["id"]))
		{
				?>
					<div class="panel panel-success">
						<div class="panel-heading">
							Preisgruppe löschen erfolgreich
						</div>
			
						<div class="panel-body">
							Die Preisgruppe konnte erfolgreich gelöscht werden. Zurück zur <a href='index.php?site=pricegroups'>Übersicht</a><br>
						</div>
					</div>
				<?php
		}
		else
		{
			?>
					<div class="panel panel-danger">
						<div class="panel-heading">
							Preisgruppe löschen nicht erfolgreich
						</div>
			
						<div class="panel-body">
							Die Preisgruppe konnte leider nicht gelöscht werden. Zurück zur <a href='index.php?site=pricegroups'>Übersicht</a><br>
						</div>
					</div>
				<?php
		}
	}
	else if($option == "editsave")
	{
			if(array_key_exists("submit", $_REQUEST))
			{
				$Name = $_REQUEST["tbname"];
				$Preis = $_REQUEST["tbpreis"];
			
				if(\EventManager\PricegroupsManager::update($id, $Preis, $Name))
				{
					?>
						<div class="panel panel-success">
							<div class="panel-heading">
									Preisgruppe bearbeiten erfolgreich
								</div>
					
								<div class="panel-body">
									Die Preisgruppe konnte bearbeitet werden. Zurück zur <a href='index.php?site=pricegroups'>Übersicht</a><br>
								</div>
							</div>
						<?php
				}
				else
				{
					?>
						<div class="panel panel-danger">
							<div class="panel-heading">
								Preisgruppe bearbeiten nicht erfolgreich
							</div>
				
							<div class="panel-body">
								Die Preisgruppe konnte leider nicht bearbeitet werden. Zurück zur <a href='index.php?site=pricegroups'>Übersicht</a><br>
							</div>
						</div>
					<?php
				}
			}
		}
		else
		{	
			\EventManager\PricegroupsManager::showPricegroupsSite($User != "", $option, $id);		
		
			$PricegroupForm = \EventManager\PricegroupsManager::getPricegroupsForm("create pricegroup", "preisgruppe", array("ID"), array(), array());
			$PricegroupForm->createForm("index.php?site=pricegroups&option=create");
		}
	
?>