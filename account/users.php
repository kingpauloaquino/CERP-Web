<?php
  /*
   * Module: Users 
  */
  $capability_key = 'users';  
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
				  echo '<a href="'.$Capabilities->All['add_user']['url'].'" class="nav">'.$Capabilities->All['add_user']['name'].'</a>';
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
      <div id="grid-users" class="grid jq-grid" style="min-height:400px;">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td class="border-right text-center" width="110"><a class="sort default active up" column="employee_id">Employee ID</a></td>
              <td class="border-right text-center"><a class="sort" column="name">Name</a></td>
              <td class="border-right text-center" width="140"><a class="sort" column="role">Role</a></td>
              <td class="border-right text-center" width="100"><a class="sort" column="status">Status</a></td>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="users-pagination"></div>
		</div>
	</div>
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/users.php",
      "limit":"15",
			"data_key":"users",
			"row_template":"row_template_users",
      "pagination":"#users-pagination",
      "searchable":true
		}
	
		$('#grid-users').grid(data);
  }) 
 </script>

<?php }
require('footer.php'); ?>