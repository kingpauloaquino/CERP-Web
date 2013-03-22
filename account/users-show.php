<?php
  /*
   * Module: Users 
  */
  $capability_key = 'show_user';
  require('header.php');
  
  if(isset($_REQUEST['uid'])) {
  	$user = $DB->Find('users', array(
			'columns' 		=> 'users.*, lookups.description AS status, roles.name as role', 
			'joins'				=> 'LEFT OUTER JOIN lookups on users.status = lookups.id 
												LEFT OUTER JOIN roles on users.role = roles.id',
  	  'conditions' 	=> 'users.id = '.$_REQUEST['uid']
  	  )
		);	
  }
?>

	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetName(); ?></span>
        <?php
				  echo '<a href="'.$Capabilities->All['users']['url'].'" class="nav">'.$Capabilities->All['users']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['add_user']['url'].'" class="nav">'.$Capabilities->All['add_user']['name'].'</a>'; 
				  echo '<a href="'.$Capabilities->All['edit_user']['url'].'?uid='.$_REQUEST['uid'].'" class="nav">'.$Capabilities->All['edit_user']['name'].'</a>'; 
				?>
				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<form class="form-container">
        <h3 class="form-title">Basic Information</h3>
        
        <div class="field">
          <label class="label">Employee ID:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $user['employee_id'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Firstname:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $user['first_name'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Lastname:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $user['last_name'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Email:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $user['email'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Position:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $user['position'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <div class="field">
          <label class="label">Status:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $user['status'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
        
        <br/>
        <h3 class="form-title">Security Information</h3>
        
        <div class="field">
          <label class="label">Role:</label>
          <div class="input">
            <input type="text" name="name" value="<?php echo $user['role'] ?>" readonly="readonly"/>
          </div>
          <div class="clear"></div>
        </div>
      </form>
	</div>
</div>

<?php require('footer.php'); ?>