<?php
	namespace EventManager\Models;

	require_once("php/BusinessObjects/PresentationDate.php");
	
	class PresentationDbModel
	{
		public static $DB;
		
		public function read($idEvent)
		{
			self::$DB = \EventManager\Data\DB::getConnection("read", "Resources/Configuration/config.ini");
		
			$stmt = self::$DB->prepare("SELECT ID, datum, zeit, idVeranstaltung FROM vorstellung WHERE idVeranstaltung = ?");
			$stmt->bind_param("i", $idEvent);
			
			$presentationData = "";
						
			if ($stmt->execute()) {
				$result = $stmt->get_result();
				$row = $result->fetch_assoc();
				
				$presentationData = new \EventManager\BusinessObjects\PresentationDate($row["id"], $row["idVeranstaltung"], $row["zeit"], $row["datum"]);
			}
			
			return $presentationData;
		}
		
		public function readAll()
		{
			self::$DB = \EventManager\Data\DB::getConnection("read", "Resources/Configuration/config.ini");
		
			$stmt = self::$DB->prepare("SELECT ID, datum, zeit, idVeranstaltung FROM vorstellung");
			
			$presentationData = array();
			
			if ($stmt->execute()) {
			
				$result = $stmt->get_result();
				
				$i = 0;
					while ($row = $result->fetch_assoc()) {
						$i++;
					
						$presentationDate = new \EventManager\BusinessObjects\PresentationDate($row["id"], $row["idVeranstaltung"], $row["zeit"], $row["datum"]);
					
						$presentationData[$i] = $presentationDate;
				}
			}
			
			self::$DB->close();
			
			return $presentationData;
		}
		
		public function create()
		{
			// wird in diesem Fall nicht unterstützt
		}
		
		public function delete()
		{
			// wird in diesem Fall nicht unterstützt
		}
		
		public function update()
		{
			// wird in diesem Fall nicht unterstützt
		}


	}

?>