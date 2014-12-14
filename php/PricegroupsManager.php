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
		
		public static function showEditForm($option, $Pricegroup, $UserAvailable, $idEdit)
		{
			$Form = "";
			
			if($Pricegroup->getId() != $idEdit)
			{
				$option = "not";
			}
			
			switch($option)
			{
				case 'edit':
					$Form .= "<form method='post' action='index.php?site=pricegroups&option=editsave&id=" . $idEdit . "'>" .
							 "<tr>" . PHP_EOL .
							 "<td><input type='text' class='form-control' name='txtName' value='". $Pricegroup->getName() ."'></td>" . PHP_EOL .
							 "<td><input type='text' class='form-control' name='txtPreis' value='" . $Pricegroup->getPrice() . "'></td>" . PHP_EOL.
							 "<td><input type='submit' class='btn btn-success' value='Ändern'>" . PHP_EOL . 
									"<a href='index.php?site=pricegroups' class='btn btn-danger'>Cancel</a></td>". PHP_EOL .
							 "</form>";
				break;
				case 'editsave':
					
				break;
				default:
					$Form .= "<tr>" . PHP_EOL .
							 "<td>". $Pricegroup->getName() ."</td>" . PHP_EOL .
							 "<td>" . $Pricegroup->getPrice() . "</td>" . PHP_EOL;
							 
					if($UserAvailable)
					{
						$Form .= "<td>" . self::setOptions($Pricegroup->getId()) . "</td>" . PHP_EOL;
					}
					else
					{
						$Form .= "<td>No options available</td>";
					}	
				break;
			}
			
			$Form .= "</tr>";
			
			echo $Form;
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
				self::showEditForm($option, $Pricegroup, $UserAvailable, $idPricegroup);
			}
			
			echo "</table>";
		}
		
		public static function setOptions($idPricegroup)
		{
			$Options = 	"<a data-toggle='tooltip' data-original-title='edit genre' href='index.php?site=pricegroups&option=edit&id=" . $idPricegroup . "'><span class='glyphicon glyphicon-pencil'></span></a>" .
						"<a data-href='index.php?site=pricegroups&option=delete&id=". $idPricegroup ."' data-toggle='modal' data-target='#confirm-delete' href='#'><span data-toggle='tooltip' data-original-title='delete pricegroup' class='glyphicon glyphicon-trash'></span></a>";
			
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
		
		public static function delete($id)
		{
			$Pricegroup = new \EventManager\BusinessObjects\Pricegroup($id, "", "");
			$deleteSuccessfull = $Pricegroup->delete();
			
			return $deleteSuccessfull;
		}
		
	}
?>