<?php
  /*
   * Module: Devices 
  */
  $capability_key = 'devices';  
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['add_device']['url'].'" class="nav">'.$Capabilities->All['add_device']['name'].'</a>';
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<!-- BOF Search -->
      <div class="search">
        <input type="text" id="keyword" name="keyword" placeholder="Search" />
      </div>
        
      <!-- BOF GridView -->
      <div id="grid-devices" class="grid jq-grid">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td class="border-right text-center" width="110"><a class="sort default active up" column="code">Code</a></td>
              <td class="border-right text-center" width="200"><a class="sort" column="make">Make</a></td>
              <td class="border-right text-center"><a class="sort" column="model">Model</a></td>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="devices-pagination"></div>
		</div>
	</div>
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/devices.php",
      "limit":"15",
			"data_key":"devices",
			"row_template":"row_template_devices",
      "pagination":"#devices-pagination"
		}
	
		$('#grid-devices').grid(data);
  }) 
 </script>

<?php }
require('footer.php'); ?>