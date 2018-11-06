<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Products</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li><a href="./">Dashboard</a></li>
                    <li><a href="admin/prodlist">Products</a></li>
                    <li class="active">Productions</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content mt-3">
  <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-7">
          <div class="card">
              <div class="card-header">
                  <strong class="card-title">Production</strong>
              </div>
              <div class="card-body">
                <form action="" method="post">
                  <div class="form-group">
                    <label>Select Recipe <font color="red"> * </font></label>
                    <select required  name="recipe" id="recipe" class="form-control form-control-sm" onchange = "recipex()">
                      <option value=""> - - - - - - - </option>
                      <?php
                        $prod = "SELECT * FROM products LEFT JOIN category on products.category = category.category_id where wrecipe = '1' ORDER BY name asc";
                        $prod = $conn->query($prod);
                        if($prod->num_rows > 0){
                          while ($row = $prod->fetch_object()) {
                            $selected = "";
                            if(isset($_SESSION['recipe']) && $_SESSION['recipe'] == $row->product_id){
                              $selected = ' selected ';                              
                            }
                            echo '<option ' . $selected . ' value = "' . $row->product_id . '"> ' . $row->name . ' [ '.$row->persets.'/set ] </option>';
                          }
                        }
                      ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label># of Sets <font color="red"> * </font></label>
                    <input <?php if(isset($_SESSION['qty'])){ echo " value = '" . $_SESSION['qty'] . "'"; } ?> type = "text" class="form-control form-control-sm" autocomplete = "off" onkeyup = "recipex('',this.value)" name = "qty" placeholder = "Enter quantity">
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary" id = "create" name = "create">Create</button>
                  </div>
                </form>
              </div>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="card">
              <div class="card-header">
                  <strong class="card-title">Recipe [ Current Stocks ]</strong>
              </div>
              <div class="card-body" id = "a_recipe">
                
              </div>
          </div>
        </div>
      </div>
  </div>
</div>
<?php
  if( (isset($_SESSION['recipe']) && $_SESSION['recipe'] > 0) && (isset($_SESSION['qty']) && $_SESSION['qty'] > 0)) {
    echo '<script> recipex('.$_SESSION['recipe'].','. $_SESSION['qty'] .')</script>';
  }
  if(isset($_SESSION['disable']) && $_SESSION['disable'] > 0){
    echo '<script> $("button[name=\'create\'").attr(\'disabled\',true); </script>';
  }
  if(isset($_POST['create'])){
    if(!empty($_POST['recipe']) && !empty($_POST['qty'])){
      if(isset($_SESSION['disable']) && $_SESSION['disable'] > 0){
        alert("admin/production", "Not enought raw materials.");  
        exit;
      }
      $slect = "SELECT * FROM recipe WHERE product_id = '" . mysqli_real_escape_string($conn, $_POST['recipe']) . "'";
      $slect = $conn->query($slect);
      if($slect->num_rows > 0){
        while ($row = $slect->fetch_object()) {
          $qty = $_POST['qty'] * $row->qty;
          $stmt2 = $conn->prepare("UPDATE raw SET qty = (qty - ?) WHERE raw_id = ?");
          $stmt2->bind_param("di", $qty, $row->raw_id);
          $stmt2->execute();
        }
        $stmt = $conn->prepare("INSERT INTO production (product_id, qty) VALUES (?, ?)");
        $stmt->bind_param("id", $_POST['recipe'], $_POST['qty']);
        if($stmt->execute() === TRUE){
          $_SESSION['recipe'] = 0;
          $_SESSION['qty'] = 0;
          $stmt2 = $conn->prepare("UPDATE products SET curstocks = (curstocks + (? * persets)) WHERE product_id = ?");
          $stmt2->bind_param("di", $_POST['qty'], $_POST['recipe']);
          if($stmt2->execute()===TRUE){
            alert("admin/production", "Production Successfull");
          }else{
            echo mysqli_error($conn);
          }
          //
        } 
      }else{
        alert("admin/production", "Check your details");
      }
    }else{
      alert("admin/production", "Check your details");
    }
  }
?>