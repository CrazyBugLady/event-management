<?php
	namespace EventManager\BusinessObjects;
	
	require_once("php/Models/GenreDbModel.php");

	class Genre
	{
		public $idGenre;
		public $Name;
	
		public function __construct($idGenre, $Name)
		{
			$this->idGenre = $idGenre;
			$this->Name = $Name;
		}
		
		public function getName()
		{
			return $this->Name;
		}
		
		public function getId()
		{
			return $this->idGenre;
		}
		
		public function update()
		{
			$updateSuccessfull = \EventManager\Models\GenreDbModel::update($this);
			
			return $updateSuccessfull;
		}
		
		public function create()
		{
			$createSuccessfull = \EventManager\Models\GenreDbModel::create($this);
			
			return $createSuccessfull;
		}
		
		public function delete()
		{
			$deleteSuccessfull = \EventManager\Models\GenreDbModel::delete($this);
			
			return $deleteSuccessfull;
		}
	}
?>