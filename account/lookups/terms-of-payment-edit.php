<h3><a href="#">Terms of Payment</a></h3>
<div>
  <?php
  	$trmpays = $DB->Get('lookups', array('columns' => 'lookups.*', 'conditions' => 'parent="'.get_lookup_code("term_of_payment").'"')); //function get_lookup_code @ /include/functions.php
  	$ctr = 1;
  	foreach($trmpays as $trmpay)
		{
			echo '<div class="field">';
      echo '<label class="label">'.$ctr.'.</label>';
      echo '<div class="input">';
      echo '<input type="text" value="'.$trmpay['description'].'" class="w320"/>';
      echo '</div>';
      echo '<div class="clear"></div>';
      echo '</div>';
			$ctr+=1;
		}
  ?>
</div>