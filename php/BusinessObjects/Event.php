<?php
	namespace EventManager\BusinessObjects;
	
	require_once("php/Models/UserDbModel.php");
	require_once("php/Models/EventDbModel.php");
	require_once("php/Models/GenreDbModel.php");
	require_once("php/Models/PresentationDbModel.php");
	require_once("php/BusinessObjects/PresentationDate.php");

	class Event
	{
		public $idEvent;
		public $Name;
		public $Description;
		public $Persons;
		public $PicturePath;
		public $PictureDescription;
		public $idGenre;
		public $Duration;
		public $ModificationDate;
		public $CreationDate;
	
		public function __construct($idEvent, $Name, $Description, $Persons, $Duration, $PicturePath, $PictureDescription, $idGenre, $ModificationDate, $CreationDate)
		{
			$this->idEvent = $idEvent;
			$this->Name = $Name;
			$this->Description = $Description;
			$this->Persons = $Persons;
			$this->PicturePath = $PicturePath;
			$this->PictureDescription = $PictureDescription;
			$this->idGenre = $idGenre;
			$this->Duration = $Duration;
			$this->ModificationDate = $ModificationDate;
		} 
		
		public function getFormattedModificationDate()
		{
			return date("d.m.Y H:i:s", strtotime($this->ModificationDate));
		}
		
		public function getFormattedCreationDate()
		{
			return date("d.m.Y H:i:s", strtotime($this->CreationDate));
		}
		
		public function hasBeenModified()
		{
			return $this->ModificationDate != "0000-00-00 00:00:00";
		}
		
		public function getGenre()
		{
			$genre = \EventManager\Models\GenreDbModel::read($this->idGenre);
			
			return $genre->Name;
		}
		
		public function getPresentationData()
		{
			$presentationData = \EventManager\Models\PresentationDbModel::read($this->idEvent);
			
			return $presentationData;
		}
		
		public function isStillActive()
		{
			$tempPresentationData = getPresentationData();
			
			$lastPresentationDate = strtotime($tempPresentationData[sizeof($tempPresentationData) - 1]);
			$currentDate = time();
			
			if($lastPresentationDate >= $currentDate)
			{
				return true;
			}
			
			return false;
		}
		
		public function create()
		{
			$createSuccessfull = \EventManager\Models\EventDbModel::create($this);
			
			return $createSuccessfull;
		}
		
		public function update()
		{
			$updateSuccessfull = \EventManager\Models\EventDbModel::update($this);
			
			return $updateSuccessfull;
		}
		
		public function delete()
		{
			$deleteSuccessfull = \EventManager\Models\EventDbModel::delete($this);
			
			return $deleteSuccessfull;
		}
		
	}
?>