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
                        <li><a href = "./">Menu</a></li>
                        <li class="active">View Products</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content mt-3">
        <div class="col-sm-6 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <a href = "javascript:javascript:history.go(-1)" class="btn btn-danger btn-sm"><span class="fa fa-arrow-left"></span></a>
                    <strong class="card-title">Select Product</strong>
                </div>
                <div class="card-body">
                    <?php
                        $cat = "SELECT * FROM products AS a LEFT JOIN b_stocks as b on a.product_id = b.product_id where a.category = '" . $_GET['view'] . "' and b.branch_id = '" . $access->group_id . "' and qty > 0 ORDER BY name ASC";
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
                        <div class="col-sm-6 col-lg-6">
                            <!--
                                <a onclick="sales(<?php echo $row->product_id;?>)">
                                <a href = "main/view/<?php echo $row->product_id;?>">
                            -->
                                <div class="card text-white bg-flat-color-<?php echo $num;?>" align="center">
                                    <div class="card-body">
                                        <h1><?php echo $row->name; ?></h1>
                                        <h3>P <?php echo $row->price; ?></h3>										
										<h3> ( <?php echo $row->qty; ?> ) </h3>
                                        <input type = "number" name = "qty<?php echo $row->product_id;?>" style="text-align:center;color: black;" value="0" min = '0' autocomplete = "off"/>
                                        <input type="hidden" name = "price<?php echo $row->product_id;?>" value = "<?php echo $row->price; ?>"/>
                                        <button onclick="cart(<?php echo $row->product_id;?>);"><span class="fa fa-plus"></span> Add </button>
                                    </div>
                                </div>
                            <!--</a>-->
                        </div>
                    <?php
                            }
                        }
                    ?>       
                </div>  
            </div>          
        </div>
        <div class="col-xs-6 col-lg-4">
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
                                while ($row = $select->fetch_object()) {
                                    $id = $row->sales_id;
                                    echo '<li> '. $row->name . ' @ ' . $row->price  . ' x ' . $row->qty . ' [ ' . number_format($row->price * $row->qty,2) . ' ] </li>';    
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