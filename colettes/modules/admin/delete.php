<?php
	if( (isset($_GET['id']) && $_GET['id'] > 0) && (isset($_GET['recipe']) && $_GET['recipe'] == 1) ){
		$stmt = $conn->prepare("DELETE FROM recipe WHERE recipe_id = ?");
		$stmt->bind_param("i", $_GET['id']);
		if($stmt->execute()===TRUE){
			alert("admin/recipe/".$_GET['prod'],"Delete succcessfull");
		}
		exit;
	}

	if(isset($_GET['id']) && $_GET['id'] > 0){
		$stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
		$stmt->bind_param("i", $_GET['id']);
		if($stmt->execute()===TRUE){
			alert("admin/prodlist","Delete succcessfull");
		}
	}

	
?>