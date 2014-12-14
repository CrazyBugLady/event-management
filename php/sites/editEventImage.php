
<?php
	include_once("php/Models/EventDbModel.php");
	include_once("php/EventManager.php");

	$Event = "";

	if(array_key_exists("id", $_REQUEST))
	{
		$Event = \EventManager\Models\EventDbModel::read($_REQUEST["id"]);	
	}

if(array_key_exists("submit", $_POST))
{
if ( $_FILES['uploaddatei']['name']  <> "" )
{
    // Datei wurde durch HTML-Formular hochgeladen
    // und kann nun weiterverarbeitet werden
 
    // Kontrolle, ob Dateityp zulässig ist
    $zugelassenedateitypen = array("image/png", "image/jpeg", "image/gif");
 
    if ( ! in_array( $_FILES['uploaddatei']['type'] , $zugelassenedateitypen ))
    {
        echo "<p>Dateitype ist NICHT zugelassen</p>";
    }
    else
    {
        move_uploaded_file (
             $_FILES['uploaddatei']['tmp_name'] ,
             'Resources/Images/'. $_FILES['uploaddatei']['name'] );
		
		$Event->PictureDescription = $_REQUEST["tbBildbeschreibung"];
		$Event->PicturePath = $_FILES["uploaddatei"]["name"];
		
		if(\EventManager\EventManager::update($Event))
		{
			?>
				<div class="panel panel-success">
					<div class="panel-heading">
						Bildupload erfolgreich
					</div>
				
					<div class="panel-body">
						Das Bild des Events wurde erfolgreich verändert. Zurück zur <a href='index.php?site=show'>Eventübersicht?</a><br>
					</div>
				</div>
			<?php
		}
		else
		{
			?>
				<div class="panel panel-danger">
					<div class="panel-heading">
						Bildupload nicht erfolgreich
					</div>
				
					<div class="panel-body">
						Das Bild des Events konnte nicht hochgeladen werden. Zurück zur <a href='index.php?site=show'>Eventübersicht?</a><br>
					</div>
				</div>
			<?php
		}
    }
}
}
?>

<form name="uploadformular" enctype="multipart/form-data" action="index.php?site=image&id=<?php echo $Event->idEvent; ?>" method="post" >

 <div class="form-group">
    <label for="lbluploaddatei">Datei:</label>
		<input type="file" name="uploaddatei" size='60' maxlength='255'>
		<p class="help-block">Upload of a specific image for an event</p>
  </div>
 <div class="form-group">
	<label for="lblBildbeschreibung">Bildbeschreibung</label>
		<textarea class='form-control' name='tbBildbeschreibung' maxlength='255'></textarea>
 </div>
<input class='btn btn-success' type="Submit" name="submit" value="upload image">

</form>

