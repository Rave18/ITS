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
                    <?php if(!isset($_GET['view'])){ ?>                    
                      <li><a href="admin/prodlist">Products</a></li>
                      <li class="active">Recipes</li>
                    <?php }else{ ?>
                      <li><a href="admin/recipe">Recipes</a></li>        
                      <li class="active">View Recipes</li>
                    <?php } ?>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content mt-3">
  <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-12">
<?php if(!isset($_GET['view'])){ ?>
          <div class="card">
              <div class="card-header">
                  <strong class="card-title">Product List (Inhouse)</strong>
                  <div class="form-inline">
                    <input <?php if(isset($_SESSION['prodlist'])){ echo " value = '" . $_SESSION['prodlist'] . "' ";} ?> style="min-width: 350px;" type = "text" class="form-control form-control-sm" onkeyup = "prodlist(this.value,'1')" placeholder = "Search Product">
                  </div>
              </div>
              <div class="card-body">
                  <table class="table">
                    <thead class="thead-dark">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Product Cost</th>
                        <th scope="col">Product Category</th>
                        <th scope="col">With Recipe</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody id = "prodlist">
                      <?php
                        $prod = "SELECT * FROM products LEFT JOIN category on products.category = category.category_id where wrecipe = '1' ORDER BY name asc";
                        $prod = $conn->query($prod);
                        if($prod->num_rows > 0){
                          $num = 0;
                          while ($row = $prod->fetch_object()) {
                            $num += 1;
                            $wrecipe = $row->wrecipe ? 'Yes' : 'No';
                            echo '<tr>';
                              echo '<th scope="row">' . $num . '</th>';
                              echo '<td>' . $row->name . '</td>';
                              echo '<td>' . $row->price . '</td>';
                              echo '<td>' . $row->c_name . '</td>';
                              echo '<td>' . $wrecipe . '</td>';
                              echo '<td>';
                                echo '<a href = "admin/recipe/'.$row->product_id.'" class = "btn btn-sm btn-primary"> View </a>';
                                echo ' <a onclick = "edit('.$row->product_id.',\'product\')" name = "edit" class = "btn btn-sm btn-success"> Edit </a>';
                                //echo ' <a onclick = "return confirm(\'Are you sure?\')" href = "admin/delete?id=' . $row->product_id . '" class = "btn btn-sm btn-danger"> Delete </a>';
                              echo '</td>';
                            echo '</tr>';
                          }
                        }
                      ?>                   
                    </tbody>
                  </table>
              </div>
          </div>
<?php }else{ ?>
          <?php
            $prod = "SELECT * FROM products LEFT JOIN category on products.category = category.category_id where wrecipe = '1' and product_id = '" . mysqli_real_escape_string($conn, $_GET['view']) . "' ORDER BY name asc";
            $prod = $conn->query($prod)->fetch_object();
          ?>
          <div class="card">
              <div class="card-header">
                  <strong class="card-title"> Recipe </strong>
                  <button class="btn btn-primary btn-sm pull-right" onclick = "addx('recipe')"><span class="fa fa-plus"></span></button>
              </div>
              <div class="card-body">
                  <div class="card-title">
                      <h3 class="text-center"><?php echo strtoupper($prod->name);?></h3>
                  </div>
                  <hr>
                  <table class="table">
                    <thead class="thead-dark">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Raw Material</th>
                        <th scope="col">Quantity (kg)</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $prod = "SELECT *,a.qty as qtyx FROM recipe as a LEFT JOIN raw as b ON a.raw_id = b.raw_id where a.product_id = '" . $prod->product_id . "' ORDER BY b.raw_desc asc";
                        $prod = $conn->query($prod);
                        if($prod->num_rows > 0){
                          $num = 0;
                          while ($row = $prod->fetch_object()) {
                            $num += 1;
                            echo '<tr>';
                              echo '<th scope="row">' . $num . '</th>';
                              echo '<td>' . $row->raw_desc . '</td>';
                              echo '<td>' . $row->qtyx . '</td>';
                              echo '<td>';
                                echo '<a onclick = "edit('.$row->recipe_id.',\'recipe\')" name = "edit" class = "btn btn-sm btn-success"> Edit </a>';
                                echo ' <a onclick = "return confirm(\'Are you sure?\')" href = "admin/delete?recipe=1&id=' . $row->recipe_id . '&prod='. $row->product_id.'" class = "btn btn-sm btn-danger"> Delete </a>';
                              echo '</td>';
                            echo '</tr>';
                          }
                        }
                      ?>                   
                    </tbody>
                  </table>
              </div>
          </div>
<?php } ?>
        </div>
      </div>
  </div>
</div>
<?php
  if(isset($_POST['update'])){
    if(!empty($_POST['p_name']) && !empty($_POST['p_category']) && !empty($_POST['p_price'])){
      $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, category = ?, wrecipe = ?, persets = ? WHERE product_id = ?");
      $stmt->bind_param("sdsiii", $_POST['p_name'], $_POST['p_price'], $_POST['p_category'], $_POST['recipe'], $_POST['per_sets'], $_SESSION['edit_id']);
      if($stmt->execute() === TRUE){
        alert("admin/prodlist", "Update successfull.");
      }
    }else{
      alert("admin/prodlist", "Check your details");
    }
  }
  if(isset($_POST['update_rep'])){
    if(!empty($_POST['qty'])){
      $stmt = $conn->prepare("UPDATE recipe SET qty = ? where recipe_id = ?");
      $stmt->bind_param("di", $_POST['qty'], $_SESSION['edit_id']);
      if($stmt->execute() === TRUE){
        alert("admin/recipe/".$_GET['view'], "Update successfull.");
      }
    }else{
      alert("admin/recipe/".$_GET['view'], "Check your details");
    }
  }
  if(isset($_POST['add'])){
    if(!empty($_POST['raw']) && !empty($_POST['qty'])){
      $slect = "SELECT * FROM recipe WHERE product_id = '" . mysqli_real_escape_string($conn, $_GET['view']) . "' and raw_id = '" . mysqli_real_escape_string($conn, $_POST['raw']) . "'";
      $slect = $conn->query($slect);
      if($slect->num_rows > 0){
        alert("admin/recipe/".$_GET['view'], "Ingredient already exist.".$slect->num_rows);
        exit;
      }
      $stmt = $conn->prepare("INSERT INTO recipe (product_id, raw_id, qty) VALUES (?, ?, ?)");
      $stmt->bind_param("iid", $_GET['view'], $_POST['raw'], $_POST['qty']);
      if($stmt->execute() === TRUE){
        alert("admin/recipe/".$_GET['view'], "Adding successfull.");
      }
    }else{
      alert("admin/recipe/".$_GET['view'], "Check your details");
    }
  }
  unset($_SESSION['edit_id']);
  if(isset($_SESSION['prodlist'])){
    echo '<script> prodlist("'.$_SESSION['prodlist'].'","1");</script>';
  }
?>