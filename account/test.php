<?php 
$capability_key = 'show_material'; 
require('header.php');	

$allowed = $Role->isCapableByName('show_material');

if(!$allowed) {
	require('inaccessible.php');	
}else{
?>
	<div id="page">
  	<div class="breadcrumb">
  		<a href="#">Parent</a> &raquo; Current
  	</div>
		<div id="page-title">
    	<h2>
      	<span class="title">Title</span>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
					<h2>usual content</h2>
					<span class="notice">
	          <p class="error"><strong>Notice!</strong> Material codes should be unique.</p>
	        </span>
					<?php
					echo generate_new_code('purchase_number');

					
					?>
		</div>
	</div>
	
<?php }
require('footer.php'); ?>