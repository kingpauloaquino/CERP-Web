<?php
  /*
   * Module: Roles 
  */
  $capability_key = 'roles';  
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
				  echo '<a href="'.$Capabilities->All['add_role']['url'].'" class="nav">'.$Capabilities->All['add_role']['name'].'</a>';
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
      <div id="grid-roles" class="grid jq-grid" style="min-height:400px;">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td class="border-right text-center" width="110"><a class="sort default active up" column="name">Title</a></td>
              <td class="border-right text-center" width="410"><a class="sort" column="description">Description</a></td>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="roles-pagination"></div>
		</div>
	</div>
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/roles.php",
      "limit":"15",
			"data_key":"roles",
			"row_template":"row_template_roles",
      "pagination":"#roles-pagination",
      "searchable":true
		}
	
		$('#grid-roles').grid(data);
  }) 
 </script>

<?php }
require('footer.php'); ?>