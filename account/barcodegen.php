<?php
  /* Module: Name  */
  $capability_key = 'show_barcode_generator';
  require('header.php');
	
	$allowed = $Role->isCapableByName($capability_key);	
	if(!$allowed) {
		require('inaccessible.php');	
	}else{
		
?>
	<!-- BOF PAGE -->
	<div id="page">
		<div id="page-title">
    	<h2>
      	<span class="title">Barcode Generator</span>
				<div class="clear"></div>
      </h2>
		</div>

    <div id="content">
			<form id="generator-form" action="<?php host($Capabilities->GetUrl()) ?>" method="POST">
	      <div class="form-container">
					<table>
						<tr>
							<td width="100">Data:</td><td width="240"><input id="data" name="data" type="text" class="text-field" placeholder="BARCODE" maxlength="30" />
							</td>
							<td width="100"></td><td></td>
						</tr>
						<tr>
							<td>Type:</td><td>
								<select size="1" id="filetype" name="filetype" style="width: 192px">
									<option value="PNG" selected="selected">PNG</option>
									<option value="JPEG">JPEG</option>
									<option value="GIF">GIF</option>
								</select>
							</td>
							<td>Rotation:</td><td>
								<select size="1" id="rotation" name="rotation" style="width: 192px">
									<option value="0" selected="selected">No rotation</option>
									<option value="90">90° clockwise</option>
									<option value="180">180° clockwise</option>
									<option value="270">270° clockwise</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>DPI:</td><td><input id="dpi" name="dpi" type="number" class="text-field text-right" min="1" max="300" value="72" />
								&nbsp;<a href="#" title="DPI range is from 1 to 300." class="tooltip"><span title="More">info</span></a>
							</td>
							<td>Thickness:</td><td><input id="thickness" name="thickness" type="number" class="text-field text-right" min="20" max="90" value="30"/>
								&nbsp;<a href="#" title="Thickness range is from 1 to 90." class="tooltip"><span title="More">info</span></a>
							</td>
						</tr>
						<tr>
							<td>Scale:</td><td><input id="scale" name="scale" type="number" class="text-field text-right" min="1" max="4" value="1"/>
								&nbsp;<a href="#" title="Scale range is from 1 to 4." class="tooltip"><span title="More">info</span></a>
							</td>
							<td>Fontsize:</td><td><input id="fontsize" name="fontsize" type="number" class="text-field text-right" min="1" max="30" value="8"/>
								&nbsp;<a href="#" title="Font-size range is from 1 to 30." class="tooltip"><span title="More">info</span></a>
							</td>
						</tr>
						<tr>
							<td>Barcode:</td>
							<td colspan="99"><div id="div-img"></div></td>
						</tr>
						<tr><td height="5" colspan="99"></td></tr>
					</table>
					
					<div class="field-command">
						<div class="text-post-status">
						   
						</div>
						<input id="btn-generate" type="button" value="Generate" class="btn"/>
					</div>	
		  	</div>
			</form>
		</div>
	</div>
	<script type="text/javascript">
		$(function() {
			$('#btn-generate').click(function(e) { 
				if($('#data').val() != '') {
					$("#div-img").empty();
					var t = $('#data').val();
					var typ = $('#filetype').val();
					var rot = $('#rotation').val();
					var dpi = $('#dpi').val();
					var thk = $('#thickness').val();
					var scl = $('#scale').val();
					var fsz = $('#fontsize').val();
					
					var img = $("<img id='img-code' />").attr('src', '../include/barcodephp/generator.php?t='+t+'&typ='+typ+'&rot='+rot+'&dpi='+dpi+'&thk='+thk+'&scl='+scl+'&fsz='+fsz)
				    .load(function() {
				        $("#div-img").append(img);
				    });	
				}
				
				
				//e.preventDefault();
				// var img = $("<img />").attr('src', '../include/barcodephp/generator.php?text=12345')
			    // .load(function() {
			        // $("#div-img").append(img);
			    // });
				
				
				// $.ajax({
					// type: "POST",
					// url: "../include/barcodephp/generator.php?text=12345",
					// data: {  
									// dpi : 72
								// },
					// cache: false,
					// dataType : "html",
					// success: function(data) {
						// $('#div-img').html('<img src="data:image/png;base64,' + data + '" />');
					// }
				// });
			});
		});
	</script>
<?php }
require('footer.php'); ?>