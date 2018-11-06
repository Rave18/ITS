<style type="text/css">
	@media print {	
		#reportg p, ul li{
			font-size: 8px;
		}
		label{
			font-size: 9.5px;
		}
		#reportg label{
			margin-bottom: 0px !important;
		}
		#reportg p{
			margin-top: 0px !important;
		}
		#reportg{
			margin: 1mm;
		}
		body * {
			visibility: hidden; 
			margin: 0px;	
		}
		@page{
			margin: 1mm;
		}
		#reportg, #reportg * {
			visibility: visible;
		}
		#reportg {
		position: absolute;
			left: 0;
			top: 0;
			right: 0;
		}
		#backs{
			display: none;
		}
		hr{
			border-top: 1px solid black;
		}
	}
</style>
<div id="reportg" style="width: 230px; margin-left: 0px;">
	<div id = "head" align="center">
		<label>COLLETE'S FOOD INC.</label>
		<p>
			Op. by: Collete's Food Inc. <br>
			52 Brgy. San Rafael <br>
			San Pablo City, Laguna
			<br>VAT REG TIN: 228-877-367-000
		</p>
	</div>
	<div id = "body">
		<?php
			$_GET['id'] = mysqli_real_escape_string($conn, $_GET['id']);
			$select = "SELECT * FROM sales as a LEFT JOIN products as b on a.product_id = b.product_id where a.branch_id = '" . $_SESSION['branch_id'] . "' and a.sales_id = '" . $_GET['id'] . "'";
			$select = $conn->query($select);
            if($select->num_rows > 0){
                echo '<div class="form-group" style = "margin-left: 10px;">';
                echo "<hr><p>Item @ Price x Qty [ Total ]<hr></p>";
                    echo '<ul>';
                    $qty = 0;
                    $total = 0;
                    $id = "";
					$salesid = "";
                    while ($row = $select->fetch_object()) {
						$salesid = $row->sales_id;
                    	$pay = $row->dttm_pay;
                        $id = $row->sales_id;
                        echo '<li> '. $row->name . ' @ ' . $row->price  . ' x ' . $row->qty . ' [ ' . number_format($row->total,2) . ' ] </li>';    
                        $qty = $qty + $row->qty;
                        $total = $total + $row->total;
                    }
                    echo '</ul>';
                    echo '<hr>';
					if(isset($_GET['tendered']) && $_GET['tendered'] > 0){
						$change = number_format($_GET['tendered'] - $total,2);
					}else{
						$change = number_format($total,2); 
					}
                    echo '<p> Total Amnt @ P ' . number_format($total,2) . '<br>';
					echo 'Amount Tendered: P ' . number_format($_GET['tendered'],2) . '<br>';
					echo 'Change: P '.$change.'</p>';
					echo '<hr>';
					echo '<p>';
					echo 'Vatable Sales: P ' . number_format( $total - ($total * .12), 2).'<br>';
					echo 'VAT Amount: P ' . number_format( ($total * .12), 2);
					echo '</p>';
					echo '<hr><p>';
					echo 'Invoice #: ' . $salesid . '<br>';
					echo 'Cashier: ' . $_SESSION['nameinsta'] . '<br>';
                    echo 'Trans. Date: ' . date("m/d/Y h:i:s A", strtotime($pay)) . '</p>';
	                echo '<hr>';
	                echo '<br><p align = "center">Thank you! Come again.</p>';
                echo '</div>';
                
             }              
		?>
	</div>
</div>
<div id = "break" style="page-break-before: always;"/>
<?php
	echo '<script type = "text/javascript">	setTimeout(function() {window.print(); window.location.href = "main";	},600); </script>';			
?>
