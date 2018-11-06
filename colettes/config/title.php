<?php
	$page = array(
				"member"			=>			"New Member Application",
				);

	foreach($page as $x => $tag) {
	    if(isset($_GET['action']) && $_GET['action'] == $x){
			$title = $tag;
	    }elseif(isset($_GET['module']) && $_GET['module'] == $x){
			$title = $tag;
	    }elseif(isset($_SESSION['insta_acc']) && isset($_GET['module']) != $x){
			$title = "Dashboard";
		}elseif(!isset($_SESSION['insta_acc'])){
			$title = "Login Page";
		}
	}
?>