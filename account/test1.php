<?php 
require('header.php');	
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title">TEST</span>

				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<?php
				echo 'role: '.$Role->get_roles();				
			?>
		</div>
	</div>
<?php require('footer.php'); ?>