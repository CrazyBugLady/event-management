$(document).ready(function() {
  $('[data-toggle="popover"]').popover();
  $('[data-toggle="tooltip"]').tooltip();
  
  $('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.danger').attr('href', $(e.relatedTarget).data('href'));
  });
  
  $('#confirm-create-event').on('show.bs.modal', function(e) {
    $(this).find('.success').attr('href', $(e.relatedTarget).data('href'));
  });

  $('#my-tab-content').tab();
  
   $('.fancybox').fancybox({
		type:'image',
		openEffect : 'elastic',
		closeEffect : 'elastic',
		helpers : {
			title : {
				type : 'float'
			}
		}
	});
	
	$("#addlink").click(function(){
		addLinkRow();
	});
	
	updateLinkDeleteHandlers();

	function addLinkRow() {
		$('#links tbody').append('<tr> \
											<td width="30%"> \
												<input name="link-name[]" type="text" class="form-control" \
												placeholder="The name of the link. E.g.: www.gibm.ch" \
												pattern=".{0,50}" maxlength="50" title="Maximum 50 characters"> \
											</td> \
											<td width="70%" class="margin-right"> \
												<input name="link-url[]" type="url" class="form-control" \
												placeholder="The URL of the link. E.g.: http://www.gibm.ch" \
												required="required" pattern=".{5,255}" maxlength="255" title="A valid URL"> \
											</td> \
											<td> \
												<a class="delete-link" title="Delete this link"> \
													<span class="glyphicon glyphicon-trash"></span> \
												</a> \
											</td> \
										</tr>');
		updateLinkDeleteHandlers();
	}
	
	function deleteLinkRow(sender) {
		// TODO: Display name (if available) or url of link the user is about to delete
		if (confirm('Do you really want to delete this link?')) {
			var row = sender.parents('tr').first();
			row.remove();
			updateLinkDeleteHandlers();
		}	
	}
	
	function updateLinkDeleteHandlers() {
		$('.delete-link').off('click');
		
		$('.delete-link').on('click', function () {
			deleteLinkRow($(this));
		});
	}
	
 })();
 