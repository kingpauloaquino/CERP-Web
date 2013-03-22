<div id="logo">
	<center><a href="#"><img src="../images/cresc-logo.jpeg" alt="Cresc" width="160" /></a></center>
	<h4 class="text">Hi, <?php echo $Signed['first_name']; ?></h4>
	<p><a href="#">My Account</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/<?php echo $Host; ?>?signout=true">Sign Out</a></p>
</div>
<div id="main-menu">
	<ul>
	<?php
	  foreach ($Menu->Main() as $key => $value) {
	    $action = $Capabilities->All[$key];
		$active = ($Capabilities->GetParent() == $key) ? 'active' : null;
		
	    $menu = '<li>';
	    $menu .= '<a href="'. host($action['url']) .'" class="'. $active .'">'. $value .'</a>';
		
		$submenu = $Menu->Childrens($key);
		
		if(isset($active) && !empty($submenu)) {
		  $menu .= '<ul>';
		  foreach ($submenu as $key => $value) {
	        $action = $Capabilities->All[$key];
		    $menu .= '<li><a href="'.host($action['url']).'">'.$value.'</a></li>';
		  }
	      $menu .= '</ul>';
		}
		
	    $menu .= '</li>';
		echo $menu;
	  } 
	?>
	</ul>
</div>