<?php
  /* Module: Name  */
  $capability_key = 'key';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	// if(!$allowed) {
		// require('inaccessible.php');	
	// }else{
		
?>
	<!-- BOF PAGE -->
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
				<div class="clear"></div>
      </h2>
		</div>

    <div id="content">
    	<h2>Models Production Plan</h2>
    	<div id="accordion">
    		
      <h3 class="head" style="border-bottom: 1px solid #ccc">ECI-E31</h3>
      <div id="grid-suppliers" class="grid jq-grid" >
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td class="border-right text-center" width="110"><a class="sort default active up" column="code"></a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Jan</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Feb</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Mar</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Apr</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">May</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Jun</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Jul</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Aug</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Sep</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Oct</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Nov</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Dec</a></td>
            </tr>
          </thead>
          <tbody>
          	<tr>
          		<td class="border-right">ECI-E31B</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          	</tr>
          	<tr>
          		<td class="border-right">ECI-E31C</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          	</tr>
          	<tr>
          		<td class="border-right">ECI-E31M</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          	</tr>
          	<tr>
          		<td class="border-right">ECI-E31Y</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          	</tr>
          </tbody>
        </table>
      </div>	
      <h3 class="head" style="border-bottom: 1px solid #ccc">ECI-E32</h3>
      <div id="grid-suppliers" class="grid jq-grid" >
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td class="border-right text-center" width="110"><a class="sort default active up" column="code"></a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Jan</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Feb</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Mar</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Apr</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">May</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Jun</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Jul</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Aug</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Sep</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Oct</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Nov</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Dec</a></td>
            </tr>
          </thead>
          <tbody>
          	<tr>
          		<td class="border-right">ECI-E32B</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          	</tr>
          	<tr>
          		<td class="border-right">ECI-E32C</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          	</tr>
          	<tr>
          		<td class="border-right">ECI-E32M</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          	</tr>
          	<tr>
          		<td class="border-right">ECI-E32Y</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          	</tr>
          	<tr>
          		<td class="border-right">ECI-E32LC</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          	</tr>
          	<tr>
          		<td class="border-right">ECI-E32LM</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          	</tr>
          </tbody>
        </table>
      </div>
      <h3 class="head" style="border-bottom: 1px solid #ccc">ECI-E23</h3>
      <div id="grid-suppliers" class="grid jq-grid" >
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td class="border-right text-center" width="110"><a class="sort default active up" column="code"></a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Jan</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Feb</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Mar</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Apr</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">May</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Jun</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Jul</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Aug</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Sep</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Oct</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Nov</a></td>
              <td class="border-right text-center" width="70"><a class="sort" column="name">Dec</a></td>
            </tr>
          </thead>
          <tbody>
          	<tr>
          		<td class="border-right">ECI-E23B</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          	</tr>
          	<tr>
          		<td class="border-right">ECI-E23C</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          	</tr>
          	<tr>
          		<td class="border-right">ECI-E23M</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          	</tr>
          	<tr>
          		<td class="border-right">ECI-E23Y</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          	</tr>
          	<tr>
          		<td class="border-right">ECI-E23LC</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          	</tr>
          	<tr>
          		<td class="border-right">ECI-E23LM</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          		<td class="border-right text-center click">0</td>
          	</tr>
          </tbody>
        </table>
      </div>	
		</div>
  	</div>
	</div>
	
	<a id="btn-click" href="#modal-materials" class="" rel="modal:open"></a>
	<div id="modal-materials" class="modal" style="display:none;width:820px;">
		<div class="modal-title"><h3>MONTH NAME</h3></div>
		<div class="modal-content">
			<!-- BOF GRIDVIEW -->
			<div id="grid-materials" class="grid jq-grid grid-item">
				<table id="tbl-materials" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td class="border-right text-center" width="50"><a class="sort" column="unit">W31</a></td>
							<td class="border-right text-center" width="50"><a class="sort" column="unit">W32</a></td>
							<td class="border-right text-center" width="50"><a class="sort" column="unit">W33</a></td>
							<td class="border-right text-center" width="50"><a class="sort" column="unit">W34</a></td>
							<td class="border-right text-center" width="50"><a class="sort" column="unit">W35</a></td>
						</tr>
					</thead>
					<tbody id="materials">
						<tr>
							<td class="border-right text-center">8/2</td>
							<td class="border-right text-center">8/9</td>
							<td class="border-right text-center">8/16</td>
							<td class="border-right text-center">8/23</td>
							<td class="border-right text-center">8/30</td>
						</tr>
						<tr>
							<td class="border-right text-center">0</td>
							<td class="border-right text-center">0</td>
							<td class="border-right text-center">0</td>
							<td class="border-right text-center">0</td>
							<td class="border-right text-center">0</td>
						</tr>
					</tbody>
				</table>
			</div>
 
			<!-- BOF Pagination -->
			<div id="materials-pagination"></div>
		</div>

		<div class="modal-footer">
			<a class="btn parent-modal" rel="modal:close">Close</a>
			<div class="clear"></div>
		</div>
	</div>
	
<script type="text/javascript">
	$(function() {
    // $( "#accordion" ).accordion({
      // collapsible: true,
      // heightStyle: "content"
    // });
    
    $('#accordion .head').click(function() {
      $(this).next().toggle('slow');
      return false;
  }).next().hide();
    
    $('.click').click(function (){
    	$('#btn-click').click();
    })
  });
</script>
<?php //}
require('footer.php'); ?>