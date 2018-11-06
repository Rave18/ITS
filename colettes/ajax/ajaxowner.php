<?php
	include '../config/conf.php';
	session_start();
	if(!isset($_GET['module'])){
		echo '<script type = "text/javascript">window.location.replace("/colletes/");</script>';
	}
?>
<?php
	if(isset($_GET['val']) && isset($_GET['edit'])){
		if(isset($_GET['form'])){
			$_GET['val'] = mysqli_real_escape_string($conn, $_GET['val']);
			switch ($_GET['form']) {
				case 'product':
					$stmt = "SELECT * FROM products WHERE product_id = '" . $_GET['val'] . "'";
					$stmt = $conn->query($stmt);
					if($stmt->num_rows > 0){
						$res = $stmt->fetch_object();
						$_SESSION['edit_id'] = $res->product_id;
?>			
			<form action="" method="post">
				    <div class="modal-content">
				        <div class="modal-header">
				            <h5 class="modal-title" id="modalLabel">Edit Product</h5>
				            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				                <span aria-hidden="true">&times;</span>
				            </button>
				        </div>
				        <div class="modal-body">
				            <form action="" method="post">
				            	<div class="form-group">
									<label>Product Name <font color = "red"> * </font></label>
									<input type = "text" autocomplete = "off" class="form-control form-control-sm" placeholder = "Enter product." required autofocus name = "p_name" value = "<?php echo $res->name;?>">
								</div>
								<div class="form-group">
									<label>Product Cost <font color = "red"> * </font></label>
									<input pattern = "[0-9.]*" type = "text" autocomplete = "off" class="form-control form-control-sm" placeholder = "Enter product." required autofocus name = "p_price" value = "<?php echo $res->price;?>">
								</div>
								<div class="form-group">
                                  <label for="recipe" class="control-label mb-1">With Recipe  <font color = "red"> * </font></label>
                                  <select required  name="recipe" id="recipe" class="form-control form-control-sm" onchange = "change_x()">
                                    <option value=""> - - - - - - - </option>
                                    <option value="1" <?php if($res->wrecipe == '1'){ echo ' selected '; } ?>> Yes </option>
                                    <option value="0" <?php if($res->wrecipe == '0'){ echo ' selected '; } ?>> No </option>
                                  </select>
                              	</div>
                              	<div class="form-group" <?php if($res->wrecipe <> '1'){ echo ' style="display: none" '; } ?> id = "psets">
	                                <label for="recipe" class="control-label mb-1">Per Sets  <font color = "red"> * </font></label>
	                                <input type = "number" class="form-control form-control-sm" <?php if($res->wrecipe == '1'){ echo ' value = "' . $res->persets . '"'; } ?> name = "per_sets" min = "1" placeholder = "Enter per sets">
	                              </div>
								<div class="form-group">
									<label for="category" class="control-label mb-1">Category  <font color = "red"> * </font></label>
	                                <select required  name="p_category" id="category" class="form-control form-control-sm">
	                                	<option value=""> - - - - - - - </option>
	                                    <?php
	                                      $cat = "SELECT * FROM category ORDER BY c_name";
	                                      $cat = $conn->query($cat);
	                                      if($cat->num_rows > 0){	                                      	
	                                        while ($row = $cat->fetch_object()) {
	                                        	$selected = "";
	                                        	if($row->category_id == $res->category){
	                                        		$selected = " selected ";
	                                        	}
	                                        	echo '<option '.$selected.' value = "' . $row->category_id . '"> ' . $row->c_name . '</option>';
	                                        }
	                                      }
	                                    ?>
	                                </select>
                                </div>   
				            </form>
				        </div>
				        <div class="modal-footer">
				            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				            <button type="submit" class="btn btn-primary" name = "update">Update</button>
				        </div>
				    </div>
				</form>
<?php
					}
					break;

				case 'recipe':
					$stmt = "SELECT *,a.qty as qtyx FROM recipe as a LEFT JOIN raw as b ON a.raw_id = b.raw_id where a.recipe_id = '" . $_GET['val'] . "'";
                    $stmt = $conn->query($stmt);
					if($stmt->num_rows > 0){
						$res = $stmt->fetch_object();
						$_SESSION['edit_id'] = $res->recipe_id;
?>
						<form action="" method="post">
						    <div class="modal-content">
						        <div class="modal-header">
						            <h5 class="modal-title" id="modalLabel"><?php echo $res->raw_desc; ?> Ingredient</h5>
						            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						                <span aria-hidden="true">&times;</span>
						            </button>
						        </div>
						        <div class="modal-body">
						        	<div class="form-group">
										<label>Quantity (kg) <font color = "red"> * </font></label>
										<input pattern = "[0-9.]*" type = "text" autocomplete = "off" class="form-control form-control-sm" placeholder = "Enter product." required autofocus name = "qty" value = "<?php echo $res->qtyx;?>">
									</div>
						        </div>
						        <div class="modal-footer">
						            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						            <button type="submit" class="btn btn-primary" name = "update_rep">Update</button>
						        </div>
						    </div>
						</form>
<?php
				}
				break;

				case 'branch':
					$stmt = "SELECT * FROM branches where branch_id = '" . $_GET['val'] . "'";
                    $stmt = $conn->query($stmt);
					if($stmt->num_rows > 0){
						$res = $stmt->fetch_object();
						$_SESSION['edit_id'] = $res->branch_id;
?>
						<form action="" method="post">
						    <div class="modal-content">
						        <div class="modal-header">
						            <h5 class="modal-title" id="modalLabel">Edit Branch</h5>
						            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						                <span aria-hidden="true">&times;</span>
						            </button>
						        </div>
						        <div class="modal-body">
						            <form action="" method="post">
						            	<div class="form-group">
											<label>Branch Name <font color = "red"> * </font></label>
											<input value = "<?php echo $res->b_name;?>" type = "text" autocomplete = "off" name = "b_name" class="form-control  form-control-sm" required placeholder = "Enter branch name."/>
										</div>								
										<div class="form-group">
											<label for="category" class="control-label mb-1">Branch Location<font color = "red"> * </font></label>
			                                <input value = "<?php echo $res->b_addr;?>" type = "text" autocomplete = "off" name = "b_addr" class="form-control  form-control-sm" required placeholder = "Enter branch name"/>
		                                </div> 
						            </form>
						        </div>
						        <div class="modal-footer">
						            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						            <button type="submit" class="btn btn-primary" name = "update">Update</button>
						        </div>
						    </div>
						</form>
<?php
				}
				break;
				
				default:
					# code...
					break;
			}
		}
	}
?>

<?php
	if(isset($_GET['add'])){
		if(isset($_GET['form'])){
			switch ($_GET['form']) {
				case 'raw':
?>			
			<form action="" method="post">
				    <div class="modal-content">
				        <div class="modal-header">
				            <h5 class="modal-title" id="modalLabel">Add raw stocks</h5>
				            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				                <span aria-hidden="true">&times;</span>
				            </button>
				        </div>
				        <div class="modal-body">
				            <form action="" method="post">
				            	<div class="form-group">
									<label>Raw Material <font color = "red"> * </font></label>
									<select required name="raw" id="category" class="form-control form-control-sm" autofocus>
	                                	<option value=""> - - - - - - - </option>
	                                    <?php
	                                      $cat = "SELECT * FROM raw ORDER BY raw_desc";
	                                      $cat = $conn->query($cat);
	                                      if($cat->num_rows > 0){	                                      	
	                                        while ($row = $cat->fetch_object()) {
	                                        	echo '<option value = "' . $row->raw_id . '"> ' . $row->raw_desc . '</option>';
	                                        }
	                                      }
	                                    ?>
	                                </select>
	                                <input type = "text" class="form-control form-control-sm" name = "new_raw" style="display: none;" id = "new_raw" placeholder = "Enter new material">
	                                <label><input type = "checkbox" name = "checkbox" id = "check" onclick="click_x()" /> New raw material </label>
								</div>								
								<div class="form-group">
									<label for="category" class="control-label mb-1">Quantity (kg)<font color = "red"> * </font></label>
	                                <input type = "text" pattern = "[0-9.]*" autocomplete = "off" name = "qty" class="form-control  form-control-sm" required placeholder = "Enter quantity"/>
                                </div>   
								<div class="form-group">
									<label>Delivery Date <font color = "red"> * </font></label>
									<input type = "date" class="form-control form-control-sm" required name = "d_date" max = "<?php echo date("Y-m-d");?>" value = "<?php echo date("Y-m-d");?>">
								</div>
				            </form>
				        </div>
				        <div class="modal-footer">
				            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				            <button type="submit" class="btn btn-primary" name = "add">Add</button>
				        </div>
				    </div>
				</form>
<?php
					break;

				case 'product':
?>			
			<form action="" method="post">
				    <div class="modal-content">
				        <div class="modal-header">
				            <h5 class="modal-title" id="modalLabel">Transfer stocks to branches</h5>
				            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				                <span aria-hidden="true">&times;</span>
				            </button>
				        </div>
				        <div class="modal-body">
				            <form action="" method="post">
				            	<div class="form-group">
									<label>Select Product <font color = "red"> * </font></label>
									<select required name="p_id" id="p_id" class="form-control form-control-sm" autofocus>
	                                	<option value=""> - - - - - - - </option>
	                                    <?php
	                                    	$cat = "SELECT * FROM products WHERE curstocks > 0 ORDER BY name";
	                                    	$cat = $conn->query($cat);
	                                    	if($cat->num_rows > 0){	                                      	
	                                    		while ($row = $cat->fetch_object()) {
	                                       			echo '<option value = "' . $row->product_id . '"> ' . $row->name . ' [ ' . $row->curstocks . ' ]</option>';
	                                        	}
	                                      	}
	                                    ?>
	                                </select>
								</div>
								<div class="form-group">
									<label>Branch <font color = "red"> * </font></label>
									<select required name="branch" id="branch" class="form-control form-control-sm">
	                                    <?php
	                                    	$cat = "SELECT * FROM branches ORDER BY b_name";
	                                    	$cat = $conn->query($cat);
	                                    	if($cat->num_rows > 0){	                                      	
	                                    		while ($row = $cat->fetch_object()) {
	                                       			echo '<option value = "' . $row->branch_id . '"> ' . $row->b_name . '</option>';
	                                        	}
	                                      	}
	                                    ?>
	                                </select>
								</div>								
								<div class="form-group">
									<label class="control-label mb-1">Quantity<font color = "red"> * </font></label>
	                                <input type = "text"pattern = "[0-9.]*" autocomplete = "off" name = "qty" class="form-control  form-control-sm" required placeholder = "Enter quantity"/>
                                </div>
				            </form>
				        </div>
				        <div class="modal-footer">
				            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				            <button type="submit" class="btn btn-primary" name = "add">Add</button>
				        </div>
				    </div>
			</form>
<?php
					break;
			case 'prod_inve':
?>			
			<form action="" method="post">
				    <div class="modal-content">
				        <div class="modal-header">
				            <h5 class="modal-title" id="modalLabel">Add outsourced stocks</h5>
				            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				                <span aria-hidden="true">&times;</span>
				            </button>
				        </div>
				        <div class="modal-body">
				            <form action="" method="post">
				            	<div class="form-group">
									<label>Select Product <font color = "red"> * </font></label>
									<select required name="p_id" id="p_id" class="form-control form-control-sm" autofocus>
	                                	<option value=""> - - - - - - - </option>
	                                    <?php
	                                    	$cat = "SELECT * FROM products WHERE wrecipe = 0 ORDER BY name";
	                                    	$cat = $conn->query($cat);
	                                    	if($cat->num_rows > 0){	                                      	
	                                    		while ($row = $cat->fetch_object()) {
	                                       			echo '<option value = "' . $row->product_id . '"> ' . $row->name . ' [ ' . $row->curstocks . ' ]</option>';
	                                        	}
	                                      	}
	                                    ?>
	                                </select>
								</div>								
								<div class="form-group">
									<label class="control-label mb-1">Quantity<font color = "red"> * </font></label>
	                                <input type = "text"pattern = "[0-9.]*" autocomplete = "off" name = "qty" class="form-control  form-control-sm" required placeholder = "Enter quantity"/>
                                </div>   
								<div class="form-group">
									<label>Delivery Date <font color = "red"> * </font></label>
									<input type = "date" class="form-control form-control-sm" required name = "d_date" max = "<?php echo date("Y-m-d");?>" value = "<?php echo date("Y-m-d");?>">
								</div>
				            </form>
				        </div>
				        <div class="modal-footer">
				            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				            <button type="submit" class="btn btn-primary" name = "add">Add</button>
				        </div>
				    </div>
			</form>
<?php
					break;

				case 'recipe';
?>
				<form action="" method="post">
				    <div class="modal-content">
				        <div class="modal-header">
				            <h5 class="modal-title" id="modalLabel">Add new ingredients</h5>
				            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				                <span aria-hidden="true">&times;</span>
				            </button>
				        </div>
				        <div class="modal-body">
				            <form action="" method="post">
				            	<div class="form-group">
									<label>Raw Material <font color = "red"> * </font></label>
									<select required name="raw" id="category" class="form-control form-control-sm" autofocus>
	                                	<option value=""> - - - - - - - </option>
	                                    <?php
	                                      $cat = "SELECT * FROM raw ORDER BY raw_desc";
	                                      $cat = $conn->query($cat);
	                                      if($cat->num_rows > 0){	                                      	
	                                        while ($row = $cat->fetch_object()) {
	                                        	echo '<option value = "' . $row->raw_id . '"> ' . $row->raw_desc . '</option>';
	                                        }
	                                      }
	                                    ?>
	                                </select>
								</div>								
								<div class="form-group">
									<label for="category" class="control-label mb-1">Quantity (kg)<font color = "red"> * </font></label>
	                                <input type = "text" pattern = "[0-9.]*"autocomplete = "off" name = "qty" class="form-control  form-control-sm" required placeholder = "Enter quantity"/>
                                </div> 
				            </form>
				        </div>
				        <div class="modal-footer">
				            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				            <button type="submit" class="btn btn-primary" name = "add">Add</button>
				        </div>
				    </div>
				</form>
<?php
					break;
				
				case 'branches';
?>
				<form action="" method="post">
				    <div class="modal-content">
				        <div class="modal-header">
				            <h5 class="modal-title" id="modalLabel">New Branch</h5>
				            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				                <span aria-hidden="true">&times;</span>
				            </button>
				        </div>
				        <div class="modal-body">
				            <form action="" method="post">
				            	<div class="form-group">
									<label>Branch Name <font color = "red"> * </font></label>
									<input type = "text" autocomplete = "off" name = "b_name" class="form-control  form-control-sm" required placeholder = "Enter branch name."/>
								</div>								
								<div class="form-group">
									<label for="category" class="control-label mb-1">Branch Location<font color = "red"> * </font></label>
	                                <input type = "text" autocomplete = "off" name = "b_addr" class="form-control  form-control-sm" required placeholder = "Enter branch name"/>
                                </div> 
				            </form>
				        </div>
				        <div class="modal-footer">
				            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				            <button type="submit" class="btn btn-primary" name = "add">Add</button>
				        </div>
				    </div>
				</form>
<?php
					break;

				default:
					# code...
					break;
			}
		}
	}
?>

<?php
	if(isset($_GET['recipe'])){
		$select = "SELECT *,a.qty as recipe_qty, b.qty as raw_qty FROM recipe as a LEFT JOIN raw as b on a.raw_id = b.raw_id where a.product_id = '" . mysqli_real_escape_string($conn, $_GET['recipe']) . "'";
		$select = $conn->query($select);
		if(!is_numeric($_GET['qty']) && $_GET['qty'] != ""){
			echo '<div align="center"><h4> Wrong input </h4></div>';
			echo '<input type = "hidden" id = "red" value = "'.$_SESSION['disable'].'"></div>';			
			$_SESSION['recipe'] = $_GET['recipe'];
			$_SESSION['qty'] = $_GET['qty'];
			exit;
		}
		if($select->num_rows > 0){
			$_SESSION['disable'] = 0;
			if(isset($_GET['qty']) && ($_GET['qty'] == 0 || $_GET['qty'] == "")){
				$_GET['qty'] = 1;
			}
			echo '<div class="form-group" style = "margin-left: 10px;"><ul>';
			while ($row = $select->fetch_object()) {
				$color = "";
				if($row->raw_qty < ($row->recipe_qty * $_GET['qty'])){
					$color = " color = 'red' ";
					$_SESSION['disable'] = 1;
				}
				echo '<li>' . $row->raw_desc . ' ' . $row->recipe_qty * $_GET['qty'] . ' (kg) <b>[ <font ' . $color . '>' . $row->raw_qty . '</font> ] </b></li>';	
			}
			$_SESSION['recipe'] = $_GET['recipe'];
			$_SESSION['qty'] = $_GET['qty'];
			echo '</ul></div>';
			echo '<input type = "hidden" id = "red" value = "'.$_SESSION['disable'].'">';
		}else{
			if($_GET['recipe'] <> "undefined"){
				$_SESSION['disable'] = 1;
				echo '<div align="center"><h4> No Record Found </h4><br>';
				echo '<a href = "admin/recipe/' . mysqli_real_escape_string($conn, $_GET['recipe']) . '" class = "btn btn-primary btn-sm center-block"> Add recipe </a>';			
				echo '<input type = "hidden" id = "red" value = "'.$_SESSION['disable'].'"></div>';			
				$_SESSION['recipe'] = $_GET['recipe'];
				$_SESSION['qty'] = $_GET['qty'];
			}
		}
	}
?>

<?php
	if(isset($_GET['branch_inve'])){
		$_SESSION['branch_inve'] = mysqli_real_escape_string($conn, $_GET['branch_inve']);
		if($_GET['search'] == 'undefined'){
			$_GET['search'] = "";
		}
		$_SESSION['search'] = mysqli_real_escape_string($conn, $_GET['search']);
		$where = " where (a.name like '%" . $_SESSION['search'] . "%' or b.qty like '%" . $_SESSION['search'] . "%' or c.b_name like '%" . $_SESSION['search'] . "%')";
		if($_SESSION['branch_inve'] != 'all'){
			$where .= " and b.branch_id = '" . $_SESSION['branch_inve'] . "'";
		}
?>
    <thead class="thead-dark"  align="center">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Product Name</th>
        <!--<th scope="col">Date</th>-->
        <th scope="col">Quantity</th>
        <th scope="col">Branch</th>
        <!--<th scope="col">Action</th>-->
      </tr>
    </thead>
    <tbody align="center">
      <?php
        $prod = "SELECT * FROM b_stocks as b LEFT JOIN products as a on a.product_id = b.product_id LEFT JOIN branches as c on b.branch_id = c.branch_id " . $where . " ORDER BY a.name asc";
        $prod = $conn->query($prod);
        if($prod->num_rows > 0){
          $num = 0;
          while ($row = $prod->fetch_object()) {
            $num += 1;
            echo '<tr>';
              echo '<th scope="row">' . $num . '</th>';
              echo '<td>' . $row->name . '</td>';
              //echo '<td>' . $row->d_date . '</td>';
              echo '<td>';
              if($row->qty <= 10){
                echo '<font color="red"><b>' . $row->qty . '</b></font>';
              }else{
                echo $row->qty;
              }
              echo '</td>';
              echo '<td>' . $row->b_name . '</td>';
              //echo '<td>';
              //  echo '<a onclick = "edit('.$row->raw_id.',\'raw\')" name = "edit" class = "btn btn-sm btn-success"> Edit </a>';
              //  echo ' <a onclick = "return confirm(\'Are you sure?\')" href = "admin/delete?id=' . $row->raw_id . '" class = "btn btn-sm btn-danger"> Delete </a>';
              //echo '</td>';
            echo '</tr>';
          }
        }
      ?>                   
    </tbody>
<?php
	}

?>
<?php
	if(isset($_GET['sales'])){
		$_GET['sales'] = mysqli_real_escape_string($conn, $_GET['sales']);
		$stmt = "SELECT * FROM products WHERE category = '" . $_GET['sales'] . "'";
		$stmt = $conn->query($stmt);
		if($stmt->num_rows > 0){
?>
	    <div class="modal-content">
	        <div class="modal-header">
	            <h5 class="modal-title" id="modalLabel">Select Product</h5>
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                <span aria-hidden="true">&times;</span>
	            </button>
	        </div>
	        <div class="modal-body">
	        	<ul style="margin-left: 10px;">
<?php
					while ($row = $stmt->fetch_object()) {
						echo '<li><b>' . $row->name . ' ( ' . $row->price . ' ) ' . '</b></li>';
					}
?>
				</ul>
	        </div>
		</div>
<?php
		}else{
?>
	<div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">No Record Found</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
<?php
		}
	}

?>

<?php
	if(isset($_GET['cart'])){
		$_GET['cart'] = mysqli_real_escape_string($conn, $_GET['cart']);
		$chkqty = "SELECT ifnull(a.qty,0) as qtyx FROM b_stocks as a WHERE a.product_id = '" . $_GET['cart'] . "' and a.branch_id = '" . $_SESSION['branch_id'] . "'";
		$chkqty = $conn->query($chkqty)->fetch_object();
		$chkqtyx = "SELECT ifnull(a.qty,0) as qtyx FROM sales as a  WHERE a.product_id = '" . $_GET['cart'] . "' and a.branch_id = '" . $_SESSION['branch_id'] . "' and a.temp = 1";
		
		$qtyxx = 0;
		if($conn->query($chkqtyx)->num_rows > 0){
			$chkqtyx = $conn->query($chkqtyx)->fetch_object();
			$qtyxx = $chkqtyx->qtyx;
		}
		$noins = 0;
		if(($_GET['qty'] <= ($chkqty->qtyx - ($qtyxx+$_GET['qty'])) || ($chkqty->qtyx - ($qtyxx+$_GET['qty'])) >= 0) && $chkqty->qtyx <> 0){
			$select = "SELECT * FROM sales where product_id = '" . $_GET['cart'] . "' and temp = '1'";
			$select = $conn->query($select);
			if($select->num_rows > 0){
				//update code
				$total = $_GET['qty'] * $_GET['price'];
				$stmt = $conn->prepare("UPDATE sales set qty = (qty + ?), amnt = ?, total = (total + ?) where product_id = ? and branch_id = ? and temp = '1'");
				$stmt->bind_param("dddii", $_GET['qty'], $_GET['price'], $total, $_GET['cart'], $_SESSION['branch_id']);
				if($stmt->execute() === TRUE){
					
				}
			}else{
				//insert
				$total = $_GET['qty'] * $_GET['price'];
				$stmt = $conn->prepare("INSERT INTO sales (product_id, branch_id, qty, amnt, total) VALUES (?, ?, ?, ?, ?)");
				$stmt->bind_param("iiddd", $_GET['cart'], $_SESSION['branch_id'], $_GET['qty'], $_GET['price'], $total);
				if($stmt->execute() === TRUE){
					
				}
			}
		}else{
			echo '<i><h4 align="center"> No more stock </h4></i><hr>';
		}
		$select = "SELECT * FROM sales as a LEFT JOIN products as b on a.product_id = b.product_id where a.branch_id = '" . $_SESSION['branch_id'] . "' and a.temp = 1";
		$select = $conn->query($select);
		if($select->num_rows > 0){
			echo '<div class="form-group" style = "margin-left: 10px;">';
				echo '<ul>';
				$qty = 0;
				$total = 0;
				while ($row = $select->fetch_object()) {
					$id = $row->sales_id;
					echo '<li> '. $row->name . ' @ ' . $row->price  . ' x ' . $row->qty . ' [ ' . number_format($row->total,2) . ' ] </li>';	
					$qty = $qty + $row->qty;
					$total = $total + $row->total;
				}
				echo '</ul>';
				echo '<hr><p> Total Qty @ '. $qty .' | Total Amnt @ P ' . number_format($total,2) . '</p>';
			echo '</div>';
			echo '<input type = "hidden" value = "' . $total . '" id = "totamnt"/>';
			echo '<input type = "text" onchange = "change(this.value,'.$total .')" name = "tendered" class = "form form-control form-control-sm" pattern = "[0-9]*" placeholder = "Enter amount"/>';
            echo '<hr>';
            echo '<p id = "xchange"> Change: </p>';
            echo '<div align="center"><hr>';
                echo '<button class = "btn btn-sm btn-primary" onclick = "return confirm(\'Are you sure?\')?checkout('.$_SESSION['branch_id'].','.$id.'):\'\';"> Checkout </button>';
            	echo '&nbsp;<button class = "btn btn-sm btn-danger" onclick = "return confirm(\'Are you sure?\')?clearcart('.$_SESSION['branch_id'].'):\'\';"> Clear Cart </button>';
            echo '</div>';
		}
	}
?>

<?php
	if(isset($_GET['checkout'])){
		$_GET['checkout'] = mysqli_real_escape_string($conn, $_GET['checkout']);
		$select = "SELECT * FROM sales where temp = '1' and branch_id = '" . $_SESSION['branch_id'] . "'";
		$select = $conn->query($select);
		if($select->num_rows > 0){
			$update = $conn->prepare("UPDATE sales set temp = 0, dttm_pay = now() where branch_id = ? and temp = 1");
			$update->bind_param("i", $_SESSION['branch_id']);
			if($update->execute() === TRUE){
				while ($row = $select->fetch_object()) {
				 	//$update2 = $conn->prepare("UPDATE products SET curstocks = (curstocks - ?) where product_id = ?");
				 	//$update2->bind_param("di", $row->qty, $row->product_id);
				 	//$update2->execute();

				 	$update2 = $conn->prepare("UPDATE b_stocks SET qty = (qty - ?) where product_id = ? and branch_id = ?");
				 	$update2->bind_param("dii", $row->qty, $row->product_id, $_SESSION['branch_id']);
				 	$update2->execute();
				}
				echo	'<div class="sufee-alert alert with-close alert-success alert-dismissible fade show">
                    		<span class="badge badge-pill badge-success">Success</span>
                            Payment Successful
                          	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            	<span aria-hidden="true">×</span>
                        	</button>
                    	</div>';
                //echo '<script>window.open("main/print?id="'.$_GET['checkout'].'","_blank");</script>';
			}
		}
	}
?>


<?php
	if(isset($_GET['b_sales']) && isset($_GET['frd']) && isset($_GET['tod'])){
		$_SESSION['b_sales'] = mysqli_real_escape_string($conn, $_GET['b_sales']);
		$_SESSION['frd'] = mysqli_real_escape_string($conn, $_GET['frd']) . ' 00:00:00';
		$_SESSION['tod'] = mysqli_real_escape_string($conn, $_GET['tod']) . ' 23:59:59';
		$_SESSION['rep'] = mysqli_real_escape_string($conn, $_GET['rep']);
		$where = "";
		if($_SESSION['b_sales'] != 'all'){
			$where = " and c.branch_id = '" . $_SESSION['b_sales'] . "'";
		}
		$where .= " and a.dttm between '" . $_SESSION['frd'] . "' and '" . $_SESSION['tod'] . "'";
?>
    <thead class="thead-dark"  align="center">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Product Name</th>
        <!--<th scope="col">Date</th>-->
        <th scope="col">Total Quantity Sold</th>
        <th scope="col">Total Amount</th>
        <th scope="col">Branch</th>
        <!--<th scope="col">Trans. Date</th>
        <th scope="col">Action</th>-->
      </tr>
    </thead>
    <tbody align="center">
      <?php
        $prod = "SELECT *,sum(total) as s_tot, sum(qty) as q_tot FROM sales as a left join products as b on a.product_id = b.product_id left join branches as c on a.branch_id = c.branch_id where temp = '".$_SESSION['rep']."' ".$where." group by a.product_id, a.branch_id";
        $prod = $conn->query($prod);
        if($prod->num_rows > 0){
          $num = 0;
          $total_q = 0;
          $total_s = 0;
          while ($row = $prod->fetch_object()) {
            $total_q += $row->q_tot;
            $total_s += $row->s_tot;
            $num += 1;
            echo '<tr>';
              echo '<td scope="row">' . $num . '</td>';
              echo '<td>' . $row->name . '</td>';
              echo '<td>' . $row->q_tot . '</td>';
              echo '<td>' . number_format($row->s_tot,2) . '</td>';
              echo '<td>' . $row->b_name . '</td>';
              //echo '<td>' . ddatet($row->dttm) . 'a</td>';
            echo '</tr>';
          }
          echo '<tr><td></td><td align="right"><b>Total</b></td><td><b>' . $total_q . '</b></td><td><b>' . number_format($total_s,2) . '</b></td><td></td><td></td></tr>';                        
        }
      ?>                     
    </tbody>
<?php
	}

?>
<?php
	if(isset($_GET['b_inve']) && isset($_GET['frdx']) && isset($_GET['todx'])){
		$_SESSION['b_inve'] = mysqli_real_escape_string($conn, $_GET['b_inve']);
		$_SESSION['frdx'] = mysqli_real_escape_string($conn, $_GET['frdx']) . ' 00:00:00';
		$_SESSION['todx'] = mysqli_real_escape_string($conn, $_GET['todx']) . ' 23:59:59';
		$where = "";
		if($_SESSION['b_inve'] != 'all'){
			$where = " and c.branch_id = '" . $_SESSION['b_inve'] . "'";
		}
		$where .= " and a.dttm between '" . $_SESSION['frdx'] . "' and '" . $_SESSION['todx'] . "'";
?>
		<thead class="thead-dark"  align="center">
		  <tr>
		    <th scope="col">#</th>
		    <th scope="col">Product Name</th>
		    <th scope="col">Total Stocks</th>
		    <th scope="col">Remaining Stocks</th>
		    <th scope="col">Total Sold Stocks</th>
		    <th scope="col">Branch</th>
		    <!--<th scope="col">Trans. Date</th>-->
		  </tr>
		</thead>
		<tbody align="center">
		  <?php                        
		    $prod = "SELECT *,sum(d.qty) as q_b, sum(a.qty) as q_tot FROM sales as a left join products as b on a.product_id = b.product_id left join branches as c on a.branch_id = c.branch_id LEFT JOIN b_stocks AS d ON a.branch_id = d.branch_id and a.product_id = d.product_id where temp = 0 ".$where." group by a.product_id, a.branch_id";
		    //echo $prod;
		    $prod = $conn->query($prod);
		    if($prod->num_rows > 0){
		      $num = 0;                          
		      $total_t = 0;
		      $total_r = 0;
		      $total_q = 0;
		      while ($row = $prod->fetch_object()) {
		        $total_t += $row->q_b + $row->q_tot;
		        $total_r += $row->q_b;
		        $total_q += $row->q_tot;
		        $num += 1;
		        echo '<tr>';
		          echo '<td scope="row">' . $num . '</td>';
		          echo '<td>' . $row->name . '</td>';
		          echo '<td>' . ($row->q_b + $row->q_tot) . '</td>';
		          echo '<td>' . $row->q_b . '</td>';
		          echo '<td>' . $row->q_tot . '</td>';
		          echo '<td>' . $row->b_name . '</td>';
		          //echo '<td>' . ddatet($row->dttm) . 'a</td>';
		        echo '</tr>';
		      }
		      echo '<tr><td></td><td align="right"><b>Total</b></td><td><b>' . $total_t . '</b></td><td><b>' . $total_r . '</b></td><td><b>' . $total_q . '</b></td><td></td></tr>';
		    }
		  ?>                   
		</tbody>
<?php
	}

?>


<?php
	if(isset($_GET['clearcart'])){
		$_GET['clearcart'] = mysqli_real_escape_string($conn, $_GET['clearcart']);
		$update = $conn->prepare("UPDATE sales set temp = 2 where branch_id = ? and temp = 1");
		$update->bind_param("i", $_SESSION['branch_id']);
		$update->execute();
	}
?>

<?php
	if(isset($_GET['prodlist'])){
		$_SESSION['prodlist'] = mysqli_real_escape_string($conn, $_GET['prodlist']);
		if($_GET['recipexx'] == 1){
			$wrecipe = " and wrecipe = 1 ";
		}else{
			$wrecipe = "  ";
		}
		$prod = "SELECT * FROM products LEFT JOIN category on products.category = category.category_id where (products.name like '%" . $_SESSION['prodlist'] . "%' or products.price like '%" . $_SESSION['prodlist'] . "%' or category.c_name like '%" . $_SESSION['prodlist'] . "%') " . $wrecipe . " ORDER BY name asc";
        $prod = $conn->query($prod);
        if($prod->num_rows > 0){
          $num = 0;
          while ($row = $prod->fetch_object()) {
            $num += 1;
            $wrecipe = $row->wrecipe ? 'Yes' : 'No';
            echo '<tr>';
              echo '<th scope="row">' . $num . '</th>';
              echo '<td>' . $row->name . '</td>';
              echo '<td>' . $row->price . '</td>';
              echo '<td>' . $row->c_name . '</td>';
              echo '<td>' . $wrecipe . '</td>';
              echo '<td>';
                if($wrecipe == 'Yes'){
                  echo '<a href = "admin/recipe/'.$row->product_id.'" class = "btn btn-sm btn-primary"> View </a> ';
                }
                //if($_GET['recipexx'] == '1'){
                	echo '<a onclick = "edit('.$row->product_id.',\'product\')" name = "edit" class = "btn btn-sm btn-success"> Edit </a>';
                //}
                
                //echo ' <a onclick = "return confirm(\'Are you sure?\')" href = "admin/delete?id=' . $row->product_id . '" class = "btn btn-sm btn-danger"> Delete </a>';
              echo '</td>';
            echo '</tr>';
          }
        }
	}

?>

<?php
	if(isset($_GET['bod_sales'])){		
		$_SESSION['bod_sales'] = mysqli_real_escape_string($conn, $_GET['bod_sales']);
		$_SESSION['frd'] = mysqli_real_escape_string($conn, $_GET['frd']) . ' 00:00:00';
		$_SESSION['tod'] = mysqli_real_escape_string($conn, $_GET['tod']) . ' 23:59:59';
		if($_SESSION['bod_sales'] == 1){
			$wrecipe = " and a.type = 1 ";
		}else{
			$wrecipe = " and a.type = 0 ";
		}
		$prod = "SELECT *,a.qty as qtyxx FROM dispose_bo as a left join products as b on a.product_id = b.product_id where a.dttm between '" . $_SESSION['frd'] . " 00:00:00' and '" . $_SESSION['tod'] . " 23:59:59' and a.type = '".$_SESSION['bod_sales']."' order by b.name";
       	$prod = $conn->query($prod);
        if($prod->num_rows > 0){
          $num = 0;
          $total_q = 0;
          while ($row = $prod->fetch_object()) {
            $total_q += $row->qtyxx;
            $num += 1;
            echo '<tr>';
              echo '<td scope="row">' . $num . '</td>';
              echo '<td>' . $row->name . '</td>';
              echo '<td>' . $row->qtyxx . '</td>';
            echo '</tr>';
          }
          echo '<tr><td></td><td align="right"><b>Total</b></td><td><b>' . $total_q . '</b></td></tr>';
        }
	}

?>


<?php
	if(isset($_GET['r_search'])){
		$_SESSION['r_search'] = mysqli_real_escape_string($conn, $_GET['r_search']);
		$prod = "SELECT * FROM raw where raw_desc like '%" . $_SESSION['r_search'] . "%' or qty like '%" . $_SESSION['r_search'] . "%' ORDER BY raw_desc";
        $prod = $conn->query($prod);
        if($prod->num_rows > 0){
          $num = 0;
          while ($row = $prod->fetch_object()) {
            $num += 1;
            echo '<tr>';
              echo '<th scope="row">' . $num . '</th>';
              echo '<td>' . $row->raw_desc . '</td>';
              echo '<td>';
              if($row->qty <= 10){
                echo '<font color="red"><b>' . $row->qty . '</b></font>';
              }else{
                echo $row->qty;
              }
              echo '</td>';
              echo '<td>';
                echo '<a href = "admin/raw/'.$row->raw_id.'" class = "btn btn-sm btn-primary"> View </a> ';
              echo '</td>';
            echo '</tr>';
          }
        }
	}

?>

<?php
	if(isset($_GET['prodx_inve'])){
		$_SESSION['prodx_inve'] = mysqli_real_escape_string($conn, $_GET['prodx_inve']);
		$prod = "SELECT * FROM products where name like '%" . $_SESSION['prodx_inve'] . "%' or price like '%" . $_SESSION['prodx_inve'] . "%' ORDER BY name";
        $prod = $conn->query($prod);
        if($prod->num_rows > 0){
          $num = 0;
          while ($row = $prod->fetch_object()) {
            $num += 1;
            echo '<tr>';
              echo '<th scope="row">' . $num . '</th>';
              echo '<td>' . $row->name . '</td>';
              echo '<td>' . number_format($row->curstocks) . '</td>';
              echo '<td>';
                echo '<a href = "admin/prod_inve/'.$row->product_id.'" class = "btn btn-sm btn-primary"> View </a> ';
                //echo '<a onclick = "edit('.$row->raw_id.',\'raw\')" name = "edit" class = "btn btn-sm btn-success"> Edit </a>';
                //echo ' <a onclick = "return confirm(\'Are you sure?\')" href = "admin/delete?id=' . $row->raw_id . '" class = "btn btn-sm btn-danger"> Delete </a>';
              echo '</td>';
            echo '</tr>';
          }
        }
	}

?>