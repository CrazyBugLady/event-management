
<?php
	include_once("php/Models/EventDbModel.php");
	include_once("php/EventManager.php");

	$Event = "";

	if(array_key_exists("id", $_REQUEST))
	{
		$Event = \EventManager\Models\EventDbModel::read($_REQUEST["id"]);	
	}

if(array_key_exists("fileupload", $_POST))
{
if ( $_FILES['uploaddatei']['name']  <> "" )
{
    // Datei wurde durch HTML-Formular hochgeladen
    // und kann nun weiterverarbeitet werden
 
    // Kontrolle, ob Dateityp zulässig ist
    $zugelassenedateitypen = array("image/png", "image/jpeg");
 
    if ( ! in_array( $_FILES['uploaddatei']['type'] , $zugelassenedateitypen ))
    {
        echo "<p>Dateitype ist NICHT zugelassen</p>";
    }
    else
    {
        move_uploaded_file (
             $_FILES['uploaddatei']['tmp_name'] ,
             'Resources/Images/'. $_FILES['uploaddatei']['name'] );
		
		
		// get image to resize with gd library
		$image = "";
		
		if($_FILES['uploaddatei']['type'] == "image/png")
			$image = imagecreatefrompng( 'Resources/Images/'. $_FILES['uploaddatei']['name']);
		else if($_FILES['uploaddatei']['type'] == "image/jpeg")
		{
			$image = imagecreatefromjpeg('Resources/Images/'. $_FILES['uploaddatei']['name']);
		}
		
		$width = imagesx( $image );
		$height = imagesy( $image );

		$new_width = 100;
		$new_height = 100;
		// calculates depending on the height and the width
		if($height < $width)
		{
			// calculate thumbnail size
			$new_height = floor( $height * ( 100 / $width ) );
		}
		else
		{
			$new_width = floor( $width * (100 / $height));
		}
		
		// create a new temporary image
		$tmp_img = imagecreatetruecolor( $new_width, $new_height );
	
		// copy and resize old image into new image
		imagecopyresized( $tmp_img, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
	
		// save thumbnail into a file
		imagejpeg( $tmp_img, 'Resources/Images/thumbnails/'.$_FILES['uploaddatei']['name']);

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

<form name="uploadformular" enctype="multipart/form-data" action="index.php?site=edit&id=<?php echo $Event->idEvent; ?>" method="post" >

 <div class="form-group">
    <label for="lbluploaddatei">Datei:</label>
		<input type="file" name="uploaddatei" size='60' maxlength='255'>
		<p class="help-block">Upload of a specific image for an event</p>
  </div>
 <div class="form-group">
	<label for="lblBildbeschreibung">Bildbeschreibung</label>
		<textarea class='form-control' name='tbBildbeschreibung' maxlength='255'></textarea>
 </div>
<input class='btn btn-success' type="Submit" name="fileupload" value="upload image">

</form>

