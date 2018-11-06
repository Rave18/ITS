<?php date_default_timezone_set("Asia/Manila"); ?>
<?php if(isset($_GET['view'])){ $_GET['view'] = mysqli_real_escape_string($conn,$_GET['view']); } ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title> Collete's <?php if(isset($title)){echo ' - ' . $title; }?> </title>
		<base href="<?php echo "http://$_SERVER[HTTP_HOST]"."/".$pagename;?>/"/>
		<meta charset="utf-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1">		
		<!-- Latest compiled and minified CSS 
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/bootstrap.min.css">-->
		
		<!-- jQuery library -->
		<script src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/js.js"></script>
		<script src="js/js2.js"></script>
		
		<!-- Latest compiled JavaScript -->
		<script src="js/bootstrap.min.js"></script>

		<?php //if(isset($_SESSION['insta_acc']) && $access->level > 50){ ?>
			<link rel="stylesheet" href="assets/css/normalize.css">
		    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
		    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
		    <link rel="stylesheet" href="assets/css/themify-icons.css">
		    <link rel="stylesheet" href="assets/css/flag-icon.min.css">
		    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
		    <!-- <link rel="stylesheet" href="assets/css/bootstrap-select.less"> -->
		    <link rel="stylesheet" href="assets/scss/style.css?<?php echo rand(1,100);?>">
		    <link href="assets/css/lib/vector-map/jqvmap.min.css" rel="stylesheet">
		<?php 	//}	?>		
		<link rel="stylesheet" href="css/css.css?<?php echo rand(1,100);?>">
	</head>
	<body>
