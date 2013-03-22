<?php
  /*
   * Module: Manufacturing 
  */
  $capability_key = 'manufacturing';
  require('header.php');
	
 
	
?>
	<script type="text/javascript">
	$(document).ready(function(){
 		$.modal.defaults = {
	  overlay: "#111",        // Overlay color
	  opacity: 0.2,          // Overlay opacity
	  zIndex: 1,              // Overlay z-index.
	  escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
	  clickClose: false,       // Allows the user to close the modal by clicking the overlay
	  closeText: 'Close',     // Text content for the close <a> tag.
	  showClose: true,        // Shows a (X) icon/link in the top-right corner
	  modalClass: "modal",    // CSS class added to the element being displayed in the modal.
	  //spinnerHtml: null,      // HTML appended to the default spinner during AJAX requests.
	  showSpinner: true       // Enable/disable the default spinner during AJAX requests.
		};
		$('#l1').click(function(event) {
		  event.preventDefault();
		  $(this).modal();
		});
		
		$('#tb1').datetimepicker();
});
		 
		
	</script>
	<div id="page"> 
				
		<div id="content">
			<div id="title">
				<h1><?php echo $Capabilities->GetName(); ?></h1>
				</div>
		    <div class="clear"></div>
		    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dapibus molestie fermentum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas</p>
					
			<input type="text" id="tb1"/>
				  <div id="ex1" style="display:none;">
				    <p>Thanks for clicking.  That felt good.</p>
				    <p>  <a href="#" rel="modal:close">Close</a> or press ESC</p>
				    <p>testest</p>
				  </div>
				
				  <!-- Link to open the modal -->
				  <p><a href="#ex1" rel="modal:open">Open Modal</a></p>
				  
				  <a href="#ex5"> Open modal by getting the dom id from href</a>
					<a id="l1" href="remove.php?mid=359"> Open modal by making an AJAX call</a>
					<br/>
			<?php
				// $querystring = explode('&','iid=1803&mid=780');
				// $items = explode( ',', 'iid,mid');   
				// $newqs = array();
				// foreach ($items as $itm) {
					// foreach ($querystring as $qs) {
					 	// if(strpos($qs, $itm)!==FALSE) {
					 		// echo $qs.'<br/>';
							// $newqs[] = $qs;
					 	// }				
					// }
				// }  
				// $x = implode("&", $newqs);
				// var_dump($x).'<br/>';
// 				
// 				
				// var_dump(md5('7250100ASSEMBLED IN PHILIPPINE LABEL')).'<br/>';
				// var_dump(md5('7350100ASSEMBLED IN PHILIPPINE LABEL')).'<br/>';
			?>


			</div>
	</div>

<?php require('footer.php'); ?>