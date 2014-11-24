<?php
	namespace EventManager;

	require_once("Models/EventDbModel.php");
	require_once("BusinessObjects/EventFilter.php");
	require_once("BusinessObjects/Event.php");
	require_once("BusinessObjects/User.php");
	
	class EventManager
	{	
		private $except;
		
		private static $perSite = 5,
				$Title = 'EventManager';
		
		public static function countAllEvents()
		{
			$Events = \EventManager\Models\EventDbModel::readAll();
			return sizeof($Events);
		}
		
		// Events anzeigen mit selbst generiertem Filter
		public static function showEvents($Page, $loggedInUser)
		{			
			$Filter = self::generateFilter(0, "Erstelldatum", "desc"); 
			
			$allEvents = \EventManager\Models\EventDbModel::readAll($Filter); // um alle Einträge zu kriegen, zuerst nicht Paging aktivieren
			
			$Filter = self::generateFilter($Page, "Erstelldatum", "desc");
			
			$EventsCurrentPage = \EventManager\Models\EventDbModel::readAll($Filter); // um nur Einträge von der aktuellen Page zu erhalten
			
			echo "<h2>". self::$Title ."</h2>";
			
			echo "<h3>Events (". self::countAllEvents() .")</h3>";
			
			self::setPaging(sizeof($allEvents), $Page);
					
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
				
				if($Event->hasBeenModified())
				{
					echo "<tr>" . PHP_EOL;
					echo "<td>This Event has been modified ".$Event->getFormattedModificationDate()."</td>";
					echo "</tr>" . PHP_EOL;
				}
				
				if($loggedInUser != "")
				{
					echo "<tr>" . PHP_EOL;
					echo "<td>". self::setOptions($Event->idEvent) ."</td>";
					echo "</tr>" . PHP_EOL;
				}
				
				echo "</table>". PHP_EOL;
			}
			if(sizeof($EventsCurrentPage) == 0)
			{
				echo "<p>Auf dieser Seite gibt es keine Events!</p>" . PHP_EOL;
			}
		}
		
		public static function showCalendar($idEvent)
		{
			?>
				<script type="text/javascript">
					$(document).ready(function() {
						$('#calendar').fullCalendar({
							events: [
								<?php
									$Event = \EventManager\Models\EventDbModel::read($idEvent);
									
									$PresentationData = $Event->getPresentationData();
									
									foreach($PresentationData as $PresentationDate)
									{
										?>
											
										<?php
									}
								?>
							]
						});
					});
				</script>
			<?php
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
			echo "<a data-toggle='tooltip' data-original-title='edit event' href='#'><span class='glyphicon glyphicon-pencil'></span></a>";
			echo "<a data-toggle='tooltip' data-original-title='delete event and dependencies' href='#'><span class='glyphicon glyphicon-trash'></span></a>";
			echo "<a data-toggle='tooltip' data-original-title='watch the presentation data' href='index.php?site=presentation&id='". $idEvent ."'><span class='glyphicon glyphicon-calendar'></span></a>";
			echo "<a data-toggle='tooltip' data-original-title='watch the prices' href='#'><span class='glyphicon glyphicon-usd'></span></a>";
		}
		
		public static function generateFilter($currentPage, $orderBy, $orderByDirection)
		{
			$Endpoint = 0;
			$Startpoint = 0;
			
			if($currentPage != 0)
			{
				$Endpoint = $currentPage * self::$perSite; // bsp: Seite 2 = Endpunkt 20
				$Startpoint = $Endpoint - self::$perSite; // bsp: Seite 2 = Endpunkt 20 - 10 = 10
			}
			
			$generatedFilter = new \EventManager\BusinessObjects\EventFilter($Startpoint, $Endpoint, $orderBy, $orderByDirection);
			
			return $generatedFilter;
		}
		
		public static function getEventForm($title, $datatable, $except, $repeat, $placeholders)
		{
			$EventForm = new \FormularGenerator\formulargenerator($title, $datatable, $except, $repeat, $placeholders, false);
			return $EventForm;
		}

	}
?>