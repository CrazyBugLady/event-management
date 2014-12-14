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
		
		public static function countAllEvents($Filter)
		{
			$Events = \EventManager\Models\EventDbModel::readAll($Filter);
			return sizeof($Events);
		}
		
		// Events anzeigen mit selbst generiertem Filter
		public static function showEvents($Page, $loggedInUser, $archive, $Genre)
		{			
			$Filter = self::generateFilter(0, "Erstelldatum", "desc", false, $Genre); 
			$allEvents = \EventManager\Models\EventDbModel::readAll($Filter); // um alle Einträge zu kriegen, zuerst nicht Paging aktivieren
			
			$Filter = self::generateFilter($Page, "Erstelldatum", "desc", $archive, $Genre);
			$EventsCurrentPage = \EventManager\Models\EventDbModel::readAll($Filter); // um nur Einträge von der aktuellen Page zu erhalten
			
			echo "<h2>". self::$Title ."</h2>";
			echo "<h3>Events (". self::countAllEvents($Filter) .")</h3>";
		
			self::setPaging(sizeof($allEvents), $Page);
					
			self::showFilterGenre($Page, $archive);		
					
			if($loggedInUser != "")
			{
				echo "<p><a href='index.php?site=sign'>You want to make a new Event?</a></p>" . PHP_EOL;
			}
			
			foreach($EventsCurrentPage as $Event)
			{
				echo "<table class='table table-striped'>" . PHP_EOL;
				echo "<tr>". PHP_EOL;
				echo "<th colspan='2'>Title: " . $Event->Name. "</th>". PHP_EOL;
				echo "</tr>". PHP_EOL;
				echo "<tr>" . PHP_EOL;
				echo "<td colspan='2'>Genre: ". $Event->getGenre() ."</td>" . PHP_EOL;
				echo "<tr>" . PHP_EOL;
				echo "<tr>". PHP_EOL;
				echo "<td colspan='2'>" . $Event->Description . "</td>". PHP_EOL;
				echo "</tr>". PHP_EOL;
				
				echo "<tr>" . PHP_EOL;
				echo "<th colspan='2'>Vorstellungstermine</th>" . PHP_EOL;
				echo "</tr>" . PHP_EOL;
				
				echo "<tr>" . PHP_EOL;
	
				$PresentationData = $Event->getPresentationData();
				foreach($PresentationData as $PresentationDate)
				{
					echo "<td>" . $PresentationDate->PresentationDate . "</td>" . PHP_EOL;
					echo "<td>" . $PresentationDate->PresentationTime . "</td>" . PHP_EOL;
				}
				
				echo "</tr>" . PHP_EOL;
				
				echo "<tr>" . PHP_EOL;
				echo "<th colspan='2'>Preisgruppen</th>" . PHP_EOL;
				echo "</tr>" . PHP_EOL;
				
				echo "<tr>" . PHP_EOL;
	
				$Pricegroups = $Event->getPricegroups();
				foreach($Pricegroups as $Pricegroup)
				{
					echo "<td>" . $Pricegroup->getName() . "</td>" . PHP_EOL;
					echo "<td>" . $Pricegroup->getPrice() . "</td>" . PHP_EOL;
				}
				
				echo "</tr>" . PHP_EOL;
				
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
			if(sizeof($EventsCurrentPage) == 0)
			{
				echo "<p>Auf dieser Seite gibt es keine Events!</p>" . PHP_EOL;
			}
		}
		
		public static function showCalendar($idEvent)
		{
			$Event = \EventManager\Models\EventDbModel::read($idEvent);
			$PresentationData = $Event->getPresentationData();
		
			?>
				<script type="text/javascript">
					$(document).ready(function() {
						$('#calendar').fullCalendar({
										
							 events: [
								<?php
									foreach($PresentationData as $PresentationDate)
									{
										echo "{";
										echo "id: '". $PresentationDate->idPresentationDate ."',";
										echo "title: '". $Event->Name ."'," . PHP_EOL;
										echo "start: '" . $PresentationDate->PresentationDate."'," . PHP_EOL;
										echo "allday: false, " .PHP_EOL;
										echo "description: 'duration: " . $Event->Duration ."h'". PHP_EOL;
										echo "}";
									}
								?>
							],
							eventClick: function(event) {
								alert('Duration: <?php echo $Event->Duration; ?>h');
								
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
			$PresentationForm->createForm("index.php?site=presentation?id=" . $Event->idEvent);
		}
		
		public static function setPaging($Events, $currentPage)
		{
			$SiteAmount = ceil($Events / self::$perSite);
			
			echo "<ul class='pagination'>" . PHP_EOL;
			
			for ($Page = 1; $Page <= $SiteAmount; $Page++)
			{
				$ActiveAttribute = ($Page == $currentPage) ? "class='active'" : "";
				echo "<li ". $ActiveAttribute ."><a href='index.php?site=show&page=". $Page ."'>". $Page ."</a></li>" . PHP_EOL; 
			}
			
			echo "</ul>" . PHP_EOL;
		}

		public static function setOptions($idEvent)
		{
			$options = "";
			$options .= "<a data-toggle='tooltip' data-original-title='edit event' href='#'><span class='glyphicon glyphicon-pencil'></span></a>";
			$options .= "<a href='#' data-href='index.php?site=start' data-toggle='modal' data-target='#confirm-delete'><span data-toggle='tooltip' title='delete event and dependencies' class='glyphicon glyphicon-trash'></span></a>";
			$options .= "<a data-toggle='tooltip' data-original-title='watch the presentation data' href='index.php?site=presentation&id=". $idEvent ."'><span class='glyphicon glyphicon-calendar'></span></a>";
			
			$options .= "<a data-toggle='tooltip' data-original-title='watch the prices' href='#'><span class='glyphicon glyphicon-usd'></span></a>";
			
			return $options;
		}
		
		public static function showFilterGenre($Page, $archive)
		{
			$Genres = \EventManager\Models\GenreDbModel::readAll();
			
			?>
			<form method="post" action="index.php?site=show&page=<?php echo $Page; ?>">
				<select class="form-control" name="selectedgenre">
					<option value='0'>-- All -- </option>
			<?php	
			foreach($Genres as $Genre)
			{
				echo "<option value=". $Genre->getId() .">". $Genre->getName() ."</option>" . PHP_EOL;	
			}	
			?>
				</select>
				
				<input class='btn btn-default' type='submit' name='submit' value='Filtern'>
			</form>
			<?php
		}	
		
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
		
		public static function create($Name, $Besetzung, $Beschreibung, $Dauer, $idGenre)
		{
			$Event = new \EventManager\BusinessObjects\Event(0, $Name, $Beschreibung, $Besetzung, $Dauer, "", "", $idGenre, "", "");
			$createSuccessfull = $Event->create();
			
			return $createSuccessfull;
		}

	}
?>