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
	
	if($option == "create")
	{
		if(array_key_exists("submit", $_POST))
		{
			if(\EventManager\GenresManager::create($_REQUEST["tbname"]))
			{
				?>
					<div class="panel panel-success">
						<div class="panel-heading">
							Genre erstellen erfolgreich
						</div>
			
						<div class="panel-body">
							Du konntest das Genre erfolgreich erstellen. Zurück zur <a href='index.php?site=genres'>Übersicht</a><br>
						</div>
					</div>
				<?php
			}
			else
			{
				?>
					<div class="panel panel-danger">
						<div class="panel-heading">
							Genre erstellen nicht erfolgreich
						</div>
			
						<div class="panel-body">
							Das Genre konnte nicht erstellt werden.<br>
						</div>
					</div>
				<?php

			}
		}
	}	
    else
	{	
		\EventManager\GenresManager::showGenreSite($User != "", $option, $id);
		
		$GenreForm = \EventManager\GenresManager::getGenreForm("create genre", "genre", array("ID"), array(), array());
		$GenreForm->createForm("index.php?site=genres&option=create");
	}
?>