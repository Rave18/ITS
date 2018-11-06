<?php
  if( (isset($_SESSION['frdx']) && stristr($_SESSION['frdx'],'unde') !== false) && (isset($_SESSION['todx']) && stristr($_SESSION['todx'],'unde') !== false)){
    $_SESSION['frdx'] = date("Y-m-d");
    $_SESSION['todx'] = date("Y-m-d");
  }
  if(!isset($_SESSION['frdx']) || !isset($_SESSION['todx'])){
    $_SESSION['frdx'] = date("Y-m-d");
    $_SESSION['todx'] = date("Y-m-d");
  }
?>
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
                    <li class="active">Inventory Report</li>
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
                  <strong class="card-title">Inventory Report</strong>
                  <div class="form-inline"><br>
                    <select class="form-control form-control-sm" name = "brx" onchange = "b_inve()">
                      <option value="all"> All branch </option>
                      <?php
                        $slect = "SELECT * FROM branches ORDER BY b_name ASC";
                        $slect = $conn->query($slect);
                        if($slect->num_rows > 0){
                            while ($row = $slect->fetch_object()) {
                              $slected = "";
                              if(isset($_SESSION['b_inve'])){
                                  if($_SESSION['b_inve'] == $row->branch_id){
                                    $slected = " selected ";                                    
                                  }
                              }
                              echo '<option ' . $slected . ' value = "' . $row->branch_id . '"> ' . $row->b_name . ' </option>';
                            }
                        }
                      ?>
                    </select>                    
                    &nbsp;&nbsp;&nbsp;<label>Fr:&nbsp;&nbsp;</label><input onkeyup = "b_inve()" type = "date" class="form-control form-control-sm" name = "frdx" value="<?php echo date("Y-m-d", strtotime($_SESSION['frdx']));?>">
                    &nbsp;&nbsp;&nbsp;<label>To:&nbsp;&nbsp;</label><input onkeyup = "b_inve()" type = "date" class="form-control form-control-sm" name = "todx" value="<?php echo date("Y-m-d", strtotime($_SESSION['todx']));?>">
                    &nbsp;&nbsp;&nbsp;<a href = "admin/print?rep=inve&print" class="btn btn-sm btn-danger"> Print </a>
                  </div>
              </div>
              <div class="card-body">
                  <table class="table" align="center" id = "b_invex">
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
                        $prod = "SELECT *,sum(d.qty) as q_b, ifnull(sum(a.qty),0) as q_tot FROM sales as a left join products as b on a.product_id = b.product_id left join branches as c on a.branch_id = c.branch_id LEFT JOIN b_stocks AS d ON a.branch_id = d.branch_id and a.product_id = d.product_id where temp = 0 and a.dttm between '" . $_SESSION['frdx'] . " 00:00:00' and '" . $_SESSION['todx'] . " 23:59:59' group by a.product_id, a.branch_id";
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
                  </table>
              </div>
          </div>
        </div>
      </div>
  </div>
</div>
<?php
  if(isset($_SESSION['b_inve'])){
    echo '<script> b_inve();</script>';
  }
?>