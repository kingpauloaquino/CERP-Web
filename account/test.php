<?php 
$capability_key = 'users'; 
require('header.php');	
echo '<br/><br/><br/><br/><br/>';
var_dump($Role->getRoleCapabilityIDs(1));
$allowed = $Role->isCapableByName('add_user');
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title">TEST</span>
				 <?php
				 	if($allowed)
				  	echo '<a href="'.$Capabilities->All['add_user']['url'].'" class="nav">'.$Capabilities->All['add_user']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<?php
				if($allowed){
					?>
					<h2>usual content</h2>
					<?php
				}
				else {					
					?>
					<span class="notice">
		        <p class="info"><strong>Prohibited!</strong> You have no access.</p>
		      </span> 
					<?php
				}
			?>  
		</div>
	</div>
<?php require('footer.php'); ?>