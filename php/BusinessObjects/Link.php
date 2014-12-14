<?php
	namespace EventManager\BusinessObjects;

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
		
	}
?>