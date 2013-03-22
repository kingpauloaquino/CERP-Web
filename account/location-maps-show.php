<?php
  /*
   * Module: Location Maps - Show
  */
  $capability_key = 'show_location_maps';
  require('header.php');
	
	if(isset($_REQUEST['search'])){
		$cur_search = trim($_REQUEST['search']);
		$cur_filter = $_REQUEST['filter'];
	}
?>
	<div id="page">
		 <div id="page-title">
        <h2>
          <span class="title"><?php echo $Capabilities->GetName(); ?></span>
          <?php
				  	//echo '<a href="'.$Capabilities->All['add_location']['url'].'" class="nav">'.$Capabilities->All['add_location']['name'].'</a>'; 
					?>
          <div class="clear"></div>
        </h2>
      </div>
				
		<div id="content">
			
		</div>
	</div>

<?php require('footer.php'); ?>