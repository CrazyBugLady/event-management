<?php
	namespace EventManager;

	require_once("Models/PriceGroupsDbModel.php");
	require_once("BusinessObjects/Pricegroup.php");
	
	class PricegroupsManager
	{	
		private static $Title = "Pricegroups";
				
		public static function showPricegroupsSite($UserAvailable, $option, $idPricegroup)
		{
			echo "<h2>" . self::$Title . "</h2>";
			
			self::showPricegroups($UserAvailable, $option, $idPricegroup);
		}
		
		public static function showPricegroups($UserAvailable, $option, $idPricegroup)
		{
			$Pricegroups = \EventManager\Models\PriceGroupsDbModel::readAll(0);
			
			?>
			
			<table role="table" class="table table-striped">
				<tr>
					<th>Name</th>
					<th>Price</th>
					<th>Options</th>
				</tr>
			<?php
			
			foreach($Pricegroups as $Pricegroup)
			{
				echo "<tr>" . PHP_EOL;
				echo "<td>". $Pricegroup->getName() ."</td>";
				echo "<td>" . $Pricegroup->getPrice() . "</td>";
					
			if($UserAvailable)
			{
				echo "<td>" . self::setOptions($Pricegroup->getId()) . "</td>" . PHP_EOL;
			}
			else
			{
				echo "<td>No options available</td>";
			}
			}
			echo "</tr>" . PHP_EOL;
			
			echo "</table>";
		}
		
		public static function setOptions($idPricegroup)
		{
			$Options = 	"<a data-toggle='tooltip' data-original-title='edit genre' href='index.php?site=genres&option=edit&id=" . $idPricegroup . "'><span class='glyphicon glyphicon-pencil'></span></a>" .
						"<a data-href='index.php?site=start' data-toggle='modal' data-target='#confirm-delete' href='#'><span data-toggle='tooltip' data-original-title='delete pricegroup' class='glyphicon glyphicon-trash'></span></a>";
			
			return $Options;
		}
		
		public static function getPricegroupsform($title, $datatable, $except, $repeat, $placeholders)
		{
			$PricegroupsForm = new \FormularGenerator\formulargenerator($title, $datatable, $except, $repeat, $placeholders, false);
			return $PricegroupsForm;
		}
		
		public static function create($name, $preis)
		{
			$Pricegroup = new \EventManager\BusinessObjects\Pricegroup(0, $name, $preis);
			$createSuccessfull = $Pricegroup->create();
			
			return $createSuccessfull;
		}
	}
?>