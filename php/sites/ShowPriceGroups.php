<?php
	require_once("php/PricegroupsManager.php");

	require_once("php/inc/loginChecker.php");
	
	$id = 0;
	$option = "";
	
	$PricegroupForm = \EventManager\PricegroupsManager::getPricegroupsForm("create pricegroup", "preisgruppe", array("ID"), array(), array());
	
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
			if($PricegroupForm->validationSuccessful(array($_REQUEST["tbname"], $_REQUEST["tbpreis"])))
			{
				if(\EventManager\PricegroupsManager::create($_REQUEST["tbname"], $_REQUEST["tbpreis"])) {
					$PricegroupForm->showMessage("success", "Preisgruppe erstellen erfolgreich", "Du konntest die Preisgruppe erfolgreich erstellen.<br>");
				}
				else
				{
					$PricegroupForm->showMessage("danger", "Preisgruppe erstellen nicht erfolgreich", "Die Preisgruppe konnte nicht erfolgreich erstellt werden.");
				}
			}
			else
			{
				$PricegroupForm->showMessage("danger", "Fehler", $PricegroupForm->showValidationResult($_REQUEST["tbname"], $_REQUEST["tbpreis"]));
			}
		}
	}	
	else if($option == "delete")
	{
		if(\EventManager\PricegroupsManager::delete($_REQUEST["id"]))
		{
			$PricegroupForm->showMessage("success", "Preisgruppe löschen erfolgreich", "Die Preisgruppe konnte erfolgreich gelöscht werden. <br>");
		}
		else
		{
			$PricegroupForm->showMessage("danger", "Preisgruppe löschen nicht erfolgreich", "Die Preisgruppe konnte leider nicht gelöscht werden.<br>");
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
					$PricegroupForm->showMessage("success", "Preisgruppe bearbeiten erfolgreich", "Die Preisgruppe konnte bearbeitet werden.<br>");
				}
				else
				{
					$PricegroupForm->showMessage("danger", "Preisgruppe bearbeiten nicht erfolgreich", "Die Preisgruppe konnte leider nicht bearbeitet werden.<br>");
				}
			}
	}	
	
	\EventManager\PricegroupsManager::showPricegroupsSite($User != "", $option, $id);	
	$PricegroupForm->createForm("index.php?site=pricegroups&option=create");
	
?>