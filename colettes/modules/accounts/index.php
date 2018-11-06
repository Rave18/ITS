<?php
	if($access->level <= 1){
		echo '<script type = "text/javascript">alert("Restricted."); window.location.replace("/'.$pagename.'");</script>';
	}
?>
<?php
	echo '<script type = "text/javascript">window.location.replace("/'.$pagename.'/'.$_GET['module'].'/userlist");</script>';