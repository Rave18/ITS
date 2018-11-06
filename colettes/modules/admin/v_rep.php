
<?php
  if( (isset($_SESSION['frd']) && stristr($_SESSION['frd'],'unde') !== false) && (isset($_SESSION['tod']) && stristr($_SESSION['tod'],'unde') !== false)){
    $_SESSION['frd'] = date("Y-m-d");
    $_SESSION['tod'] = date("Y-m-d");
  }
  if(!isset($_SESSION['frd']) || !isset($_SESSION['tod'])){
    $_SESSION['frd'] = date("Y-m-d");
    $_SESSION['tod'] = date("Y-m-d");
  }
?>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Sales</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li><a href="./">Dashboard</a></li>
                    <li class="active">Void Report</li>
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
                  <strong class="card-title">Cancelled Transaction Report</strong>
                  <div class="form-inline"><br>
                    <select class="form-control form-control-sm" name = "br" onchange = "b_sales(2)">
                      <option value="all"> All branch </option>
                      <?php
                        $slect = "SELECT * FROM branches ORDER BY b_name ASC";
                        $slect = $conn->query($slect);
                        if($slect->num_rows > 0){
                            while ($row = $slect->fetch_object()) {
                              $slected = "";
                              if(isset($_SESSION['b_sales'])){
                                  if($_SESSION['b_sales'] == $row->branch_id){
                                    $slected = " selected ";
                                    //
                                  }
                              }
                              echo '<option ' . $slected . ' value = "' . $row->branch_id . '"> ' . $row->b_name . ' </option>';
                            }
                        }
                      ?>
                    </select>                    
                    &nbsp;&nbsp;&nbsp;<label>Fr:&nbsp;&nbsp;</label><input onkeyup = "b_sales(2)" type = "date" class="form-control form-control-sm" name = "frd" value="<?php echo date("Y-m-d", strtotime($_SESSION['frd']));?>">
                    &nbsp;&nbsp;&nbsp;<label>To:&nbsp;&nbsp;</label><input onkeyup = "b_sales(2)" type = "date" class="form-control form-control-sm" name = "tod" value="<?php echo date("Y-m-d", strtotime($_SESSION['tod']));?>">
                    &nbsp;&nbsp;&nbsp;<a href = "admin/print?rep=sales&print&void" class="btn btn-sm btn-danger"> Print </a>
                  </div>
              </div>
              <div class="card-body" id = "reportg">
                  <table class="table" align="center" id = "b_salesx">
                    <thead class="thead-dark"  align="center">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Total Quantity Sold</th>
                        <th scope="col">Total Amount</th>
                        <th scope="col">Branch</th>
                        <!--<th scope="col">Trans. Date</th>-->
                      </tr>
                    </thead>
                    <tbody align="center">
                      <?php                        
                        $prod = "SELECT *,sum(total) as s_tot, sum(qty) as q_tot FROM sales as a left join products as b on a.product_id = b.product_id left join branches as c on a.branch_id = c.branch_id where temp = 2 and a.dttm between '" . $_SESSION['frd'] . " 00:00:00' and '" . $_SESSION['tod'] . " 23:59:59' group by a.product_id, a.branch_id";
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
                              echo '<td>' . number_format($row->s_tot) . '</td>';
                              echo '<td>' . $row->b_name . '</td>';
                              //echo '<td>' . ddatet($row->dttm) . 'a</td>';
                            echo '</tr>';
                          }
                          echo '<tr><td></td><td align="right"><b>Total</b></td><td><b>' . $total_q . '</b></td><td><b>' . number_format($total_s,2) . '</b></td><td></td><td></td></tr>';
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
  if(isset($_SESSION['b_sales'])){
    echo '<script> b_sales(2);</script>';
  }
?>