<?php
	
?>
<style type="text/css">
  #reportg p, ul li{
    font-size: 11px;
  }
  #reportg label{
    margin-bottom: 0px !important;
  }
  #reportg p{
    margin-top: 0px !important;
  }
  #reportg th, td{
    font-size: 11px !important;
  }
</style>
<div id="reportg" class="container-fluid">
	<?php
		if(isset($_GET['rep']) && $_GET['rep'] == 'sales') { 
			if(isset($_GET['void'])){
				$temp = 2;
				$page = "v_rep";
				$lab = "Void";
			}else{
				$temp = 0;
				$page = "s_rep";
				$lab = "Sales";
			}
		echo '<h2 align = "center">'. $lab . ' Report <h2>';
	?>
	<div class="card-body">
      <table class="table" align="center" id = "b_salesx">
        <thead class="thead-dark"  align="center">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Product Name</th>
            <th scope="col">Total Quantity <?php if($lab=='Sales'){ echo 'Sold';} else { echo $lab; }?></th>
            <th scope="col">Total Amount</th>
            <th scope="col">Branch</th>
            <!--<th scope="col">Trans. Date</th>-->
          </tr>
        </thead>
        <tbody align="center">
          <?php                    
            $prod = "SELECT *,sum(total) as s_tot, sum(qty) as q_tot FROM sales as a left join products as b on a.product_id = b.product_id left join branches as c on a.branch_id = c.branch_id where temp = ".$temp." and a.dttm between '" . $_SESSION['frd'] . " 00:00:00' and '" . $_SESSION['tod'] . " 23:59:59' group by a.product_id, a.branch_id";
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
<?php
		if(isset($_GET['print'])){
	    	echo '<script type = "text/javascript"> 
				$(document).ready(function(){ 
					setTimeout(function() { window.print(); },300);
					setTimeout(function() { window.location.href = "admin/'.$page.'"; },600); 
				});</script>';     
	  	}
	}elseif(isset($_GET['rep']) && $_GET['rep'] == 'inve'){
		echo '<h1 align="center"> Inventory Report </h1>';
?>
  <div>
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
<?php
		if(isset($_GET['print'])){
			echo '<script type = "text/javascript"> 
				$(document).ready(function(){ 
					setTimeout(function() { window.print(); },500);
					setTimeout(function() { window.location.href = "admin/i_rep"; },1000); 
				});</script>';     
		}
	}elseif(isset($_GET['rep']) && $_GET['rep'] == 'box'){
    if(isset($_GET['bo'])){
      $rep = "Back Ordered";
      $x = 0;
    }else{
      $rep = "Diposed";
      $x = 1;
    }
    echo '<h2 align = "center"> '.$rep.' Report <h2>';
?>
    <table class="table" align="center" id = "bod_salesx">
      
      <thead class="thead-dark"  align="center">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Product Name</th>
          <th scope="col">Disposed Quantity</th>
        </tr>
      </thead>
      <tbody align="center">
        <?php                        
          $prod = "SELECT *,a.qty as qtyxx FROM dispose_bo as a left join products as b on a.product_id = b.product_id where a.dttm between '" . $_SESSION['frd'] . " 00:00:00' and '" . $_SESSION['tod'] . " 23:59:59' and a.type = $x order by b.name";
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
<?php
    if(isset($_GET['print'])){
      echo '<script type = "text/javascript"> 
        $(document).ready(function(){ 
          setTimeout(function() { window.print(); },500);
          setTimeout(function() { window.location.href = "admin/bo_rep"; },1000); 
        });</script>';     
    }
  }
?>