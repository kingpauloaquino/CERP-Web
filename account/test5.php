<?php
  /* Module: Name  */
  $capability_key = 'key';
  require('header.php');
	
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
      <form id="form-name" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
      	<a id="a1" href="#">div1</a>|<a id="a2" href="#">div2</a>|<a id="a3" href="#">div3</a><br/>
      	
      	<div id="grid1" class="grid jq-grid" style="min-height:140px;">
      		<h3>grid1</h3>
	        <table id="tbl1" cellspacing="0" cellpadding="0" >
	          <thead>
	            <tr>
	              <td class="border-right"><a class="sort default active up" column="code">Model</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="series">Series</a></td>
	            </tr>
	          </thead>
	          <tbody>
	          	<tr>
	          		<td><a href="#">test1</a></td>
	          		<td>test2</td>
	          	</tr>
	          </tbody>
	        </table>
	      </div>
	      
	      <div id="grid2" class="grid jq-grid" style="min-height:140px; display:none">
      		<h3>grid2</h3>
	        <table id="tbl2" cellspacing="0" cellpadding="0" >
	          <thead>
	            <tr>
	              <td class="border-right"><a class="sort default active up" column="code">Model</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="series">Series</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="series">Series</a></td>
	            </tr>
	          </thead>
	          <tbody>
	          	<tr>
	          		<td><a href="#">test1</a></td>
	          		<td>test2</td>
	          		<td>test2</td>
	          	</tr>
	          	<tr>
	          		<td><a href="#">test1</a></td>
	          		<td>test2</td>
	          		<td>test2</td>
	          	</tr>
	          	<tr>
	          		<td><a href="#">test1</a></td>
	          		<td>test2</td>
	          		<td>test2</td>
	          	</tr>
	          	<tr>
	          		<td><a href="#">test1</a></td>
	          		<td>test2</td>
	          		<td>test2</td>
	          	</tr>
	          	<tr>
	          		<td><a href="#">test1</a></td>
	          		<td>test2</td>
	          		<td>test2</td>
	          	</tr>
	          </tbody>
	        </table>
	      </div>
	      
	      <div id="grid3" class="grid jq-grid" style="min-height:140px; display:none">
      		<h3>grid3</h3>
	        <table id="tbl3" cellspacing="0" cellpadding="0" >
	          <thead>
	            <tr>
	              <td class="border-right"><a class="sort default active up" column="code">Model</a></td>
	              <td class="border-right text-center" width="90"><a class="sort" column="series">Series</a></td>
	            </tr>
	          </thead>
	          <tbody>
	          	<tr>
	          		<td><a href="#">test1</a></td>
	          		<td>test2</td>
	          	</tr>
	          	<tr>
	          		<td><a href="#">test1</a></td>
	          		<td>test2</td>
	          	</tr>
	          	<tr>
	          		<td><a href="#">test1</a></td>
	          		<td>test2</td>
	          	</tr>
	          </tbody>
	        </table>
	      </div>
      </form>
      
			<div>
	      <div id="slideleft" class="slide" style="height: 122px; width: 352px; background: #aaa">
				  <button>slide it</button>
				  <div class="inner" style="left: 0px; background: #ddd">Slide from bottom</div>
				</div>	
      </div>	
      
		</div>
	</div>
	
	<style>
		.slide {
  		position: relative;
		}
		.slide .inner {
			position: absolute;
		  left: 0;
		  bottom: 0;
		}
	</style>
	<script>
		$(document).ready(function() {
		  $('#slideleft button').click(function() {
		    var $lefty = $(this).next();
		    $lefty.animate({left: parseInt($lefty.css('left'),10) == 0 ? -$lefty.outerWidth() : 0});
		  });
		  
		  
		  $('#a1').live('click', function(){  
		  	$('#grid2').fadeOut('fast', function(){
		  		$('#grid1').fadeIn('fast', function(){})
		  		$('#grid3').hide();
		  	})
		  })
		  
		  $('#a2').live('click', function(){  
		  	$('#grid1').fadeOut('fast', function(){
		  		$('#grid2').fadeIn('fast', function(){})
		  		$('#grid3').hide();
		  	})
		  })
		  
		  $('#a3').live('click', function(){  
		  	$('#grid2').fadeOut('fast', function(){
		  		$('#grid3').fadeIn('fast', function(){})
		  		$('#grid1').hide();
		  	})
		  })
		  
		});
		
		
		
	</script>

<?php 
require('footer.php'); ?>