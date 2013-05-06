<?php
  /*
   * Module: Locations
  */
  $capability_key = 'locations';  
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['add_location']['url'].'" class="nav">'.$Capabilities->All['add_location']['name'].'</a>'; 
				  
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<!-- BOF Search -->
      <div class="search">
        <input type="text" id="keyword" name="keyword" class="keyword" placeholder="Search" />
      </div>
        
      <!-- BOF GridView -->
      <div id="grid-locations" class="grid jq-grid" style="min-height:400px;">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td width="110" class="border-right text-center"><a class="sort default active up" code="address">Address</a></td>
              <td width="200" class="border-right text-center"><a class="sort" code="item">Item</a></td>
              <td width="100" class="border-right text-center"><a class="sort" code="bldg">Building</a></td>
              <td class="border-right text-center"><a class="sort" code="description">Description</a></td>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="locations-pagination"></div>
		</div>
	</div>
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/locations.php",
      "limit":"15",
			"data_key":"location_addresses",
			"row_template":"row_template_locations",
      "pagination":"#locations-pagination",
      "searchable":true
		}
	
		$('#grid-locations').grid(data);
  }) 
 </script>

<?php }
require('footer.php'); ?>