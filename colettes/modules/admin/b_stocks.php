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
                    <li><a href="./"><?php if($access->level > 1){ ?> Dashboard <?php }else{ ?> Main <?php } ?></a></li>
                    <li class="active">Branch Inventory</li>
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
                  <strong class="card-title">Branch Stocks</strong>
                  <?php if($access->level > 1){ ?> 
                    <button class="btn btn-primary btn-sm pull-right" onclick = "addx('product')"><span class="fa fa-plus"></span></button>
                  <?php } ?>
                  <div class="form-inline"><br>
                    <select class="form-control form-control-sm" onchange = "branch_inve()" name = "branch">
                      <option value="all"> All branch </option>
                      <?php
                        $slect = "SELECT * FROM branches ORDER BY b_name ASC";
                        $slect = $conn->query($slect);
                        if($slect->num_rows > 0){
                            while ($row = $slect->fetch_object()) {
                              $slected = "";
                              if(isset($_SESSION['branch_inve'])){
                                  if($_SESSION['branch_inve'] == $row->branch_id){
                                    $slected = " selected ";
                                    echo '<script> branch_inve(' . $row->branch_id . ')</script>';
                                  }
                              }
                              echo '<option ' . $slected . ' value = "' . $row->branch_id . '"> ' . $row->b_name . ' </option>';
                            }
                        }
                      ?>
                    </select>&nbsp;&nbsp;
                    <input name = "search" onkeyup = "branch_inve()" <?php if(isset($_SESSION['search'])){ echo " value = '" . $_SESSION['search'] . "' ";} ?> style="min-width: 350px;" type = "text" class="form-control form-control-sm" placeholder = "Search">
                  </div>
              </div>
              <div class="card-body">
                  <table class="table" align="center" id = "b_inve">
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
                        $prod = "SELECT * FROM b_stocks as b LEFT JOIN products as a on a.product_id = b.product_id LEFT JOIN branches as c on b.branch_id = c.branch_id ORDER BY a.name asc";
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
                  </table>
              </div>
          </div>
        </div>
      </div>
  </div>
</div>
<?php
  if(isset($_POST['add'])){
    if(!empty($_POST['qty']) && !empty($_POST['p_id'])){
      $stocks = "SELECT * FROM products WHERE product_id = '" . mysqli_real_escape_string($conn, $_POST['p_id']) . "'"; 
      if($conn->query($stocks)->num_rows <= 0){
        alert("admin/b_stocks", "No record found.");
        exit;
      }else{
        $stocks = $conn->query($stocks)->fetch_object();
        if($_POST['qty'] > $stocks->curstocks){
          alert("admin/b_stocks", "Not enought stocks.");
          exit;
        }
      }
      $slect = "SELECT * FROM b_stocks WHERE product_id = '" . mysqli_real_escape_string($conn, $_POST['p_id']) . "' and branch_id = '" . $_POST['branch'] . "'";
      if($conn->query($slect)->num_rows > 0){
        $stmt = $conn->prepare("UPDATE b_stocks SET qty = (qty + ?)  WHERE product_id = ? and branch_id = ?");
        $stmt->bind_param("sii", $_POST['qty'], $_POST['p_id'], $_POST['branch']);
      }else{
        $stmt = $conn->prepare("INSERT INTO b_stocks (product_id, qty, branch_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $_POST['p_id'], $_POST['qty'], $_POST['branch']); 
      }
      if($stmt->execute() === TRUE){
        $stmt2 = $conn->prepare("UPDATE products SET curstocks = (curstocks - ?) WHERE product_id = ?");
        $stmt2->bind_param("di", $_POST['qty'], $_POST['p_id']);
        $stmt2->execute();
        alert("admin/b_stocks", "Adding successfull.");
      }
    }else{
      alert("admin/b_stocks", "Check your details");
    }
  }
  if(isset($_SESSION['branch_inve'])){
    echo '<script> branch_inve(); </script>';
  }
?>