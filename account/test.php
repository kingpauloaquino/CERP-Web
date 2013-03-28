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
				//var_dump($Role->getRolePermissions(6));
				$Role->set_roles("role set");
				
			?>
			<a href="test1.php">check</a>
		</div>
	</div>
<?php require('footer.php'); ?>