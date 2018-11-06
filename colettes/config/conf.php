<?php
	$host = "127.0.0.1";
	$uname = "root";
	$pword = "";
	$db = "colletes";
	
	$conn = new mysqli($host, $uname, $pword, $db);
	$pagename = "colettes";
	if($conn->connect_error){
		die("Connection error:". $conn->connect_error);
	}
	function savelogs($transaction,$transdetails){
		$host = "127.0.0.1";
		$uname = "root";
		$pword = "";
		$db = "colletes";
		$conn = mysqli_connect($host, $uname, $pword, $db);
		if (mysqli_connect_errno()){
			die ('Unable to connect to database '. mysqli_connect_error());
		}
		$pcname = 	getenv('HTTP_CLIENT_IP')?:
					getenv('HTTP_X_FORWARDED_FOR')?:
					getenv('HTTP_X_FORWARDED')?:
					getenv('HTTP_FORWARDED_FOR')?:
					getenv('HTTP_FORWARDED')?:
					getenv('REMOTE_ADDR');
		
	    $username = $_SESSION['usernameinsta'];
		$realname = $_SESSION['nameinsta'];
	    $sqllogs = "insert into audit_trail(username,realname,transaction,datetrans,transdetail,pcname) values ('$username','$realname','$transaction',now(),'$transdetails','$pcname')";            
	    $result = mysqli_query($conn, $sqllogs);
	}
	function alert($link, $msg) {
		if($msg <> ""){
	    	echo '<script type = "text/javascript">alert("'.$msg.'"); window.location.replace("'.$link.'");</script>';
	    }else{
	    	echo '<script type = "text/javascript">window.location.replace("'.$link.'");</script>';
	    }
	}
	function ddate($date) {
		return date("M j, Y", strtotime($date));
	}
	function ddatet($date) {
		return date("M j, Y h:i A", strtotime($date));
	}
	function random_string($length) {
	    $key = '';
	    $keys = array_merge(range(0, 9));
	    $keys2 = array_merge(range('A', 'Z'));
	    for ($i = 0; $i < $length; $i++) {
	        $key .= $keys[array_rand($keys)];
	        if($i %3 == true){
	       		$key .= $keys2[array_rand($keys2)];
	        }
	    }
	    return $key;
	}
?>
