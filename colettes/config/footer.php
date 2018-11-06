		</div>
		<button type="button" class="btn btn-secondary mb-1" id = "modaltrig" data-toggle="modal" data-target="#modalx" style="display: none;"></button>
		<div class="modal fade" id="modalx" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-md" role="document" id = "ajax">
		      
		  </div>
		</div>
		<?php if(isset($_SESSION['insta_acc']) && isset($access->level) && $access->level <= 1){ ?>
		<div class="modal fade" id="transfer" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
			<div class="modal-dialog modal-md" role="document">
		  		<div class="modal-content">
			        <div class="modal-header">
			            <h5 class="modal-title" id="modalLabel">Transfer Stocks to Other Branch</h5>
			            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			                <span aria-hidden="true">&times;</span>
			            </button>
			        </div>
			        <div class="modal-body">
			            <form action="" method="post">
			            	<div class="form-group">
								<label>Select Branch <font color = "red"> * </font></label>
								<select class="form form-control form-control-sm" name="to_branch" required>
									<option value=""> - - - - - - </option>
									<option value="0"> Warehouse </option>
							    	<?php
								      	$stmt = "SELECT * FROM branches WHERE branch_id not in ('" . $_SESSION['branch_id'] . "') ORDER BY b_name";
								      	$stmt = $conn->query($stmt);
								      	if($stmt->num_rows > 0){
								      		while ($row = $stmt->fetch_object()) {
								      			echo '<option value = "' . $row->branch_id . '"> ' . $row->b_name . ' </option>';
								      		}
								      	}
							     	?>
				     			</select>
				     		</div>
			            	<div class="form-group">
								<label>Select Product <font color = "red"> * </font></label>
								<select class="form form-control form-control-sm" name="product" required>
									<option value=""> - - - - - - </option>
							    	<?php
								      	$stmt = "SELECT * FROM b_stocks AS a LEFT JOIN products AS b on a.product_id = b.product_id WHERE a.branch_id = '" . $_SESSION['branch_id'] . "' and qty > 0 ORDER BY b.name";
								      	$stmt = $conn->query($stmt);
								      	if($stmt->num_rows > 0){
								      		while ($row = $stmt->fetch_object()) {
								      			echo '<option value = "' . $row->product_id . '"> ' . $row->name . ' [ ' . $row->qty . ' ] </option>';
								      		}
								      	}
							     	?>
				     			</select>
				     		</div>
				     		<div class="form-group">
				     			<label>Number of products</label>
				     			<input type = "number" name = "number" placeholder = '0' class="form form-control form-control-sm" required>
				     		</div>
				     		<div class="form-group" align="center">
				     			<button class="btn btn-sm btn-primary" name = "sub_trans"> Submit </button>
				     		</div>
		     			</form>
		     		</div>
		  		</div>
			</div>
		</div>
		<div class="modal fade" id="void" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
			<div class="modal-dialog modal-md" role="document">
		  		<div class="modal-content">
			        <div class="modal-header">
			            <h5 class="modal-title" id="modalLabel">Cancel Transaction</h5>
			            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			                <span aria-hidden="true">&times;</span>
			            </button>
			        </div>
			        <div class="modal-body">
			            <form action="" method="post">
			            	<div class="form-group">
								<label>Select Transaction <font color = "red"> * </font></label>
								<select class="form form-control form-control-sm" name="transact" required>
									<option value=""> - - - - - - </option>
							    	<?php
								      	$stmt = "SELECT * FROM sales WHERE temp = 0 and branch_id = '" . $_SESSION['branch_id'] . "' and dttm_pay between '". date("Y-m-d H:i:s", strtotime("-1 day")) . "' and '" . date("Y-m-d H:i:s") . "' ORDER BY dttm_pay";
								      	$stmt = $conn->query($stmt);
								      	if($stmt->num_rows > 0){
								      		while ($row = $stmt->fetch_object()) {
								      			echo '<option value = "' . $row->sales_id . '"> Invoice No: ' . $row->sales_id . ' [ P ' . $row->total . ' ] </option>';
								      		}
								      	}
							     	?>
				     			</select>
				     		</div>
				     		<div class="form-group" align="center">
				     			<button class="btn btn-sm btn-primary" name = "sub_void" onclick = "return confirm('Are you sure?');"> Submit </button>
				     		</div>
		     			</form>
		     		</div>
		  		</div>
			</div>
		</div>
		<div class="modal fade" id="msg" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
			<div class="modal-dialog modal-md" role="document">
		  		<div class="modal-content">
			        <div class="modal-header">
			            <h5 class="modal-title" id="modalLabel">Send Message to Admin</h5>
			            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			                <span aria-hidden="true">&times;</span>
			            </button>
			        </div>
			        <div class="modal-body">
			            <form action="" method="post">			            	
				     		<div class="form-group">
				     			<label>Message</label>
				     			<textarea class="form-control form-control-sm" name="message" row="100" placeholder = "Enter message"></textarea>
				     		</div>
				     		<div class="form-group" align="center">
				     			<button class="btn btn-sm btn-primary" name = "msg_send"> Submit </button>
				     		</div>
		     			</form>
		     		</div>
		  		</div>
			</div>
		</div>

		<?php 
					if(isset($_POST['sub_trans'])){
						if(!empty($_POST['product']) && !empty($_POST['number'])){
							$stmtx = "SELECT * FROM b_stocks AS a LEFT JOIN products AS b on a.product_id = b.product_id WHERE a.branch_id = '" . $_SESSION['branch_id'] . "' and qty > 0 and b.product_id = '" . mysqli_real_escape_string($conn, $_POST['product']) . "' ORDER BY b.name";
					      	$stmtx = $conn->query($stmtx);
					      	if($stmtx->num_rows > 0){
					      		$stmtx = $stmtx->fetch_object();
					      		if($_POST['number'] > $stmtx->qty){
					      			alert("main", "Not enough stocks.");
					      			exit;
					      		}
					      	}
							$stmt = $conn->prepare("INSERT INTO transfers (branch_id, product_id, qty, to_branch) VALUES (?, ?, ?, ?)");
							$stmt->bind_param("iidi", $_SESSION['branch_id'], $_POST['product'], $_POST['number'], $_POST['to_branch']);
							if($stmt->execute() === TRUE){
								alert("main", "Transfer Product Sent.");
							}
						}else{
							alert($_GET['module'],"Check your details");
						}
					}
					if(isset($_POST['sub_void'])){
						if(!empty($_POST['transact'])){
							$stmtx = "SELECT * FROM sales where sales_id = '" . mysqli_real_escape_string($conn, $_POST['transact']) . "' and branch_id = '" . $_SESSION['branch_id'] . "'";
					      	$stmtx = $conn->query($stmtx);
					      	if($stmtx->num_rows <= 0){					      		
								alert("main", "No record found.");
								exit;
					      	}else{
					      		$stmtx = $stmtx->fetch_object();
					      	}
							$stmt = $conn->prepare("UPDATE sales SET temp = 2 WHERE sales_id = ? and branch_id = ?");
							$stmt->bind_param("ii", $_POST['transact'], $_SESSION['branch_id']);
							if($stmt->execute() === TRUE){
								$update2 = $conn->prepare("UPDATE b_stocks SET qty = (qty + ?) where product_id = ? and branch_id = ?");
							 	$update2->bind_param("dii", $stmtx->qty, $stmtx->product_id, $stmtx->branch_id);
							 	if($stmt->execute() === $update2->execute()){
									alert("main", "Void has been successful.");
								}
							}
						}else{
							alert($_GET['module'],"Check your details");
						}
					}
					if(isset($_POST['msg_send'])){
						if(!empty($_POST['message'])){
							$stmt = $conn->prepare("INSERT INTO message (branch_id, message) VALUES (?, ?)");
							$stmt->bind_param("is", $_SESSION['branch_id'], $_POST['message']);
							if($stmt->execute() === TRUE){
								alert("main", "Message has been sent to admin.");
							}
						}else{
							alert($_GET['module'],"Check your details");
						}
					}
				} 
		?>
		<?php
			if(isset($_SESSION['insta_acc']) && isset($access->level) && $access->level > 1){
		?>
			<div class="modal fade" id="dispose" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
				<div class="modal-dialog modal-md" role="document">
			  		<div class="modal-content">
				        <div class="modal-header">
				            <h5 class="modal-title" id="modalLabel">Dipose Product</h5>
				            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				                <span aria-hidden="true">&times;</span>
				            </button>
				        </div>
				        <div class="modal-body">
				            <form action="" method="post">
					     		<div class="form-group">
					     			<label>Select Product <font color="red"> * </font></label>
					     			<select class="form form-control form-control-sm" name = "product">
					     				<option value=""> - - - - - - - - </option>
					     				<?php
					     					$stmt = "SELECT * FROM products WHERE wrecipe = 1 and curstocks > 0";
					     					$stmt = $conn->query($stmt);
					     					if($stmt->num_rows > 0){
					     						while ($row = $stmt->fetch_object()) {
					     							echo '<option value = "' . $row->product_id . '"> ' . $row->name . ' [ ' . number_format($row->curstocks) . ' pc/s ] </option>';
					     						}
					     					}
					     				?>
					     			</select>
					     		</div>
					     		<div class="form-group">
					     			<label>Number of product <font color="red"> * </font></label>
					     			<input type = "number" name = "number" placeholder = '0' class="form form-control form-control-sm" required>
					     		</div>
					     		<div class="form-group" align="center">
					     			<button class="btn btn-sm btn-primary" name = "sub_dispose"> Submit </button>
					     		</div>
			     			</form>
			     		</div>
			  		</div>
				</div>
			</div>

			<div class="modal fade" id="bo" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
				<div class="modal-dialog modal-md" role="document">
			  		<div class="modal-content">
				        <div class="modal-header">
				            <h5 class="modal-title" id="modalLabel">Return to Supplier</h5>
				            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				                <span aria-hidden="true">&times;</span>
				            </button>
				        </div>
				        <div class="modal-body">
				            <form action="" method="post">
					     		<div class="form-group">
					     			<label>Select Product <font color="red"> * </font></label>
					     			<select class="form form-control form-control-sm" name = "product">
					     				<option value=""> - - - - - - - - </option>
					     				<?php
					     					$stmt = "SELECT * FROM products WHERE wrecipe = 0 and curstocks > 0";
					     					$stmt = $conn->query($stmt);
					     					if($stmt->num_rows > 0){
					     						while ($row = $stmt->fetch_object()) {
					     							echo '<option value = "' . $row->product_id . '"> ' . $row->name . ' [ ' . number_format($row->curstocks) . ' pc/s ] </option>';
					     						}
					     					}
					     				?>
					     			</select>
					     		</div>
					     		<div class="form-group">
					     			<label>Number of product <font color="red"> * </font></label>
					     			<input type = "number" name = "number" placeholder = '0' class="form form-control form-control-sm" required>
					     		</div>
					     		<div class="form-group" align="center">
					     			<button class="btn btn-sm btn-primary" name = "sub_bo"> Submit </button>
					     		</div>
			     			</form>
			     		</div>
			  		</div>
				</div>
			</div>

		<?php
				if(isset($_POST['sub_dispose']) || isset($_POST['sub_bo'])){
					if(!isset($_POST['sub_dispose'])){
						$slect = '0';
						$tag = "Back Ordered.";
						$linkxx = "bo_rep";
						$prep = "INSERT INTO dispose_bo (product_id, qty, type) VALUES (?, ?, 0)";
					}else{
						$slect = '1';
						$linkxx = "d_rep";
						$tag = "Product Disposed.";
						$prep = "INSERT INTO dispose_bo (product_id, qty, type) VALUES (?, ?, 1)";
					}
					if(!empty($_POST['product']) && !empty($_POST['number'])){
						$stmtx = "SELECT * FROM products where product_id = '" . mysqli_real_escape_string($conn, $_POST['product']) . "' and wrecipe = '" . $slect . "'";
				      	$stmtx = $conn->query($stmtx);
				      	if($stmtx->num_rows > 0){
				      		$stmtx = $stmtx->fetch_object();
				      		if($_POST['number'] > $stmtx->curstocks){
				      			alert("admin", "Not enough stocks.");
				      			exit;
				      		}
				      	}
						$stmt = $conn->prepare($prep);
						$stmt->bind_param("id", $_POST['product'], $_POST['number']);
						if($stmt->execute() === TRUE){
							$stmtx = $conn->prepare("UPDATE products SET curstocks = (curstocks - ?) WHERE product_id = ? and wrecipe = ?");
							$stmtx->bind_param("dii", $_POST['number'], $_POST['product'], $slect);
							if($stmtx->execute() === TRUE){
								alert("admin/".$linkxx, $tag);	
							}
						}
					}else{
						alert($_GET['module'],"Check your details");
					}
				}
			}
		?>
	   	<script src="assets/js/vendor/jquery-2.1.4.min.js"></script>
	    <script src="assets/js/popper.min.js"></script>
	    <script src="assets/js/plugins.js"></script>
	    <script src="assets/js/main.js"></script>
	</body>
</html>
<?php
	if( isset($conn) && is_resource($conn) ){
		$conn->close(); 
	}
?>