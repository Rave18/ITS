<style type="text/css">
    #bar_g{
        border: 1px black solid;
        border-radius: 5px;
        margin-bottom: 20px;
        padding: 5px !important;
    }
</style>
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Dashboard</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content mt-3">
            <?php
                if(isset($_GET['id'])){
                    $_GET['id'] =  mysqli_real_escape_string($conn, $_GET['id']);
                    $stmt = "SELECT * FROM transfers where transfer_id = '" .$_GET['id'] . "' and to_branch=0";
                    if($conn->query($stmt)->num_rows <= 0){
                        alert("", "No record found.");
                        exit;
                    } 
                    $stmtup = $conn->prepare("UPDATE transfers SET state = ? WHERE transfer_id = ? and to_branch = 0");
                    $stmtup->bind_param("ii", $_GET['state'], $_GET['id']);
                    if($stmtup->execute() === TRUE){
                        if($_GET['state'] == "1"){
                            $res = $conn->query($stmt)->fetch_object();
                            $up = $conn->prepare("UPDATE b_stocks SET qty = (qty - ?) WHERE branch_id = ? and product_id = ?");
                            $up->bind_param("iii", $res->qty, $res->branch_id, $res->product_id);
                            if($up->execute() === TRUE){
                                $upx = $conn->prepare("UPDATE products SET curstocks = (curstocks + ?) WHERE product_id = ?");
                                $upx->bind_param("ii", $res->qty, $res->product_id);
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
                $transfer = "SELECT *,a.dttm as dt FROM transfers as a left join branches as b on a.branch_id = b.branch_id left join products as c on a.product_id = c.product_id where a.state = 0  and a.to_branch=0";
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
            <?php
                }
                //critical level
                $critical = "SELECT raw_desc as namex, concat(qty,' KG') as qtyx, concat('Raw') as typex FROM raw WHERE qty <= 20 UNION ALL ";
                $critical .= "SELECT name as namex, concat(curstocks, ' pc/s') as qtyx, concat('Product') as typex FROM products WHERE curstocks <= 10 ORDER BY typex,qtyx ASC limit 20";
                $critical = $conn->query($critical);
                if($critical->num_rows > 0){
            ?>
                    <div class ="col-lg-12">
                        <div class="card">
                          <div class="card-header">
                              <strong class="card-title">Critical Level</strong>
                          </div>
                          <div class="card-body">
                            <table class="table form-control-sm">
                                <thead class="thead-dark">
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Quantity (KG)</th>
                                    <th scope="col">Type</th>
                                  </tr>
                                </thead>
                                <tbody>
                <?php
                                    $num = 0;
                                    while ($row = $critical->fetch_object()) {
                                        $num += 1;
                                        echo '<tr>';
                                            echo '<td>' . $num . '</td>';
                                            echo '<td>' . $row->namex . '</td>';
                                            echo '<td><font color = "red"><b>' . str_replace(".00", "", $row->qtyx) . '</b></font></td>';
                                            echo '<td><b>' . $row->typex . '</b></td>';
                                        echo '</tr>';
                                    }
                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
                }
                $profit = "SELECT sum(total) as profit,sum(qty) as sold, count(*) as cust FROM sales where temp = 0";
                $profit = $conn->query($profit)->fetch_object();
            ?>  
            <div class="col-xl-3 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="stat-widget-one">
                            <div class="stat-icon dib"><i class="ti-money text-success border-success"></i></div>
                            <div class="stat-content dib">
                                <div class="stat-text">Total Profit</div>
                                <div class="stat-digit"><?php echo number_format($profit->profit,2); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="stat-widget-one">
                            <div class="stat-icon dib"><i class="ti-layout-grid2 text-warning border-warning"></i></div>
                            <div class="stat-content dib">
                                <div class="stat-text">Sold Product</div>
                                <div class="stat-digit"><?php echo number_format($profit->sold); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="stat-widget-one">
                            <div class="stat-icon dib"><i class="ti-user text-primary border-primary"></i></div>
                            <div class="stat-content dib">
                                <div class="stat-text">Served Customer</div>
                                <div class="stat-digit"><?php echo number_format($profit->cust); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                $production = "SELECT sum(qty) as prod FROM production";
                $production = $conn->query($production)->fetch_object();
            ?>
            <div class="col-xl-3 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="stat-widget-one">
                            <div class="stat-icon dib"><i class="ti-pie-chart text-warning border-warning"></i></div>
                            <div class="stat-content dib">
                                <div class="stat-text">Total Productions</div>
                                <div class="stat-digit"><?php echo number_format($production->prod); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12" id="bar_g">
                <div id = "widgetChart2" style="position: relative;"></div>   
            </div>
            <div class="col-lg-12" id="bar_g">
                <div id = "widgetChart1"></div>   
            </div>
        </div>
    </div><!-- /#right-panel -->
    <!-- for charts --> 
    <script type="text/javascript">
        $(function () {
            $('#widgetChart1').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Raw Material Stocks'
                },
                subtitle: {
                    text: 'as of <?php echo date("M j, Y h:m A");?>'
                },
                xAxis: {
                    categories: [
                        <?php
                            $slect = "SELECT * FROM raw ORDER BY raw_desc ASC";
                            $slect = $conn->query($slect);
                            if($slect->num_rows > 0){
                                $num = 0;
                                $arr_stock = array();
                                $stock = "";
                                while ($row = $slect->fetch_object()) {
                                    $num += 1;
                                    $desc = '"'. $row->raw_desc . '"';
                                    $stock .= $row->qty;
                                    if($num < $slect->num_rows){
                                        $desc .= ',';
                                        $stock .= ',';
                                    }
                                    echo $desc;
                                }
                            }
                        ?>
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'KG'
                    },
                    labels: {
                        formatter: function () {
                            return Highcharts.numberFormat(this.value,0);
                        }
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0"> </td>' +
                        '<td style="padding:0"><b>{point.y:,.2f} KG </b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:,.2f}'
                        },
                    },
                    column: {
                        zones: [{
                            value: 50, // Values up to 10 (not including) ...
                            color: 'red' // ... have the color blue.
                        },{
                            //color: 'blue' // Values from 10 (including) and up have the color red
                        }]
                    }
                },
                series: [{
                    name: 'Current Stock',
                    data: [ <?php echo $stock;?> ]

                }],
            });
        });
        $(function () {
            $('#widgetChart2').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Branch Inventory'
                },
                subtitle: {
                    text: 'as of <?php echo date("M j, Y h:m A");?>'
                },
                xAxis: {
                    categories: [
                        <?php
                            $slect = "SELECT * FROM branches ORDER BY b_name ASC";
                            $slect = $conn->query($slect);
                            if($slect->num_rows > 0){
                                $num = 0;
                                while ($row = $slect->fetch_object()) {                                    
                                    $desc = '"'. $row->b_name . '"';
                                    $num += 1;                                    
                                    if($num < $slect->num_rows){
                                        $desc .= ',';
                                    }
                                    echo $desc;
                                }
                            }
                        ?>
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'KG'
                    },
                    labels: {
                        formatter: function () {
                            return Highcharts.numberFormat(this.value,0);
                        }
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0"><b>{point.y:,.2f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:,.2f}'
                        },
                    },
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                <?php
                    $slect = "SELECT * FROM products where wrecipe >= 0 ORDER BY name ASC";
                    $slect = $conn->query($slect);
                    if($slect->num_rows > 0){
                        $num = 0;
                        $stock = "";
                        $num2 = 0;
                        $num3 = 0;
                        while ($row = $slect->fetch_object()) {
                            $num += 1;
                            $stock .= 'name: "' . $row->name .'"';
                            $stock .= ",data: [";
                            $slect_p ="SELECT * FROM branches ORDER BY b_name ASC"; 
                            $slect_p = $conn->query($slect_p);
                            while ($row2 = $slect_p->fetch_object()) {
                                $num3 += 1;
                                $slect2 = "SELECT * FROM b_stocks as a left join products as b on a.product_id = b.product_id where a.product_id = '" . $row->product_id . "' and a.branch_id = '" . $row2->branch_id . "' GROUP BY b.product_id ORDER BY `name` ASC";
                                $slect2 = $conn->query($slect2);
                                if($slect2->num_rows > 0){
                                    while ($row2 = $slect2->fetch_object()) {
                                        $num2 = $num2 + 1;
                                        $stock .= $row2->qty.',';
                                        if($num2 > $slect2->num_rows){
                                            //$stock .= ",";
                                        }
                                    }
                                }else{
                                    if($num3 > $slect2->num_rows){
                                        $stock .= "0,";
                                    }
                                }
                            }
                            if($num < $slect->num_rows){  
                                $stock .= "]},{";
                            }else{                  
                                $stock .= "]";
                            } 
                        }
                        /*while ($row = $slect->fetch_object()) {                                    
                            $slect_p ="SELECT * FROM products where wrecipe = 1 ORDER BY `name` ASC"; 
                            $slect_p = $conn->query($slect_p);
                            if($slect->num_rows > 0){
                                while ($p_row = $slect_p->fetch_object()) {
                                    
                                    $slect2 = "SELECT * FROM b_stocks as a left join products as b on a.product_id = b.product_id where a.product_id = '" . $p_row->product_id . "' and a.branch_id = '" . $row->branch_id . "' GROUP BY b.product_id ORDER BY `name` ASC";
                                    $slect2 = $conn->query($slect2);
                                    while ($row2 = $slect2->fetch_object()) {                                   
                                          
                                    }                          
                                }
                            }                         
                        }*/
                    }
                ?>
                series: [{
                    <?php echo $stock;?>
                }]
            });
        });
</script>
<script type="text/javascript" src="js/highcharts.js"></script>
