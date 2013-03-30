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
      	<span class="title">TITLE:<?php var_dump($Capabilities->All['users']) ?></span>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
					<h2>usual content</h2>
					<?php
						$capabilities = $DB->Get('capabilities', array(
									  			'columns' 		=> 'id, capability, title, parent',
									  	    'conditions' 	=> 'parent IS NOT null ORDER BY parent'));
						
						$all = array();																		
						foreach ($capabilities as $cap) {
							array_push($all, array($cap['capability'] => array('name' => $cap['title'], 'url' => $cap['url'], 'parent' => $cap['parent'])));
						}
					?>
		</div>
	</div>
	
<?php }
require('footer.php'); ?>