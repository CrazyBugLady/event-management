<?php
	namespace EventManager\BusinessObjects;
	
	require_once("php/Models/PricegroupsDbModel.php");

	class Pricegroup
	{
		public $idPricegroup;
		public $Name;
		public $Price;
	
		public function __construct($idPricegroup, $Name, $Price)
		{
			$this->idPricegroup = $idPricegroup;
			$this->Name = $Name;
			$this->Price = $Price;
		} 
		
		public function getName()
		{
			return $this->Name;
		}
		
		public function getId()
		{
			return $this->idPricegroup;
		}
		
		public function getPrice()
		{
			return $this->Price;
		}
		
		public function update()
		{
			$updateSuccessfull = \EventManager\Models\PricegroupsDbModel::update($this);
			
			return $updateSuccessfull;
		}
		
		public function create()
		{
			$createSuccessfull = \EventManager\Models\PricegroupsDbModel::create($this, 0);
			
			return $createSuccessfull;
		}
		
		public function delete()
		{
			$deleteSuccessfull = \EventManager\Models\PricegroupsDbModel::delete($this, 0);
			
			return $deleteSuccessfull;
		}
	}
?>