<?php
  /*
   * Module: Reports 
  */
  $capability_key = 'reports';
  require('header.php');
?>

	<div id="page">
		<div id="side-bar">
			<?php require('sidebar.php'); ?>
		</div>
				
		<div id="content">
			<div id="title">
				<h1><?php echo $Capabilities->GetName(); ?></h1>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dapibus molestie fermentum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas</p>
			</div>
		</div>
	</div>

<?php require('footer.php'); ?>