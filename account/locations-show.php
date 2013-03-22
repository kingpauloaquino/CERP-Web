<?php
  /*
   * Module: Locations - Show
  */
  $capability_key = 'show_location';
  require('header.php');
	
	if(isset($_REQUEST['lid'])) {
  	$location = $DB->Find('location_addresses', array(
  		'columns' => 'location_addresses.id AS loc_id, locations1.location_code AS bldg, location_addresses.address,
										item_classifications.classification, locations2.location AS deck, locations3.location AS area, 
										terminals.terminal_code,terminals.id AS tid, terminals.terminal_name, location_addresses.rack, location_addresses.number, location_addresses.description', 
			'joins'		=>	'LEFT OUTER JOIN locations AS locations1 ON locations1.id = location_addresses.bldg 
										LEFT OUTER JOIN locations AS locations2 ON locations2.id = location_addresses.deck
										LEFT OUTER JOIN locations AS locations3 ON locations3.id = location_addresses.area	
										LEFT OUTER JOIN terminals ON terminals.id = location_addresses.terminal_id
										LEFT OUTER JOIN item_classifications ON item_classifications.id = location_addresses.item_classification',
  	  'conditions' => 'location_addresses.id = '.$_REQUEST['lid']
  	  ));
			$item = $DB->Find('location_address_items', array(
					  			'columns' 		=> 'location_address_items.id, materials.id AS mat_id, materials.material_code AS item_code', 
					  			'joins'				=> 'INNER JOIN materials ON materials.id = location_address_items.item_id',
					  	    'conditions' 	=> 'location_address_items.item_type="MAT" AND location_address_items.address = '.$_REQUEST['lid']
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
				  echo '<a href="'.$Capabilities->All['edit_location']['url'].'?lid='.$_REQUEST['lid'].'" class="nav">'.$Capabilities->All['edit_location']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container">
        <h3 class="form-title">Basic Information</h3>
        
        <div class="field">
          <label class="label">Current:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $location['address'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Building:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $location['bldg'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Terminal:</label>
          <div class="input">          	
            <input  type="text" name="name" value="<?php echo $location['terminal_code'] ?>" readonly="readonly"/>
          	<?php echo $linkto = ($location['terminal_code']!='') ? link_to('terminals-show.php?tid='.$location['tid']) : '' ?>          	
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Classification [Rack]:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $location['classification'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Deck:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $location['deck'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <!-- <div class="field">
          <label class="label">Area:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $location['area'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div> -->
        
        <div class="field">
          <label class="label">Rack:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $location['rack'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Number:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $location['number'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Assigned Item:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $item['item_code'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Description:</label>
          <div class="input">
            <textarea readonly="readonly"><?php echo $location['description'] ?></textarea>
          </div>
          <div class="clear"></div>
        </div>
        
      </form>
		</div>
	</div>

<?php require('footer.php'); ?>