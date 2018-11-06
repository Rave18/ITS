
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
                <h1>Reports</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li><a href="./">Dashboard</a></li>
                    <li class="active">Back Ordered Report</li>
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
                  <strong class="card-title">Return To Supplier Product</strong>
                  <div class="form-inline">                  
                    <label>Fr:&nbsp;&nbsp;</label><input onkeyup = "bod_sales(0)" type = "date" class="form-control form-control-sm" name = "frd" value="<?php echo date("Y-m-d", strtotime($_SESSION['frd']));?>">
                    &nbsp;&nbsp;&nbsp;<label>To:&nbsp;&nbsp;</label><input onkeyup = "bod_sales(0)" type = "date" class="form-control form-control-sm" name = "tod" value="<?php echo date("Y-m-d", strtotime($_SESSION['tod']));?>">
                    &nbsp;&nbsp;&nbsp;<a href = "admin/print?rep=box&print&bo" class="btn btn-sm btn-danger"> Print </a>
                  </div>
              </div>
              <div class="card-body" id = "reportg">
                  <table class="table" align="center">
                    <thead class="thead-dark"  align="center">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Back Ordered Quantity</th>
                      </tr>
                    </thead>
                    <tbody align="center" id ="bod_salesx">
                      <?php                        
                        $prod = "SELECT *,a.qty as qtyxx FROM dispose_bo as a left join products as b on a.product_id = b.product_id where a.dttm between '" . $_SESSION['frd'] . " 00:00:00' and '" . $_SESSION['tod'] . " 23:59:59' and a.type = 0 order by b.name";
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
  if(isset($_SESSION['bod_sales'])){
    echo '<script> bod_sales(0);</script>';
  }
?>