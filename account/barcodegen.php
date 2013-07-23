<?php
  /* Module: Name  */
  $capability_key = 'key';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	// if(!$allowed) {
		// require('inaccessible.php');	
	// }else{
		
?>
	<!-- BOF PAGE -->
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title">Barcode Generator</span>
				<div class="clear"></div>
      </h2>
		</div>

    <div id="content">
      <form id="form-name" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
      	
      </form>
		</div>
	</div>

<?php 
//}
require('footer.php'); ?>