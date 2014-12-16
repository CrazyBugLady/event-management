<?php
	require_once("php\FormularCreator/formulargenerator.class.php");
	require_once("php\UserAuthenticator.php");
	require_once("php\EventManager.php");
	
	$User = \EventManager\UserAuthenticator::getLoggedInUser();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EventManager</title>

	<link href="Resources/css/bootstrap.css" rel="stylesheet">
    <link href="Resources/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="Resources/css/custom.css">
	<link rel='stylesheet' href='Resources/fullcalendar/fullcalendar.css' />
	<link rel="stylesheet" href="Resources/css/jquery.fancybox.css" type="text/css" media="screen" />
	
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="Resources/js/jquery.fancybox.pack.js"></script>
    <script src="Resources/js/bootstrap.min.js"></script>
    <script src="Resources/js/custom.js"></script>
	<script src='Resources/js/moment.js'></script>
	<script src='Resources/fullcalendar/fullcalendar.min.js'></script>
  </head>
  <body>
  
 <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a href="index.php?site=start" class="navbar-brand">EventManager</a>
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
		  <?php
			if(EventManager\UserAuthenticator::isUserAlreadyLoggedIn())
			{
				include("php/inc/menu.logout.inc.php");
			}
			else
			{
				include("php/inc/menu.login.inc.php");
			}
		  ?>
        </div>
      </div>
    </div>
  
		<div class="container">
			<?php
				include_once("php/inc/modals.inc.php");
			?>
			
			<div id="calendar">
				
			</div>
			
			<?php
					   
			if(array_key_exists("site", $_REQUEST))
			{
				switch(strtolower($_REQUEST["site"]))
				{
					case "start":
						include_once("php/sites/Start.php");
						break;
					case "login":
						include_once("php/sites/Login.php");
						break;
					case "show":
						include_once("php/sites/showEvents.php");
						break;
					case "presentation":
						include_once("php/sites/PresentationData.php");
						break;
					case "sign":
						include_once("php/sites/makeEvent.php");
						break;
					case "delete":
						include_once("php/sites/deleteEvent.php");
						break;
					case "genres":
						include_once("php/sites/ShowGenres.php");
						break;
					case "pricegroups":
						include_once("php/sites/ShowPriceGroups.php");
						break;
					case "logout":
						include_once("php/sites/LogOut.php");
						break;
					case "archive":
						include_once("php/sites/showArchive.php");
						break;
					case "edit":
						include_once("php/sites/EditEvent.php");
						break;
				}
			}
			else
			{
				include_once("php/sites/Start.php");
			}
			?>
				</div>
			</div>
		</div>
  </body>
</html>