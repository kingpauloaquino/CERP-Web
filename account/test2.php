<?php

if ($handle = opendir('/')) {
    echo "Directory handle: $handle\n";
    echo "Entries:\n";

    /* This is the correct way to loop over the directory. */
    while (false !== ($entry = readdir($handle))) {
        echo "$entry<br/>";
    }

    /* This is the WRONG way to loop over the directory. */
    while ($entry = readdir($handle)) {
        echo "$entry\n";
    }

    closedir($handle);
}

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
?>