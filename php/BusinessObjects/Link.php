<?php
	namespace EventManager\BusinessObjects;

	require_once("php/Models/LinkDbModel.php");
	
	class Link
	{
		public $IdLink;
		public $Name;
		public $Link;
		public $idEvent;
	
		public function __construct($IdLink, $Name, $Link, $idEvent)
		{
			$this->IdLink = $IdLink;
			$this->Name = $Name;
			$this->Link = $Link;
			$this->idEvent = $idEvent;
		} 
		
		public function create()
		{
			$createSuccessfull = \EventManager\Models\LinkDbModel::create($this);
			
			return $createSuccessfull;
		}
		
	}
?>