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

		public function setPresentationDate($Date)
		{			
			$this->PresentationDate = date("Y-m-d", strtotime($Date))
		}
		
		public function create()
		{
			$createSuccessfull = \EventManager\PresentationDbModel::create($this);
			
			return $createSuccessfull;
		}
		
	}
?>