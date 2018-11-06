<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Inventory</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li><a href="./">Dashboard</a></li>
                    <li class="active">Product Stocks</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content mt-3">
  <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
              <div class="card-header">
                  <?php
                    if(isset($_GET['view'])){
                      echo '<strong class="card-title">Deliviries of Products</strong>';
                      echo '<a style = "margin-left: 2px; margin-right: 2px;" href = "javascript:javascript:history.go(-1)" class="btn btn-danger btn-sm  pull-right"><span class="fa fa-arrow-left"></span></a>&nbsp;';
                    }else{
                      echo '<strong class="card-title">Products</strong>';
                    }
                  ?>
                  <button class="btn btn-primary btn-sm pull-right" onclick = "addx('prod_inve')"><span class="fa fa-plus"></span></button> 
                  <?php
                    if(!isset($_GET['view'])){
                  ?>
                  <div class="form-inline">
                    <input name = "search" onkeyup = "prodx_inve(this.value)" <?php if(isset($_SESSION['prodx_inve'])){ echo " value = '" . $_SESSION['prodx_inve'] . "' ";} ?> style="min-width: 350px;" type = "text" class="form-control form-control-sm" placeholder = "Search">
                  </div>
                  <?php
                    }
                  ?>
              </div>
            <?php if(isset($_GET['view'])){ ?>
              <div class="card-body">
                  <table class="table" align="center">
                    <thead class="thead-dark"  align="center">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product</th>
                        <th scope="col">Delivery Date / Production</th>
                        <th scope="col">Quantity (kg)</th>
                        <!--<th scope="col">Action</th>-->
                      </tr>
                    </thead>
                    <tbody align="center">
                      <?php
                        if($_GET['recipe'] == 0){
	                    	$prod = "SELECT a.product_id, a.name, b.d_date, b.qty FROM p_stocks as b LEFT JOIN products as a on b.product_id = a.product_id WHERE b.product_id = '" . mysqli_real_escape_string($conn, $_GET['view']) . "' ORDER BY b.d_date asc";
                        }else{
	                    	$prod = "SELECT a.product_id, a.name, b.dttm, b.qty, a.persets FROM production as b LEFT JOIN products as a on b.product_id = a.product_id WHERE b.product_id = '" . mysqli_real_escape_string($conn, $_GET['view']) . "' ORDER BY b.dttm asc";
                        }
                        $prod = $conn->query($prod);
                        if($prod->num_rows > 0){
                          $num = 0;
                          while ($row = $prod->fetch_object()) {
                            $num += 1;
                            echo '<tr>';
                            echo '<th scope="row">' . $num . '</th>';
                            echo '<td>' . $row->name . '</td>';
                            if($_GET['recipe'] == 0){
			                 	echo '<td>' . ddate($row->d_date) . '</td>';
                            	echo '<td>' . $row->qty . '</td>';
			                }else{
			                 	echo '<td>' . ddate($row->dttm) . '</td>';
                            	echo '<td>' . $row->qty * $row->persets . '</td>';
			                }
                              //echo '<td>';
                              //  echo '<a onclick = "edit('.$row->raw_id.',\'raw\')" name = "edit" class = "btn btn-sm btn-success"> Edit </a>';
                              //  echo ' <a onclick = "return confirm(\'Are you sure?\')" href = "admin/delete?id=' . $row->raw_id . '" class = "btn btn-sm btn-danger"> Delete </a>';
                              //echo '</td>';
                            echo '</tr>';
                          }
                        }
                      ?>                   
                    </tbody>
                  </table>
              </div>
            <?php }else{ ?>
              <div class="card-body">
                  <table class="table" align="center">
                    <thead class="thead-dark"  align="center">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Produt</th>
                        <th scope="col">Current Stocks</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody align="center"  id = "prodx_inve">
                      <?php
                        $prod = "SELECT * FROM products ORDER BY name";
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
                                echo '<a href = "admin/prod_inve/'.$row->product_id.'&recipe='.$row->wrecipe.'" class = "btn btn-sm btn-primary"> View </a> ';
                                //echo '<a onclick = "edit('.$row->raw_id.',\'raw\')" name = "edit" class = "btn btn-sm btn-success"> Edit </a>';
                                //echo ' <a onclick = "return confirm(\'Are you sure?\')" href = "admin/delete?id=' . $row->raw_id . '" class = "btn btn-sm btn-danger"> Delete </a>';
                              echo '</td>';
                            echo '</tr>';
                          }
                        }
                      ?>                   
                    </tbody>
                  </table>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
  </div>
</div>
<?php
  if(isset($_POST['add'])){
    if(!empty($_POST['d_date']) && !empty($_POST['qty']) && !empty($_POST['p_id'])){
      $stmt = $conn->prepare("INSERT INTO p_stocks (product_id, d_date, qty) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $_POST['p_id'], $_POST['d_date'], $_POST['qty']);
      if($stmt->execute() === TRUE){
        $stmt2 = $conn->prepare("UPDATE products SET curstocks = (curstocks + ?) WHERE product_id = ?");
        $stmt2->bind_param("di", $_POST['qty'], $_POST['p_id']);
        $stmt2->execute();
        alert("admin/prod_inve/".$_POST['p_id']."&recipe=".$_GET['recipe'], "Adding successfull.");
      }
    }else{
      if(!isset($_POST['p_id'])){
        $_POST['p_id'] = "";
      }
      alert("admin/prod_inve/".$_POST['p_id']."&recipe=".$_GET['recipe'], "Check your details");
    }
  }

?>
<?php
  if(isset($_SESSION['prodx_inve'])){
    echo '<script> prodx_inve("'.$_SESSION['prodx_inve'].'");</script>';
  }
?>