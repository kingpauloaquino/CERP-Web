<?php
  /* Module: Name  */
  $capability_key = 'key';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
		
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
      <form id="form-name" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
      	<input type="text" class="date-pick-thursday" readonly/>
      	<input type="text" class="date-pick-friday" readonly/>
      </form>
		</div>
	</div> 
<?php 
require('footer.php'); ?>