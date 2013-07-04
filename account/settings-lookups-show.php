<?php
  /* Module: Name  */
  $capability_key = 'show_lookups_settings';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		$parents = $Query->get_lookup_parents();
		
?>
	<!-- BOF PAGE -->
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
				<div class="clear"></div>
      </h2>
		</div>

    <div id="content">
    	<div  class="form-container" style="float: left">
    		<ul>
    			<?php
    				foreach ($parents as $parent) {
							echo '<li><a class="nav-lookup" code="'.$parent['code'].'" title="'.$parent['description'].'" href="#">'.$parent['description'].'</a></li>';
						}
    			?>
    		</ul>
    	</div>
			<div style="float:left; margin-left: 10px;">
				<div class="form-container">
					<h3 id="title-lookup" class="form-title">Lookups</h3>
					<input type="hidden" id="code-lookup"/>
					<table id="table-lookup">
						<tr>
							<td width="300"></td>
							<td width="40"></td>
							<td width="40"></td>
						</tr>
					</table>
				</div> 
			</div>
		</div>
	</div>
	
	<a id="btn-edit-modal" href="#mdl-lookups" rel="modal:open"></a>	
	<div id="mdl-lookups" class="modal">
		<div class="modal-title"><h3></h3></div>
		<div class="modal-content" style="min-height:50px">
			<form id="frm-lookups" method="POST">
				<input type="hidden" name="action" value="edit_lookup"/>
				<input type="hidden" id="lookup-id" name="lookup-id"/>
				<span class="notice"></span>     
					
				<div class="field">
				<label>Title:</label>
				<input type="text" id="lookup-title" name="lookup-title" class="text-field" required/>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<a id="closeModal" rel="modal:close" class="close btn">Cancel</a>
			<a id="submit-lookups" rel="modal:close" href="#frm-lookups" class="btn" style="width:50px;">Save</a>
		</div>
	</div>
	
	<a id="btn-remove-modal" href="#mdl-lookups-remove" rel="modal:open"></a>	
	<div id="mdl-lookups-remove" class="modal">
		<div class="modal-title"><h3></h3></div>
		<div class="modal-content" style="min-height:50px">
			<form id="frm-lookups-remove" method="POST">
				<input type="hidden" name="action" value="remove_lookup"/>
				<input type="hidden" id="lookup-id-remove" name="lookup-id-remove"/>
				<span class="notice"></span>     
					
				<div class="field">
				<label>Title:</label>
				<input type="text" id="lookup-title-remove" name="lookup-title-remove" class="text-field" readonly/>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<a id="closeModal" rel="modal:close" class="close btn">Cancel</a>
			<a id="submit-lookups-remove" rel="modal:close" href="#frm-lookups-remove" class="btn" style="width:50px;">Remove</a>
		</div>
	</div>
	
<script>
	$(function() {
		$('.nav-lookup').live('click', function(){
			$('#title-lookup').text($(this).attr('title'));
			$('#code-lookup').val($(this).attr('code'));
			getLookups($(this).attr('code'));
		})
		
		$('#table-lookup tr').find('.edit-lookup').edit_lookup_modal();
		$('#table-lookup tr').find('.remove-lookup').remove_lookup_modal();
		$('#submit-lookups').update_lookup();
		$('#submit-lookups-remove').remove_lookup();
  }) 
  
  function getLookups(lcode) {
		$.ajax({
	    url: "http://"+ location.hostname +"/cerp/populate/lookups.php",
	    dataType: "json",
	    data: { code : lcode },
	    success: function(data) {
  			var table = $('#table-lookup');
  			table.empty();
	       $.each(data['lookups'], function(x, y) {
  				var row = $('<tr></tr>');
					row.append('<td width="300"><input type="text" value="'+ y['description'] +'" class="text-field-max" readonly/></td>');
				  row.append('<td width="40" class="text-center"><a class="edit-lookup" lookuptitle="'+ y['description'] +'" lookupid="'+y['id']+'" href="#">EDIT</a>&nbsp;|</td>');
				  row.append('<td width="40" class="text-center"><a class="remove-lookup" lookuptitle="'+ y['description'] +'" lookupid="'+y['id']+'" href="#">REMOVE</a></td>');
    			table.append(row);
	      });
	    },
	  }).done(function( html ) {
	  	
	  })	
	}
	
	$.fn.edit_lookup_modal = function() {
  	$()
    this.live('click', function(e) {
    	var modal = $('#btn-edit-modal').attr('href');
    	$(modal).find('#lookup-id').val($(this).attr('lookupid'));
    	$(modal).find('#lookup-title').val($(this).attr('lookuptitle'));
    	$(modal).find('.modal-title h3').text('UPDATE  '+ $('#title-lookup').text());
    	
	    $('#btn-edit-modal').click();	
    })
  }
  
  $.fn.remove_lookup_modal = function() {
  	$()
    this.live('click', function(e) {
    	var modal = $('#btn-remove-modal').attr('href');
    	$(modal).find('#lookup-id-remove').val($(this).attr('lookupid'));
    	$(modal).find('#lookup-title-remove').val($(this).attr('lookuptitle'));
    	$(modal).find('.modal-title h3').text('REMOVE  '+ $('#title-lookup').text());
    	
	    $('#btn-remove-modal').click();	
    })
  }
  
  $.fn.update_lookup = function() {
  	this.click(function(e) {
  		e.preventDefault();
  		
			var form = $(this).attr('href');
			
			if($(form).find('#lookup-title').val() != '') {
				$.post(document.URL, $(form).serialize(), function(data) {
	      }).done(function(data){
	      	getLookups($('#code-lookup').val());
	      });	
			}
  	})
  }
  
  $.fn.remove_lookup = function() {
  	this.click(function(e) {
  		e.preventDefault();
  		
			var form = $(this).attr('href');
			
			alert('DISABLED');
			// $.post(document.URL, $(form).serialize(), function(data) {
      // }).done(function(data){
      	// getLookups($('#code-lookup').val());
      // });	
  	})
  }
 </script>

<?php }
require('footer.php'); ?>