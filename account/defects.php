<?php
  /*
   * Module: Defects 
  */
  $capability_key = 'defects';  
  require('header.php');
?>
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>

				<div class="clear"></div>
      </h2>
		</div>
				
		<div id="content">
			<!-- BOF Search -->
      <div class="search">
        <input type="text" id="keyword" name="keyword" placeholder="Search" />
      </div>
        
      <!-- BOF GridView -->
      <div id="grid-defects" class="grid jq-grid" style="min-height:400px;">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
							<td class="border-right text-center" width="80"><a class="sort default active up" column="type">Type</a></td>
              <td class="border-right text-center" width="280"><a class="sort down" column="defect">Defect</a></td>
              <td class="border-right text-center"><a class="sort" column="model">Model</a></td>  
              <td class="border-right text-center" width="100"><a class="sort" column="line">Line</a></td>  
              <td class="border-right text-center" width="90"><a class="sort" column="category">Category</a></td>  
              <td class="border-right text-center" width="60"><a class="sort" column="location">Location</a></td>   
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      
      <!-- BOF Pagination -->
      <div id="defects-pagination"></div>
		</div>
	</div>
<script>
	$(function() {
  	var data = { 
    	"url":"/populate/defects.php",
      "limit":"15",
			"data_key":"defects",
			"row_template":"row_template_defects",
      "pagination":"#defects-pagination",
      "searchable":true
		}
	
		$('#grid-defects').grid(data);
  }) 
 </script>

<?php require('footer.php'); ?>