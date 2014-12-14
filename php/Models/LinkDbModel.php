<?php
	namespace EventManager\Models;

	require_once("php/BusinessObjects/Link.php");
	
	class LinkDbModel
	{
		public static $DB;
		
		public function read($idLink)
		{
			self::$DB = \EventManager\Data\DB::getConnection("read", "Resources/Configuration/config.ini");
		
			$stmt = self::$DB->prepare("SELECT ID, link, name, idVeranstaltung FROM link WHERE ID = ?");
			$stmt->bind_param("i", $idLink);
			
			$Link = "";
						
			if ($stmt->execute()) {
				$result = $stmt->get_result();
				$row = $result->fetch_assoc();
				
				$Link = new \EventManager\BusinessObjects\Link($row["ID"], $row["name"], $row["link"], $row["idVeranstaltung"]);
			}
			
			return $Link;
		}
		
		public function readAll($idEvent)
		{
			self::$DB = \EventManager\Data\DB::getConnection("read", "Resources/Configuration/config.ini");
		
			$stmt = self::$DB->prepare("SELECT ID, link, name, idVeranstaltung FROM link WHERE idVeranstaltung = ?");
			
			$stmt->bind_param("i", $idEvent);
			
			$links = array();
			
			if ($stmt->execute()) {
			
				$result = $stmt->get_result();
				
				$i = 0;
					while ($row = $result->fetch_assoc()) {
						$i++;
					
						$link = new \EventManager\BusinessObjects\Link($row["ID"], $row["name"], $row["link"], $row["idVeranstaltung"]);
						$links[$i] = $link;
				}
			}
			
			self::$DB->close();
			
			return $links;
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