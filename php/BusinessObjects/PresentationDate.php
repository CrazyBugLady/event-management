<?php
	namespace EventManager\BusinessObjects;

	class PresentationDate
	{
		public $idPresentationDate;
		public $PresentationTime;
		public $PresentationDate;
		public $idEvent;
	
		public function __construct($idPresentationDate, $Time, $Date, $idEvent)
		{
			$this->idPresentationDate = $idPresentationDate;
			$this->PresentationTime = $Time;
			$this->PresentationDate = $Date;
			$this->idEvent = $idEvent;
		} 
		
	}
?>