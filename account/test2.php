<?php


// if ($handle = opendir('/')) {
    // echo "Directory handle: $handle\n";
    // echo "Entries:\n";
// 
    // /* This is the correct way to loop over the directory. */
    // while (false !== ($entry = readdir($handle))) {
        // echo "$entry<br/>";
    // }
// 
    // /* This is the WRONG way to loop over the directory. */
    // while ($entry = readdir($handle)) {
        // echo "$entry\n";
    // }
// 
    // closedir($handle);
// }

// $con = mysql_connect("localhost","root","");
// if (!$con)
  // {
  // die('Could not connect: ' . mysql_error());
  // }
// 
// mysql_select_db("cerpdb", $con);
// 
// for($i=1; $i<=4; $i++) {
	// mysql_query("INSERT INTO location_addresses (bldg, bldg_no, item_classification, rack, number, address, description, terminal_id, created_at)
								// VALUES (2, 37, 0, 'P', ".$i.", 'WH1-B2-P".sprintf( '%03d', $i)."', 'New Building', 26, '2012-10-13 15:46:04')");
// }
// echo 'done';
// mysql_close($con);
$capability_key = 'key';
  require('header.php');
?>
<script>
$(function() {
    $( "#tabs-left" ).tabs({
      collapsible: false});
  });
$(function() {
    $( "#tabs" ).tabs();
  });
  
$(function() {
	$('.ui-tabs-nav').find('.week-link').click(function(){
		alert($(this).attr('href'));
	})
})
</script>

<style>
	#tabs-left { 
    position: relative; 
    padding-left: 8.5em; 
	    height: auto; 
	} 
	#tabs-left .ui-tabs-nav { 
	    position: absolute; 
	    left: 0.25em; 
	    top: 0.25em; 
	    bottom: 0.25em; 
	    width: 8em; 
	    padding: 0.2em 0 0.2em 0.2em; 
	} 
	#tabs-left .ui-tabs-nav li { 
	    right: 1px; 
	    width: 100%; 
	    border-right: none; 
	    border-bottom-width: 1px !important; 
	    -moz-border-radius: 4px 0px 0px 4px; 
	    -webkit-border-radius: 4px 0px 0px 4px; 
	    border-radius: 4px 0px 0px 4px; 
	    overflow: hidden; 
/*	    margin: 0.1em*/
	} 
	#tabs-left .ui-tabs-nav li.ui-tabs-selected, 
	#tabs-left .ui-tabs-nav li.ui-state-active { 
	    border-right: 1px solid transparent; 
	} 
	#tabs-left .ui-tabs-nav li a { 
	    float: right; 
	    width: 100%; 
	    text-align: center; 
	} 
	#tabs-left > div { 
	    height: 15em; 
	}
</style>

<div id="page">
	<div id="page-title">
  	<h2>
    	<span class="title"><?php echo $Capabilities->GetTitle(); ?></span>
			<div class="clear"></div>
    </h2>
	</div>

  <div id="content">
    <form id="form-name" action="<?php host($Capabilities->GetUrl()) ?>" method="POST" class="form-container">
    	<div id="tabs-left">
			  <ul class="ui-tabs-nav">
			    <li><a href="#tabs-left-1" class="week-link">Week 1</a></li>
			    <li><a href="#tabs-left-2" class="week-link">Week 2</a></li>
			    <li><a href="#tabs-left-3" class="week-link">Week 3</a></li>
			  </ul>
			  <div id="tabs-left-1">
			    <h2>Content heading 1</h2>
			    <p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
			  </div>
			  <div id="tabs-left-2">
			    <h2>Content heading 2</h2>
			    <p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
			  </div>
			  <div id="tabs-left-3">
			    <h2>Content heading 3</h2>
			    <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
			    <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
			  </div>
			</div>
			
			<div id="tabs">
			  <ul class="ui-tabs-nav">
			    <li><a href="#tabs-1">Nunc tincidunt</a></li>
			    <li><a href="#tabs-2">Proin dolor</a></li>
			    <li><a href="#tabs-3">Aenean lacinia</a></li>
			  </ul>
			  <div id="tabs-1">
			    <h2>Content heading 1</h2>
			    <p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
			  </div>
			  <div id="tabs-2">
			    <h2>Content heading 2</h2>
			    <p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
			  </div>
			  <div id="tabs-3">
			    <h2>Content heading 3</h2>
			    <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
			    <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
			  </div>
			</div>
    </form>
	</div>
</div>

<?php
require('footer.php'); ?>


