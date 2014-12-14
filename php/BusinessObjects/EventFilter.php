<?php
	namespace EventManager\BusinessObjects;
	
	class EventFilter
	{
		public $LimitStart;
		public $LimitEnd;
		
		public $OrderBy;
		public $OrderByDirection;
		
		public $SQLOptions;
		public $archive;
	
		public function __construct($LimitStart, $LimitEnd, $OrderBy, $OrderByDirection, $SQLOptions, $archive)
		{
			$this->LimitStart = ($LimitStart != "") ? $LimitStart : 0;
			$this->LimitEnd = ($LimitEnd != "") ? $LimitEnd : 1000;
			
			$this->OrderBy = $OrderBy;
			$this->OrderByDirection = $OrderByDirection;
			$this->SQLOptions = $SQLOptions;
			
			$this->archive = $archive;
		}
		
		public function addToSql($SQLString)
		{
						
			if($this->archive == true)
			{
				$SQLString .= " LEFT JOIN Vorstellung ON Vorstellung.idVeranstaltung = Veranstaltung.ID WHERE current_timestamp > concat(Vorstellung.datum, ' ', Vorstellung.zeit)";	
			}
			else
			{
				$SQLString .= " LEFT JOIN Vorstellung ON Vorstellung.idVeranstaltung = Veranstaltung.ID WHERE (current_timestamp <= concat(Vorstellung.datum, ' ', Vorstellung.zeit) OR Vorstellung.ID IS NULL)";	
			}
		
			if($this->SQLOptions != "")
			{
				if(strstr($SQLString, "WHERE"))
				{
					$SQLString .= " AND " . $this->SQLOptions;
				}
				else
				{
					$SQLString .= " WHERE " . $this->SQLOptions;
				}
			}
			
			if($this->OrderBy != "")
			{
				$SQLString .= " ORDER BY " . $this->OrderBy . " " . $this->OrderByDirection;
			}
			
			$SQLString .= " LIMIT " . $this->LimitStart . ", " . $this->LimitEnd;
			
			return $SQLString;
		}
	}
?>