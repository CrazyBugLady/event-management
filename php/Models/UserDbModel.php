<?php
	
	namespace EventManager\Models;
		
	require_once("php/Data/DB.class.php");
	require_once("php/BusinessObjects/User.php");

	class UserDBModel //extends iDbModel 
	{
		public static $DB;
	
		public function __construct()
		{
			
		}
		
		public static function read($idUser)
		{
			self::$DB = \EventManager\Data\DB::getConnection("read", "Resources/Configuration/config.ini");
	
			$stmt = self::$DB->prepare("SELECT ID, benutzername, passwort_p, erstelldatum FROM benutzer WHERE ID = ?");
			$stmt->bind_param("i", $idUser);
			
			$user = new \EventManager\BusinessObjects\User();
			
			if ($stmt->execute()) {
				$result = $stmt->get_result();
				$row = $result->fetch_assoc();
		
				$user->idUser = $row["ID"];
				$user->Nickname = $row["benutzername"];
				$user->Password = $row["passwort_p"];
				$user->CreationDate = $row["erstelldatum"];
			
			}
	
			return $user;
		}
		
		public static function readAll($filter = null)
		{
			self::$DB = \EventManager\Data\DB::getConnection("read", "Resources/Configuration/config.ini");
			
			$stmt = self::$DB->prepare("SELECT ID, benutzername, passwort_p, erstelldatum FROM benutzer");
			
			$users = array();
			
			if ($stmt->execute()) {
			
			$result = $stmt->get_result();
			
			$i = 0;
				while ($row = $result->fetch_assoc()) {
					$i++;
					
					$user = new \EventManager\BusinessObjects\User();
					$user->idUser = $row["ID"];
					$user->Nickname = $row["benutzername"];
					$user->Password = $row["passwort_p"];
					$user->CreationDate = $row["erstelldatum"];

					$users[$i] = $user;
				}
			}

			return $users;
		}
		
		public static function create($user)
		{
			self::$DB = \EventManager\Data\DB::getConnection("insert", "Resources/Configuration/config.ini");
			$stmt = self::$DB->prepare("INSERT INTO benutzer (benutzername, passwort) VALUES (?, ?)");
			$stmt->bind_param("ss", 
							  $user->Nickname, $user->Password);
			$successCreate = $stmt->execute();
			
			self::$DB->close();
			
			return $successCreate;
		}
		
		public static function update($user)
		{
			self::$DB = \EventManager\Data\DB::getConnection("edit", "Resources/Configuration/config.ini");
			$stmt = self::$DB->prepare("UPDATE benutzer SET passwort_p = ? " . 
									   "WHERE ID = ?");
			$stmt->bind_param("si", $user->Password, $user->idUser);
			$successUpdate = $stmt->execute();	

			self::$DB->close();			
			
			return $successUpdate;
		}
		
		public static function delete($user)
		{
			self::$DB = \EventManager\Data\DB::getConnection("delete", "Resources/Configuration/config.ini");
			$stmt = self::$DB->prepare("DELETE FROM benutzer WHERE id = ?");
			$stmt->bind_param("i", $user->idUser);
			$successDelete = $stmt->execute();			
			
			self::$DB->close();
			
			return $successDelete;
		}

	}

?>