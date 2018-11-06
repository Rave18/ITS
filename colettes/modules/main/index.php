        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Menu</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Menu</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <?php
                if(isset($_GET['id'])){
                    $_GET['id'] =  mysqli_real_escape_string($conn, $_GET['id']);
                    $stmt = "SELECT * FROM transfers where transfer_id = '" .$_GET['id'] . "'";
                    if($conn->query($stmt)->num_rows <= 0){
                        alert("", "No record found.");
                        exit;
                    } 
                    $stmtup = $conn->prepare("UPDATE transfers SET state = ? WHERE transfer_id = ?");
                    $stmtup->bind_param("ii", $_GET['state'], $_GET['id']);
                    if($stmtup->execute() === TRUE){
                        if($_GET['state'] == "1"){
                            $res = $conn->query($stmt)->fetch_object();
                            $up = $conn->prepare("UPDATE b_stocks SET qty = (qty - ?) WHERE branch_id = ? and product_id = ?");
                            $up->bind_param("iii", $res->qty, $res->branch_id, $res->product_id);
                            if($up->execute() === TRUE){
                                $check = "SELECT * FROM b_stocks WHERE product_id = '" . $res->product_id . "' and branch_id = '" . $res->to_branch . "'";
                                if($conn->query($check)->num_rows > 0){
                                    $upx = $conn->prepare("UPDATE b_stocks SET qty = (qty + ?) WHERE product_id = ? and branch_id = ?");
                                    $upx->bind_param("iii", $res->qty, $res->product_id, $res->to_branch);
                                }else{
                                    $upx = $conn->prepare("INSERT INTO b_stocks (qty, product_id, branch_id) VALUES (?, ?, ?)");
                                    $upx->bind_param("iii", $res->qty, $res->product_id, $res->to_branch);
                                }                                
                                if($upx->execute() === TRUE){
                                    alert("", "Transfer approved.");
                                }else{
                                    echo mysqli_error($conn);
                                }                                
                            }
                        }else{
                            alert("", "Transfer disapproved.");
                        }
                    }
                }
                $transfer = "SELECT *,a.dttm as dt FROM transfers as a left join branches as b on a.branch_id = b.branch_id left join products as c on a.product_id = c.product_id where a.to_branch = '" . $_SESSION['branch_id'] . "' and a.state = 0";
                $transfer = $conn->query($transfer);
                if($transfer->num_rows > 0){
            ?>
                    <div class ="col-lg-12">
                        <div class="card">
                          <div class="card-header">
                              <strong class="card-title">Transfer of Products</strong>
                          </div>
                          <div class="card-body">
                            <table class="table form-control-sm">
                                <thead class="thead-dark">
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Branch (Fr.) </th>
                                    <th scope="col">Product</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                <?php
                                    $num = 0;
                                    while ($row = $transfer->fetch_object()) {
                                        $num += 1;
                                        echo '<tr>';
                                            echo '<td>' . $num . '</td>';
                                            echo '<td>' . $row->b_name . '</td>';
                                            echo '<td>' . $row->name . '</td>';
                                            echo '<td>' . $row->qty . '</td>';
                                            echo '<td><a href = "?id='.$row->transfer_id.'&state=1" class = "btn btn-primary btn-sm"> Approve </a>';
                                            echo ' <a href = "?id='.$row->transfer_id.'&state=2" class = "btn btn-danger btn-sm"> Disapprove </a></td>';
                                        echo '</tr>';
                                    }
                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="col-xs-12 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Select Category</strong>
                    </div>
                    <div class="card-body">
                        <?php
                            $cat = "SELECT * FROM category ORDER BY c_name ASC";
                            $cat = $conn->query($cat);
                            if($cat->num_rows > 0){
                                $num = 1;
                                while ($row = $cat->fetch_object()) {
                                    if($num <= 3){
                                        $num += 1;
                                    }else{
                                        $num = 2;
                                    }
                                    
                        ?>
                            <div class="col-sm-6 col-lg-4">
                                <!--<a onclick="sales(<?php echo $row->category_id;?>)">-->
                                <a href = "main/view/<?php echo $row->category_id;?>">
                                    <div class="card text-white bg-flat-color-<?php echo $num;?>" align="center">
                                        <div class="card-body">
                                            <h1><?php echo $row->c_name; ?></h1>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php
                                }
                            }
                        ?>       
                    </div>  
                </div>          
            </div>
            <div class="col-xs-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Cart</strong>
                    </div>
                    <div class="card-body" id = "cart">
                        <?php
                            $select = "SELECT * FROM sales as a LEFT JOIN products as b on a.product_id = b.product_id where a.branch_id = '" . $_SESSION['branch_id'] . "' and a.temp = 1";
                            $select = $conn->query($select);
                            if($select->num_rows > 0){
                                echo '<div class="form-group" style = "margin-left: 10px;">';
                                    echo '<ul>';
                                    $qty = 0;
                                    $total = 0;
                                    $id = "";
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
                                echo '<input type = "text" onchange = "change(this.value,'.$total .')" class = "form form-control form-control-sm" pattern = "[0-9]*" name = "tendered" placeholder = "Enter amount" id = "tenderedd"/>';
                                echo '<hr>';
                                echo '<p id = "xchange"> Change: </p>';
                                echo '<div align="center"><hr>';
                                    echo '<button class = "btn btn-sm btn-primary" onclick = "return confirm(\'Are you sure?\')?checkout('.$access->group_id.','.$id.'):\'\';"> Checkout </button>';
                                    echo '&nbsp;<button class = "btn btn-sm btn-danger" onclick = "return confirm(\'Are you sure?\')?clearcart('.$access->group_id.'):\'\';"> Clear Cart </button>';
                                echo '</div>';
                            }
                        ?>
                    </div>  
                </div>                
            </div>
        </div> <!-- .content -->
    </div><!-- /#right-panel -->
