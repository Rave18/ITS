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
                    <li class="active">Raw Material Stocks</li>
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
                      echo '<strong class="card-title">Deliviries of Raw Materials</strong>';
                      echo '<a href = "javascript:javascript:history.go(-1)" class="btn btn-danger btn-sm  pull-right"><span class="fa fa-arrow-left"></span></a>&nbsp;';
                    }else{
                      echo '<strong class="card-title">Raw Materials</strong>';
                    }
                  ?>
                  <button class="btn btn-primary btn-sm pull-right" onclick = "addx('raw')"><span class="fa fa-plus"></span></button><br>
                  <?php
                    if(!isset($_GET['view'])){
                  ?>
                  <div class="form-inline">
                    <input name = "search" onkeyup = "r_search(this.value)" <?php if(isset($_SESSION['r_search'])){ echo " value = '" . $_SESSION['r_search'] . "' ";} ?> style="min-width: 350px;" type = "text" class="form-control form-control-sm" placeholder = "Search">
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
                        <th scope="col">Raw Material</th>
                        <th scope="col">Delivery Date</th>
                        <th scope="col">Quantity (kg)</th>
                        <!--<th scope="col">Action</th>-->
                      </tr>
                    </thead>
                    <tbody align="center">
                      <?php
                        $prod = "SELECT a.raw_id, a.raw_desc, b.d_date, b.qty FROM r_stocks as b LEFT JOIN raw as a on b.raw_id = a.raw_id WHERE b.raw_id = '" . mysqli_real_escape_string($conn, $_GET['view']) . "' ORDER BY b.d_date asc";
                        $prod = $conn->query($prod);
                        if($prod->num_rows > 0){
                          $num = 0;
                          while ($row = $prod->fetch_object()) {
                            $num += 1;
                            echo '<tr>';
                              echo '<th scope="row">' . $num . '</th>';
                              echo '<td>' . $row->raw_desc . '</td>';
                              echo '<td>' . ddate($row->d_date) . '</td>';
                              echo '<td>';
                              if($row->qty <= 10){
                                echo '<font color="red"><b>' . $row->qty . '</b></font>';
                              }else{
                                echo $row->qty;
                              }
                              echo '</td>';
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
                        <th scope="col">Raw Material</th>
                        <th scope="col">Current Stocks (KG)</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody align="center"  id = "r_search">
                      <?php
                        $prod = "SELECT * FROM raw ORDER BY raw_desc";
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
    if(!empty($_POST['d_date']) && !empty($_POST['qty'])){
      if(isset($_POST['checkbox'])){
        $sql = "SELECT * FROM raw WHERE raw_desc = '" . mysqli_real_escape_string($conn, $_POST['new_raw']) . "'";
        if($conn->query($sql)->num_rows > 0){
          alert("admin/raw", "Already exist.");
          exit;
        }
        $stmt = $conn->prepare("INSERT INTO raw (raw_desc) VALUES (?)");
        $stmt->bind_param("s", $_POST['new_raw']);
        if($stmt->execute() === TRUE){
          $_POST['raw'] = $conn->insert_id;
        }
      }
      $stmt = $conn->prepare("INSERT INTO r_stocks (raw_id, d_date, qty) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $_POST['raw'], $_POST['d_date'], $_POST['qty']);
      if($stmt->execute() === TRUE){
        $stmt2 = $conn->prepare("UPDATE raw SET qty = (qty + ?) WHERE raw_id = ?");
        $stmt2->bind_param("di", $_POST['qty'], $_POST['raw']);
        $stmt2->execute();
        alert("admin/raw", "Adding successfull.");
      }
    }else{
      alert("admin/raw", "Check your details");
    }
  }
?>
<?php
  if(isset($_SESSION['r_search'])){
    echo '<script> r_search("'.$_SESSION['r_search'].'");</script>';
  }
?>