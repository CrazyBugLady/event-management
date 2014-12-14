	<?php
		include_once("php/EventManager.php");
	?>
	
	<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					Löschung
				</div>
				<div class="modal-body">
					Willst du die angeforderte Löschung wirklich bestätigen? (Achtung: kann NICHT rückgängig gemacht werden!)
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<a href="#" class="btn btn-danger danger">Delete</a>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="confirm-create-event" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					Erstellen eines Events
				</div>
				<div class="modal-body">
					<form action="index.php?site=sign" method="post" role="form">
					<?php
						\EventManager\EventManager::getGenreDropdown(false);
					?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<input type="submit" value='Create' class="btn btn-success success"/>
					</form>
				</div>
			</div>
		</div>
	</div>