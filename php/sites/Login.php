<?php
	require_once("php/FormularCreator/formulargenerator.class.php");
	require_once("php/Models/EventDbModel.php");
	require_once("php/EventManager.php");

	$User = \EventManager\UserAuthenticator::getLoggedInUser();
	
	$LoginForm = EventManager\UserAuthenticator::getLogin(array("ID", "erstelldatum"), array(""), array());
?>
	<h1>Join da' partey</h1>
<?php
	if(array_key_exists("submit", $_REQUEST))
	{
		if(EventManager\UserAuthenticator::checkLogin($_REQUEST["tbbenutzername"], $_REQUEST["pwpasswort"]))
		{
?>
				<div class="panel panel-success">
					<div class="panel-heading">
						Login erfolgreich
					</div>
		
					<div class="panel-body">
						Du konntest dich erfolgreich einloggen. Zum <a href='index.php?site=show'>Gästebuch?</a><br>
					</div>
				</div>
<?php
		}
		else
		{
			$LoginForm->createForm("index.php?site=login");
			//echo "<a href='index.php?site=register'>Do you already have an account?</a><br>";
			?>
				<div class="panel panel-danger">
					<div class="panel-heading">
						Login nicht erfolgreich
					</div>
		
					<div class="panel-body">
						Du konntest nicht eingeloggt werden. Eventuell hast du dich vertippt?<br>
					</div>
				</div>
			<?php
		}
	}
	else
	{
		$LoginForm->createForm("index.php?site=login");
		//echo "<a href='index.php?site=register'>Do you already have an account?</a><br>";
	}
?>