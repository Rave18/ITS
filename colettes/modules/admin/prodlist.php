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
                    <li class="active">Product Maintenance</li>
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
                  <strong class="card-title">Product List</strong>
                  <div class="form-inline">
                    <input <?php if(isset($_SESSION['prodlist'])){ echo " value = '" . $_SESSION['prodlist'] . "' ";} ?> style="min-width: 350px;" type = "text" class="form-control form-control-sm" onkeyup = "prodlist(this.value,'0')" placeholder = "Search Product">
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
                        $prod = "SELECT * FROM products LEFT JOIN category on products.category = category.category_id ORDER BY name asc";
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
                                if($wrecipe == 'Yes'){
                                  echo '<a href = "admin/recipe/'.$row->product_id.'" class = "btn btn-sm btn-primary"> View </a> ';
                                }
                                echo '<a onclick = "edit('.$row->product_id.',\'product\')" name = "edit" class = "btn btn-sm btn-success"> Edit </a>';
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
  if(isset($_SESSION['prodlist'])){
    echo '<script> prodlist("'.$_SESSION['prodlist'].'","0");</script>';
  }
?>