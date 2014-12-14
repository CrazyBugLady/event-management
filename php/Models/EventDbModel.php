<?php
	namespace EventManager\Models;

	require_once("php/Data/DB.class.php");
	require_once("php/BusinessObjects/Event.php");
	
	class EventDbModel
	{
		public static $DB;
	
		public function __construct()
		{
			
		}
		
		public static function read($idEvent)
		{
			self::$DB = \EventManager\Data\DB::getConnection("read", "Resources/Configuration/config.ini");
			
			$stmt = self::$DB->prepare("SELECT ID, name, besetzung, beschreibung, dauer, bild, bildbeschreibung, idGenre, bearbeitungsdatum, erstelldatum FROM veranstaltung where id = ?");
			$stmt->bind_param("i", $idEvent);
			
			$Event = "";
			
			if ($stmt->execute()) {
				$result = $stmt->get_result();
				$row = $result->fetch_assoc();
				
				$Event = new \EventManager\BusinessObjects\Event($row["ID"], $row["name"], $row["besetzung"], $row["beschreibung"], $row["dauer"], $row["bild"], $row["bildbeschreibung"], $row["idGenre"], $row["bearbeitungsdatum"], $row["erstelldatum"]);
			}
			
			return $Event;
		}
		
		public static function readAll($filter)
		{
			self::$DB = \EventManager\Data\DB::getConnection("read", "Resources/Configuration/config.ini");
			
			$SQL = "SELECT distinct veranstaltung.ID, name, besetzung, beschreibung, dauer, bild, bildbeschreibung, idGenre, bearbeitungsdatum, erstelldatum FROM veranstaltung";

			if($filter != null)
			{
				$SQL = $filter->addToSQL($SQL);
			}
			
			$stmt = self::$DB->prepare($SQL);
			
			$events = array();
			
			if ($stmt->execute()) {
			
				$result = $stmt->get_result();
				
				$i = 0;
					while ($row = $result->fetch_assoc()) {
						$i++;
					
						$Event = new \EventManager\BusinessObjects\Event($row["ID"], $row["name"], $row["besetzung"], $row["beschreibung"], $row["dauer"], $row["bild"], $row["bildbeschreibung"], $row["idGenre"], $row["bearbeitungsdatum"], $row["erstelldatum"]);
						
						$events[$i] = $Event;
					}
			}
			
			self::$DB->close();
			
			return $events;
		}
		
		public static function create($Event)
		{
			self::$DB = \EventManager\Data\DB::getConnection("insert", "Resources/Configuration/config.ini");
			
			$stmt = self::$DB->prepare("INSERT INTO veranstaltung" .
									   " (name, besetzung, beschreibung, dauer, idGenre)" .
									   " VALUES (?, ?, ?, ?, ?)");
			$stmt->bind_param("ssssi", $Event->Name, $Event->Persons, $Event->Description, $Event->Duration, $Event->idGenre);
			
			$successCreate = $stmt->execute();
			
			self::$DB->close();
			
			return $successCreate;
		}
		
		public static function update($Event)
		{
			self::$DB = \EventManager\Data\DB::getConnection("edit", "Resources/Configuration/config.ini");

			$stmt = self::$DB->prepare("UPDATE veranstaltung SET bearbeitungsdatum = now(), " . 
													"name = ?, " .
													"beschreibung = ?, " .
													"bildbeschreibung = ?, " .
													"bild = ?, " .
													"besetzung = ?, " .
													"idgenre = ? " .
									   "WHERE id = ?");
			$stmt->bind_param("sssssii", $Event->Name, $Event->Description, $Event->PictureDescription, $Event->PicturePath, $Event->Persons, $Event->idGenre, $Event->idEvent);
			
			$successUpdate = $stmt->execute();
			
			self::$DB->close();
			
			return $successUpdate;
		}
		
		public function delete($Event)
		{
			self::$DB = \EventManager\Data\DB::getConnection("delete", "Resources/Configuration/config.ini");
			
			$stmt = self::$DB->prepare("DELETE FROM veranstaltung WHERE id = ?");
			$stmt->bind_param("i", $Event->idEvent);
			
			$successDelete = $stmt->execute();
			
			return $successDelete;
		}

	}

?>