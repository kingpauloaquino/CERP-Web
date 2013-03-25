<?php
  /*
   * Module: Locations - Show
  */
  $capability_key = 'show_location';
  require('header.php');
	
	if(isset($_GET['lid'])) {
  	$location = $DB->Find('location_addresses', array(
  		'columns' => 'location_addresses.id AS loc_id, locations1.location_code AS bldg, location_addresses.address,
										item_classifications.classification, locations2.location AS deck, locations3.location AS area, CONCAT(locations4.location_code, "-", locations4.description) AS bldg_no,
										terminals.terminal_code,terminals.id AS tid, terminals.terminal_name, location_addresses.rack, location_addresses.number, location_addresses.description', 
			'joins'		=>	'LEFT OUTER JOIN locations AS locations1 ON locations1.id = location_addresses.bldg 
										LEFT OUTER JOIN locations AS locations2 ON locations2.id = location_addresses.deck
										LEFT OUTER JOIN locations AS locations3 ON locations3.id = location_addresses.area	
										LEFT OUTER JOIN locations AS locations4 ON locations4.id = location_addresses.bldg_no	
										LEFT OUTER JOIN terminals ON terminals.id = location_addresses.terminal_id
										LEFT OUTER JOIN item_classifications ON item_classifications.id = location_addresses.item_classification',
  	  'conditions' => 'location_addresses.id = '.$_GET['lid']
  	  ));
			$item = $DB->Find('location_address_items', array(
					  			'columns' 		=> 'location_address_items.id, materials.id AS mat_id, materials.material_code AS item_code', 
					  			'joins'				=> 'INNER JOIN materials ON materials.id = location_address_items.item_id',
					  	    'conditions' 	=> 'location_address_items.item_type="MAT" AND location_address_items.address = '.$_GET['lid']
	  	  )
			);
	}
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['locations']['url'].'" class="nav">'.$Capabilities->All['locations']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['add_location']['url'].'" class="nav">'.$Capabilities->All['add_location']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['edit_location']['url'].'?lid='.$_GET['lid'].'" class="nav">'.$Capabilities->All['edit_location']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container">
				<h3 class="form-title">Details</h3>
        <table>
           <tr>
              <td width="150">Current:</td><td width="310"><input type="text" value="<?php echo $location['address'] ?>" class="text-field" disabled/></td>
              <td width="150">Building:</td><td><input type="text" value="<?php echo $location['bldg'] ?>" class="text-field" disabled/></td>
           </tr>
           <tr>
              <td>Building Type:</td><td><input type="text" value="<?php echo $location['bldg_no'] ?>" class="text-field" disabled/></td>
              <td>Terminal:</td><td><input type="text" value="<?php echo $location['terminal_code'] ?>" class="text-field" disabled/>
              	<?php echo $linkto = ($location['terminal_code']!='') ? link_to('terminals-show.php?tid='.$location['tid']) : '' ?>
              </td>
           </tr>
           <tr>
              <td>Classification [Rack]:</td><td><input type="text" value="<?php echo $location['classification'] ?>" class="text-field" disabled/></td>
              <td>Deck:</td><td><input type="text" value="<?php echo $location['deck'] ?>" class="text-field" disabled/></td>
           </tr>  
           <tr>
              <td>Rack:</td><td><input type="text" value="<?php echo $location['rack'] ?>" class="text-field" disabled/></td>
              <td>Number:</td><td><input type="text" value="<?php echo $location['number'] ?>" class="text-field" disabled/></td>
           </tr>             
           <tr>
              <td>Description:</td>
              <td colspan="99">
                <input type="text" value="<?php echo $location['description'] ?>" class="text-field" style="width:645px" disabled/>
              </td>
           </tr>  
           <tr>
              <td>Assigned Item:</td><td><input type="text" value="<?php echo $item['item_code'] ?>" class="text-field" disabled/>
              	<?php echo $linkto = ($item['item_code']!='') ? link_to('materials-show.php?mid='.$item['mat_id']) : '' ?>
              </td>
              <td></td><td></td>
           </tr>   
           <tr><td height="5" colspan="99"></td></tr>
        </table>				
        
      </form>
		</div>
	</div>

<?php require('footer.php'); ?>