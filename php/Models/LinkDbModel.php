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
		
		public function create($link)
		{
			self::$DB = \EventManager\Data\DB::getConnection("insert", "Resources/Configuration/config.ini");
			
			$stmt = self::$DB->prepare("INSERT INTO link (name, link, idVeranstaltung) VALUES (?, ?, ?)");
			$stmt->bind_param("ssi", 
							$link->Name,
							$link->Link,
							$link->idEvent);
			
			$successCreate = $stmt->execute();
			
			self::$DB->close();
			
			return $successCreate;
		}
		
		public function delete($link, $idEvent)
		{
			self::$DB = \EventManager\Data\DB::getConnection("delete", "Resources/Configuration/config.ini");
			$stmt = "";
			
			if($idEvent == 0)
			{
				$stmt = self::$DB->prepare("DELETE FROM Link WHERE ID = ?");
				$stmt->bind_param("i", 
							  $link->IdLink());
			}
			else
			{
				$stmt = self::$DB->prepare("DELETE FROM Link WHERE idVeranstaltung = ?");
				$stmt->bind_param("i", 
							  $idEvent);
			}
			$successDelete = $stmt->execute();
			
			self::$DB->close();
			
			return $successDelete;
		}
		
		public function update()
		{
			// wird in diesem Fall nicht unterstützt
		}


	}

?>