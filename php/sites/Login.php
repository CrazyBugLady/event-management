<?php
	require_once("php/Models/EventDbModel.php");
	require_once("php/EventManager.php");

	$User = \EventManager\UserAuthenticator::getLoggedInUser();
	
	$LoginForm = \EventManager\UserAuthenticator::getLoginForm(array(), array(), array());
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
						Du konntest dich erfolgreich einloggen. Zur <a href='index.php?site=show'>Eventübersicht?</a><br>
					</div>
				</div>
<?php
		}
		else
		{
			$LoginForm->buildForm();
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
		\EventManager\UserAuthenticator::buildForm();
	}
?>