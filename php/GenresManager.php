<?php
	namespace EventManager;

	require_once("Models/GenreDbModel.php");
	require_once("Models/EventDbModel.php");
	
	class GenresManager
	{	
		private static $Title = "Genres";
		
		
		public static function countAllEvents()
		{
			$Genres = \EventManager\Models\GenreDbModel::readAll();
			return sizeof($Genres);
		}
		
		public static function showGenreSite($UserAvailable, $option, $idGenre)
		{
			echo "<h2>" . self::$Title . "</h2>";
			
			self::showGenres($UserAvailable, $option, $idGenre);
		}
		
		public static function showGenres($UserAvailable, $option, $idGenre)
		{
			$Genres = \EventManager\Models\GenreDbModel::readAll();
			
			?>
			
			<table role="table" class="table table-striped">
				<tr>
					<th>Name</th>
					<th>Options</th>
				</tr>
			<?php
			
			foreach($Genres as $Genre)
			{
				echo "<tr>" . PHP_EOL;
					echo "<td>" . self::showEditForm($option, $idGenre, $Genre->getName(), $Genre->getId()) . "</td>" . PHP_EOL;
					if($UserAvailable)
					{
						echo "<td>" . self::setOptions($Genre->getId()) . "</td>" . PHP_EOL;
					}
					else
					{
						echo "<td>No options available</td>";
					}
				echo "</tr>" . PHP_EOL;
			}
			
			?>
			</table>
			<?php
		}

		public static function showEditForm($Option, $idEdit, $NameCurrent, $idCurrent)
		{
			if($idEdit != $idCurrent)
			{
				return $NameCurrent;
			}
		
			switch($Option)
			{
				case "edit":
					$Form = "";
					if($idEdit == $idCurrent)
					{
						$EditForm = "<form method='post' action='index.php?site=genres&option=editsave&id=" . $idEdit . "'>" .
									"<input type='text' name='txtName' value='". $NameCurrent ."'> <input type='submit' class='btn btn-success' value='Ändern'>" .
								"</form>";
								$Form = $EditForm;
					}
					else
					{
						$Form = $NameCurrent;
					}
					
					return $Form;
				case "editsave":
					$Genre = new \EventManager\BusinessObjects\Genre($idEdit, $_REQUEST["txtName"]);
						
					$ResultPanel = "";
					
					if($Genre->update())
					{
						$ResultPanel = 	$_POST["txtName"] .
										"<div class='panel panel-success'>Erfolgreich geändert.</div>"; 
					}
					else
					{
						$ResultPanel = 	$NameCurrent .
										"<div class='panel panel-danger'>Kein Erfolg beim Ändern.</div>"; 
					}
					return $ResultPanel;
				default:
					return $NameCurrent;
			}
		}
		
		public static function setOptions($idGenre)
		{
			$Options = 	"<a data-toggle='tooltip' data-original-title='edit genre' href='index.php?site=genres&option=edit&id=" . $idGenre . "'><span class='glyphicon glyphicon-pencil'></span></a>" .
						"<a data-href='index.php?site=genres&option=delete&id=".$idGenre."' data-toggle='modal' data-target='#confirm-delete' href='#'><span data-toggle='tooltip' data-original-title='delete genre' class='glyphicon glyphicon-trash'></span></a>";
			
			return $Options;
		}
		
		public static function getGenreForm($title, $datatable, $except, $repeat, $placeholders)
		{
			$GenreForm = new \FormularGenerator\formulargenerator($title, $datatable, $except, $repeat, $placeholders, false);
			return $GenreForm;
		}
		
		public static function create($name)
		{
			$Genre = new \EventManager\BusinessObjects\Genre(0, $name);
			$createSuccessfull = $Genre->create();
			
			return $createSuccessfull;
		}
		
		public static function delete($id)
		{
			$Genre = new \EventManager\BusinessObject\Genre($id, "");
			$deleteSuccessfull = $Genre->delete();
			
			return $deleteSuccessfull;
		}

	}
?>