<?php
	namespace EventManager;

	require_once("Models/EventDbModel.php");
	require_once("BusinessObjects/EventFilter.php");
	require_once("BusinessObjects/Event.php");
	require_once("BusinessObjects/User.php");
	
	class EventManager
	{	
		private $except;
		
		private static $perSite = 10,
				$Title = 'EventManager';
		
		// Events anzeigen mit selbst generiertem Filter
		public static function showEvents($Page, $loggedInUser, $archive, $Genre, $idEvent)
		{			
			if($idEvent == 0)
			{
				$Filter = self::generateFilter(0, "concat(Vorstellung.datum, ' ', Vorstellung.zeit)", "asc", $archive, $Genre); 
				$allEvents = \EventManager\Models\EventDbModel::readAll($Filter); // um alle Einträge zu kriegen, zuerst nicht Paging aktivieren
				
				$Filter = self::generateFilter($Page, "concat(Vorstellung.datum, ' ', Vorstellung.zeit)", "asc", $archive, $Genre);
				$EventsCurrentPage = \EventManager\Models\EventDbModel::readAll($Filter); // um nur Einträge von der aktuellen Page zu erhalten
				
				echo "<h2>". self::$Title ."</h2>";
				echo "<h3>Events</h3>";
			
				self::setPaging(sizeof($allEvents), $Page, $archive);
							
				self::showFilterGenre($Page, $archive, $Genre);		
						
				if($loggedInUser != "")
				{
					echo "<p><a href='#' data-toggle='modal' data-target='#confirm-create-event'>You want to make a new Event?</a></p>" . PHP_EOL;
				}
				
				// Ausgabe der Events für diese Seite
				if(sizeof($EventsCurrentPage) == 0)
				{
					echo "<p>Auf dieser Seite gibt es keine Events!</p>" . PHP_EOL;
				}
				else
				{
					self::showList($EventsCurrentPage);
				}
			}
			else
			{
				$Event = \EventManager\Models\EventDbModel::read($idEvent);
				
				echo "<h3>Detailansicht</h3>";
				
				if($Event->Name != ""){
					self::showDetails($Event, $loggedInUser, $archive);
				}
				else
				{
					echo "Event nicht vorhanden!";
				}
			}
		}
		
		public static function showList($Events)
		{
			print("<table class='table table-striped'>" . PHP_EOL .
						"<tr>" . PHP_EOL .
							"<th>Name</th>" . PHP_EOL .
							"<th>Duration</th>" . PHP_EOL .
							"<th>Detailansicht</th>" . PHP_EOL .
						"</tr>" . PHP_EOL);
			
			foreach($Events as $Event)
			{
				print("<tr>" . PHP_EOL . 
							"<td>" . $Event->Name . "</td>" . PHP_EOL .
							"<td>" . $Event->Duration . "h</td>" . PHP_EOL .
							"<td><a href='index.php?site=show&id=" . $Event->idEvent . "'>Event genauer betrachten</a></td>" . PHP_EOL .
					   "</tr>" . PHP_EOL);
			}
			
			echo "</table>";
		}
		
		public static function showDetails($Event, $loggedInUser, $archive)
		{
				echo "<table class='table table-striped'>" . PHP_EOL;
				
				echo "<tr>". PHP_EOL;
				echo "<td><a class='fancybox' href='Resources/Images/". $Event->PicturePath ."'><img class='img-thumbnail' src='Resources/Images/thumbnails/" . $Event->PicturePath . "'></td>". PHP_EOL;
				echo "<td>" . $Event->PictureDescription . "</td>". PHP_EOL;
				echo "</tr>". PHP_EOL;
				
				echo "<tr>". PHP_EOL;
				echo "<th>Titel:</th>". PHP_EOL;
				echo "<td>" . $Event->Name. "</td>" . PHP_EOL;
				echo "</tr>". PHP_EOL;
				
				echo "<tr>". PHP_EOL;
				echo "<th>Genre:</th>". PHP_EOL;
				echo "<td>" . $Event->getGenre(). "</td>" . PHP_EOL;
				echo "</tr>". PHP_EOL;
				
				echo "<tr>". PHP_EOL;
				echo "<th colspan='2'>Beschreibung</th>". PHP_EOL;
				echo "</tr>". PHP_EOL;
				echo "<tr>". PHP_EOL;
				echo "<td colspan='2'>" . $Event->Description . "</td>". PHP_EOL;
				echo "</tr>". PHP_EOL;
				echo "<tr>". PHP_EOL;
				echo "<th colspan='2'>Besetzung</th>". PHP_EOL;
				echo "</tr>". PHP_EOL;
				echo "<tr>". PHP_EOL;
				echo "<td colspan='2'>" . $Event->Persons . "</td>". PHP_EOL;
				echo "</tr>". PHP_EOL;
				
				echo "<tr>" . PHP_EOL;
				echo "<th colspan='2'>Vorstellungstermine</th>" . PHP_EOL;
				echo "</tr>" . PHP_EOL;
				
				$PresentationData = $Event->getPresentationData();
				foreach($PresentationData as $PresentationDate)
				{
					echo "<tr>" . PHP_EOL;
					echo "<td>" . $PresentationDate->PresentationDate . "</td>" . PHP_EOL;
					echo "<td>" . $PresentationDate->PresentationTime . "</td>" . PHP_EOL;
					echo "</tr>" . PHP_EOL;
				}
				
				echo "<tr>" . PHP_EOL;
				echo "<th colspan='2'>Links</th>" . PHP_EOL;
				echo "</tr>" . PHP_EOL;
				
				echo "<tr>" . PHP_EOL;
				echo "<td colspan='2'><ul>" . PHP_EOL;
				$Links = $Event->getLinks();
				foreach($Links as $Link)
				{
					
					echo "<li><a href='" . $Link->Link . "'>". $Link->Name ."</a></li>" . PHP_EOL;
					
				}
				
				echo "</ul></td>" . PHP_EOL;
				echo "</tr>" . PHP_EOL;
				
				echo "<tr>" . PHP_EOL;
				echo "<th colspan='2'>Preisgruppen</th>" . PHP_EOL;
				echo "</tr>" . PHP_EOL;
				
				$Pricegroups = $Event->getPricegroups();
				
				foreach($Pricegroups as $Pricegroup)
				{
					echo "<tr>" . PHP_EOL;
					echo "<td>" . $Pricegroup->Name . "</td>";
					echo "<td>". $Pricegroup->Price ."</td>";
					echo "</tr>" . PHP_EOL;	
				}		
				
				if($Event->hasBeenModified())
				{
					echo "<tr>" . PHP_EOL;
					echo "<td colspan='2'>This Event has been modified ".$Event->getFormattedModificationDate()."</td>";
					echo "</tr>" . PHP_EOL;
				}
				
				if($loggedInUser != "" && $archive != true)
				{
					echo "<tr>" . PHP_EOL;
					echo "<td colspan='2'>". self::setOptions($Event->idEvent) ."</td>";
					echo "</tr>" . PHP_EOL;
				}
				
				echo "</table>". PHP_EOL;
				
				echo "<br> <br>";
		}
		
		public static function showCalendar($idEvent)
		{
			$Event = \EventManager\Models\EventDbModel::read($idEvent);
			$PresentationData = $Event->getPresentationData();
			
			if(count($PresentationData) == 0)
			{
				echo "<p>Noch keine Vorstellungstermine vorhanden!</p>";
			}
		
			?>
				<script type="text/javascript">
					$(document).ready(function() {
						$('#calendar').fullCalendar({
										
							 events: [
								<?php
								
									foreach($PresentationData as $key => $PresentationDate)
									{
										echo "{";
										echo "id: '". $PresentationDate->idPresentationDate ."',";
										echo "title: '". $Event->Name ."'," . PHP_EOL;
										echo "start: '" . $PresentationDate->PresentationDate."'," . PHP_EOL;
										echo "allday: false " .PHP_EOL;
										echo "}";
										
										if(count($PresentationData) > $key)
										{
											echo ",";
										}
									}
								?>
							],
							eventClick: function(event) {
								alert('Duration: <?php echo $Event->Duration; ?>h \n Uhrzeit: <?php echo $PresentationDate->PresentationTime; ?>');
								
								if (event.url) {
									
									return false;
								}
							},
							dayClick: function(date, jsEvent, view) {
								var formattedDate = new Date(date);
								var d = formattedDate.getDate();
								var m =  formattedDate.getMonth();
								m += 1;  // JavaScript months are 0-11
								var y = formattedDate.getFullYear();
								
								$("[name=tbdatum]").val(d + "." + m + "." + y);
							}
						});
					});
				</script>
				
			<?php
			
			$PresentationForm =self::getEventForm("create presentationdate", "vorstellung", array("ID", "idVeranstaltung"), array(), array());
			$PresentationForm->createForm("index.php?site=presentation&id=" . $Event->idEvent);
		}
		
		public static function setPaging($Events, $currentPage, $archive)
		{
			$SiteAmount = ceil($Events / self::$perSite);
			
			echo "<ul class='pagination'>" . PHP_EOL;
			
			for ($Page = 1; $Page <= $SiteAmount; $Page++)
			{
				$ActiveAttribute = ($Page == $currentPage) ? "class='active'" : "";
				$site = $archive == true ? "archive" : "show";
				echo "<li ". $ActiveAttribute ."><a href='index.php?site=".$site ."&page=". $Page ."'>". $Page ."</a></li>" . PHP_EOL; 
			}
			
			echo "</ul>" . PHP_EOL;
		}

		public static function setOptions($idEvent)
		{
			$options = "";
			$options .= "<a data-toggle='tooltip' data-original-title='edit event' href='index.php?site=edit&id=". $idEvent ."'><span class='glyphicon glyphicon-pencil'></span></a>";
			$options .= "<a href='#' data-href='index.php?site=delete&id=" . $idEvent . "' data-toggle='modal' data-target='#confirm-delete'><span data-toggle='tooltip' title='delete event and dependencies' class='glyphicon glyphicon-trash'></span></a>";
			$options .= "<a data-toggle='tooltip' data-original-title='watch the presentation data' href='index.php?site=presentation&id=". $idEvent ."'><span class='glyphicon glyphicon-calendar'></span></a>";
			
			return $options;
		}
		
		public static function getGenreDropdown($filter, $selectedGenre)
		{
			$Genres = \EventManager\Models\GenreDbModel::readAll();
			?>
			<select class="form-control" name="selectedgenre">
			<?php
			if($filter)
			{
			?>
				<option value='0'>-- All -- </option>
			<?php
			}
			foreach($Genres as $Genre)
			{
				$selected = $Genre->getId() == $selectedGenre ? "selected" : "";
				echo "<option value='". $Genre->getId() ."' " . $selected . ">". $Genre->Name .  "</option>" . PHP_EOL;	
			}	
			?>
			</select>
			<?php
		}
		
		public static function getPricegroupCheckboxes($selectedPricegroups, $idEvent)
		{
			$selectedIds = array();
			$idindex = 0;
			$Form = "";
			
			foreach($selectedPricegroups as $selected)
			{
				$selectedIds[$idindex] = $selected->getId();
				$idindex++;
			}
		
			$Pricegroups = \EventManager\Models\PricegroupsDbModel::readAll(0);
			$Form .= "<form method='post' action='index.php?site=edit&id=" . $idEvent . "'>";
			
			foreach($Pricegroups as $key => $Pricegroup)
			{
				$checked = in_array($Pricegroup->getId(), $selectedIds) ? "checked" : "";
				
				$Form .= "<label class='checkbox-inline'> " . PHP_EOL .
						 "  <input type='checkbox' name='pricegroups[]' value='". $Pricegroup->getId() . "' " . $checked . "> " . $Pricegroup->Name . PHP_EOL .
					     "</label>";
			}
			
			$Form .= "<br><input type='submit' class='btn btn-success' name='submit' value='Speichern'>";
			$Form .= "</form>";
			
			return $Form;
		}
		
		/**
		* Methode zur Anzeige der Links der Events in editierbarer Form
		*/
		public static function getLinksTextboxes($Links, $idEvent)
		{
			$Form = "";
			
			foreach($Links as $Link)
			{
				$Form .= "<tr><td width='50%'><input name='link-name[]' type='text' class='form-control' required='required' pattern='{0,50}'" .
										   "maxlength='50' title='name for the url' value='" . $Link->Link . "'></td>" . PHP_EOL . 
						 "<td><input name='link-url[]' type='url' class='form-control' required='required' pattern='{5,255}'" .
										   "maxlength='50' value='" . $Link->Name . "'></td>" . PHP_EOL . 
						 "<td><a class='delete-link' title='Delete this link'><span class='glyphicon glyphicon-trash'></span></td></tr>";
										
			}
			
			return $Form;
		}
			
		
		/*
		*	Methode zur Anzeige des Filters für Genres
		*/
		public static function showFilterGenre($Page, $archive, $selectedGenre)
		{
			$link = "index.php?site=";
			
			// stellt den Link zusammen, um richtig zu filtern (hängt ab, ob archiviert oder nicht)
			if($archive)
			{
				$link .= "archive&page=" . $Page;
			}
			else
			{
				$link .= "show&page=" . $Page;
			}
			?>
			<form method="post" action="<?php echo $link; ?>">
				<?php
					self::getGenreDropdown(true, $selectedGenre);
				?>
				<input class='btn btn-default' type='submit' name='submit' value='Filtern'>
			</form>
			<?php
		}	
		/**
		*	generieren des Filters
		*/
		public static function generateFilter($currentPage, $orderBy, $orderByDirection, $archive, $genre)
		{
			$Endpoint = 0;
			$Startpoint = 0;
			$SQLOptions = "";
			
			if($currentPage != 0)
			{
				$Endpoint = $currentPage * self::$perSite; // bsp: Seite 2 = Endpunkt 20
				$Startpoint = $Endpoint - self::$perSite; // bsp: Seite 2 = Endpunkt 20 - 10 = 10
			}
			
			if($genre != "" && $genre != 0)
			{
				$SQLOptions .= "idGenre = " . $genre;
			}
			
			$generatedFilter = new \EventManager\BusinessObjects\EventFilter($Startpoint, $Endpoint, $orderBy, $orderByDirection, $SQLOptions, $archive);
			
			return $generatedFilter;
		}
		
		public static function getEventForm($title, $datatable, $except, $repeat, $placeholders)
		{
			$EventForm = new \FormularGenerator\formulargenerator($title, $datatable, $except, $repeat, $placeholders, false);
			return $EventForm;
		}
		
		/*
		* Funktion zum Erstellen eines Events
		*/
		public static function create($Name, $Besetzung, $Beschreibung, $Dauer, $idGenre)
		{
			$Event = new \EventManager\BusinessObjects\Event(0, $Name, $Beschreibung, $Besetzung, $Dauer, "", "", $idGenre, "", "");
			$createSuccessfull = $Event->create();
			
			return $createSuccessfull;
		}

		/**
		* Funktion zum Updaten der Preisgruppen eines Events
		*/
		public static function updatePricegroupsFromEvent($selectedPricegroups, $idEvent)
		{
			\EventManager\Models\PriceGroupsDbModel::delete(0, $idEvent);
			
			$createSuccessfull = false;
			
			foreach($selectedPricegroups as $selectedPricegroup)
			{
				$Pricegroup = new \EventManager\BusinessObjects\Pricegroup($selectedPricegroup, "", "");
				$createSuccessfull = \EventManager\Models\PricegroupsDbModel::create($Pricegroup, $idEvent);
			}
			
			return $createSuccessfull;
		}
		
		/**
		* Funktion zum Updaten der Links eines Events
		*/
		public static function updateLinksFromEvent($links, $names, $idEvent)
		{
			\EventManager\Models\LinkDbModel::delete(0, $idEvent);
			
			$createSuccessfull = false;
			
			foreach($links as $key => $link)
			{
				$Link = new \EventManager\BusinessObjects\Link(0, $names[$key], $link, $idEvent);
				$createSuccessfull = $Link->create();
			}
			
			return $createSuccessfull;
		}
		
		/**
		* Funktion zum Löschen eines Events
		*/
		public static function delete($idEvent)
		{
			$Event = new \EventManager\BusinessObjects\Event($idEvent, "", "", "", "", "", "", "", "", "");
			$deleteSuccessfull = $Event->delete();
			
			return $deleteSuccessfull;
		}
		
		/**
		* Funktion zum Updaten eines Events
		*/
		public static function update($Event)
		{
			$updateSuccessfull = $Event->update();
			
			return $updateSuccessfull;
		}
		
	}
?>