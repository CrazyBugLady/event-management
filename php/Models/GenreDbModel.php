<?php
	namespace EventManager\Models;

	require_once("php/Data/DB.class.php");
	require_once("php/BusinessObjects/Genre.php");
	
	class GenreDbModel
	{
		public static $DB;
		
		public function read($idGenre)
		{
			self::$DB = \EventManager\Data\DB::getConnection("read", "Resources/Configuration/config.ini");
		
			$stmt = self::$DB->prepare("SELECT ID, name FROM genre WHERE ID = ?");
	
			$stmt->bind_param("i", $idGenre);
			
			$genre = "";
						
			if ($stmt->execute()) {
				$result = $stmt->get_result();
				$row = $result->fetch_assoc();
				
				$genre = new \EventManager\BusinessObjects\Genre($row["ID"], $row["name"]);
			}
			
			return $genre;
		}
		
		public function readAll()
		{
			self::$DB = \EventManager\Data\DB::getConnection("read", "Resources/Configuration/config.ini");
		
			$stmt = self::$DB->prepare("SELECT ID, name FROM genre order by name desc");
			
			$genres = array();
			
			if ($stmt->execute()) {
			
				$result = $stmt->get_result();
				
				$i = 0;
					while ($row = $result->fetch_assoc()) {
						$i++;
					
						$genre = new \EventManager\BusinessObjects\Genre($row["ID"], $row["name"]);
					
						$genres[$i] = $genre;
				}
			}
			
			self::$DB->close();
			
			return $genres;
		}
		
		public function create($genre)
		{
			self::$DB = \EventManager\Data\DB::getConnection("insert", "Resources/Configuration/config.ini");
			$stmt = self::$DB->prepare("INSERT INTO genre (name) VALUES (?)");
			$stmt->bind_param("s", 
							  $genre->getName());
			$successCreate = $stmt->execute();
			
			self::$DB->close();
			
			return $successCreate;
		}
		
		public function delete()
		{
			// wird in diesem Fall nicht unterstützt
		}
		
		public function update($Genre)
		{
			self::$DB = \EventManager\Data\DB::getConnection("edit", "Resources/Configuration/config.ini");

			$stmt = self::$DB->prepare("UPDATE genre SET name = ? WHERE ID = ?");
								
			$stmt->bind_param("si", $Genre->Name, $Genre->idGenre);
			
			$successUpdate = $stmt->execute();
			
			self::$DB->close();
			
			return $successUpdate;
		}


	}

?>