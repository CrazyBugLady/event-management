<?php
	namespace EventManager\Models;

	require_once("php/Data/DB.class.php");
	require_once("php/BusinessObjects/Pricegroup.php");
	
	class PriceGroupsDbModel
	{
		public static $DB;
		
		public function read($idPricegroup)
		{
			self::$DB = \EventManager\Data\DB::getConnection("read", "Resources/Configuration/config.ini");
		
			$stmt = self::$DB->prepare("SELECT ID, name, preis FROM Pricegroupe WHERE ID = ?");
	
			$stmt->bind_param("i", $idPricegroup);
			
			$Pricegroupe = "";
						
			if ($stmt->execute()) {
				$result = $stmt->get_result();
				$row = $result->fetch_assoc();
				
				$Pricegroupe = new \EventManager\BusinessObjects\Pricegroup($row["ID"], $row["name"], $row["preis"]);
			}
			
			return $Pricegroupe;
		}
		
		public function readAll($idEvent)
		{
			self::$DB = \EventManager\Data\DB::getConnection("read", "Resources/Configuration/config.ini");
		
			$SQLPricegroup = "SELECT p.ID, p.name, preis FROM Preisgruppe p";
			
			if($idEvent != 0)
			{
				$SQLPricegroup .= " LEFT JOIN veranstaltung_hat_preisgruppe vhp ON vhp.idPreisgruppe = p.ID LEFT JOIN Veranstaltung v ON vhp.idVeranstaltung = v.ID WHERE v.ID = ?"; 
			}
			
			$stmt = self::$DB->prepare($SQLPricegroup);
			
			if($idEvent != 0)
			{
				$stmt->bind_param("i", $idEvent);
			}
			
			$Pricegroups = array();
			
			if ($stmt->execute()) {
			
				$result = $stmt->get_result();
				
				$i = 0;
					while ($row = $result->fetch_assoc()) {
						$i++;
					
						$Pricegroup = new \EventManager\BusinessObjects\Pricegroup($row["ID"], $row["name"], $row["preis"]);
					
						$Pricegroups[$i] = $Pricegroup;
				}
			}
			
			self::$DB->close();
			
			return $Pricegroups;
		}
		
		public function create($pricegroup)
		{
			self::$DB = \EventManager\Data\DB::getConnection("insert", "Resources/Configuration/config.ini");
			$stmt = self::$DB->prepare("INSERT INTO preisgruppe (name, preis) VALUES (?, ?)");
			$stmt->bind_param("ss", 
							  $pricegroup->getName(),
							  $pricegroup->getPrice());
			$successCreate = $stmt->execute();
			
			self::$DB->close();
			
			return $successCreate;
		}
		
		public function delete()
		{
			// wird in diesem Fall nicht unterstützt
		}
		
		public function update($Pricegroup)
		{
			self::$DB = \EventManager\Data\DB::getConnection("edit", "Resources/Configuration/config.ini");

			$stmt = self::$DB->prepare("UPDATE Preisgruppe SET name = ? & preis = ? WHERE ID = ?");
								
			$stmt->bind_param("si", $Pricegroup->Name, $Pricegroup->Preis, $Pricegroup->idPricegroupe);
			
			$successUpdate = $stmt->execute();
			
			self::$DB->close();
			
			return $successUpdate;
		}


	}

?>