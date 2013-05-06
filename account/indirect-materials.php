<?php
  /*
   * Module: Indirect Materials 
  */
  $capability_key = 'indirect_materials';  
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
				  echo '<a href="'.$Capabilities->All['add_indirect_material']['url'].'" class="nav">'.$Capabilities->All['add_indirect_material']['name'].'</a>';
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
      <div id="grid-indirect-materials" class="grid jq-grid" style="min-height:400px;">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
							<td class="border-right text-center" width="130"><a class="sort default active up" column="code">Code</a></td>
              <td class="border-right text-center" width="130"><a class="sort" column="classification">Classification</a></td>   
              <td class="border-right text-center"><a class="sort" column="description">Description</a></td>  
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="indirect-materials-pagination"></div>
		</div>
	</div>
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/indirect-materials.php",
      "limit":"15",
			"data_key":"indirect_materials",
			"row_template":"row_template_indirect_materials",
      "pagination":"#indirect-materials-pagination",
      "searchable":true
		}
	
		$('#grid-indirect-materials').grid(data);
  }) 
 </script>

<?php }
require('footer.php'); ?>