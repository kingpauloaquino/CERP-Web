<?php 
$capability_key = 'show_material'; 
require('header.php');	

$allowed = $Role->isCapableByName('show_material');

if(!$allowed) {
	require('inaccessible.php');	
}else{
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"></span>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
					<h2>usual content</h2>
		</div>
	</div>
<?php } ?>
<?php require('footer.php'); ?>