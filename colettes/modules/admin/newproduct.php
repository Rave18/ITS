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
                    <li><a href="admin/products">Products</a></li>
                    <li class="active">New Product</li>
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
                    <strong class="card-title">Product Form</strong>
                </div>
                <div class="card-body">
                  <div>
                      <div class="card-body">
                          <div class="card-title">
                              <h3 class="text-center">Product Information</h3>
                          </div>
                          <hr>
                          <form action="" method="post">
                              <div class="form-group">
                                  <label for="pname" class="control-label mb-1">Product Name <font color = "red"> * </font></label>
                                  <input autocomplete = "off" required placeholder = "Product name" id="pname" name="pname" type="text" class="form-control" aria-required="true" aria-invalid="false">
                              </div>
                              <div class="form-group has-success">
                                  <label for="price" class="control-label mb-1">Price  <font color = "red"> * </font></label>
                                  <input pattern = "[0-9.]*" required placeholder = "Enter amount" id="price" name="price" type="text" class="form-control price valid" data-val="true" data-val-required="Please enter price amount" aria-required="true" aria-invalid="false" aria-describedby="price-error">
                                  <span class="help-block field-validation-valid" data-valmsg-for="price" data-valmsg-replace="true"></span>
                              </div>
                              <div class="form-group">
                                <label for="recipe" class="control-label mb-1">With Recipe  <font color = "red"> * </font></label>
                                <select required  name="recipe" id="recipe" class="form-control form-control-sm">
                                  <option value="0"> No </option>
                                  <option value="1"> Yes </option>
                                </select>
                              </div>
                              <div class="form-group" style="display: none" id = "psets">
                                <label for="recipe" class="control-label mb-1">Per Sets  <font color = "red"> * </font></label>
                                <input type = "number" class="form-control form-control-sm" name = "per_sets" min = '1' placeholder = "Enter per sets" pattern = "[0-9.]*">
                              </div>
                              <div class="form-group">
                                  <label for="category" class="control-label mb-1">Category  <font color = "red"> * </font></label>
                                  <select required  name="category" id="category" class="form-control">
                                    <option value=""> - - - - - - - </option>
                                    <?php
                                      $cat = "SELECT * FROM category ORDER BY c_name";
                                      $cat = $conn->query($cat);
                                      if($cat->num_rows > 0){
                                        while ($row = $cat->fetch_object()) {
                                          echo '<option value = "' . $row->category_id . '"> ' . $row->c_name . '</option>';
                                        }
                                      }
                                    ?>
                                  </select>
                                  <input placeholder = "Enter Category" id="new_catx" name="new_catx" type="text" class="form-control" aria-required="true" aria-invalid="false" style="display: none;">
                                  <label><input type = "checkbox" id = "new_cat" name = "new_cat"/> Add new category </label>
                              </div>
                              <div>
                                  <button id="payment-button" type="submit" name = "submit" class="btn btn-info btn-block">
                                      <i class="fa fa-dot-circle-o"></i>&nbsp;<span>Submit</span>
                                  </button>
                              </div>
                          </form>
                      </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
  if(isset($_POST['submit'])){
    if(!empty($_POST['pname']) && !empty($_POST['price'])){
      $rand = random_string(5);
      if(isset($_POST['new_cat']) && !empty($_POST['new_catx'])){
        $stmt = $conn->prepare("INSERT INTO category (c_name) VALUES (?)");
		$_POST['new_catx'] = strtoupper($_POST['new_catx']);
        $stmt->bind_param("s", $_POST['new_catx']);
        if($stmt->execute() === TRUE){
          $_POST['category'] = $conn->insert_id;;
        }
      }
	  $_POST['pname'] = strtoupper($_POST['pname']);
      $stmt = $conn->prepare("INSERT INTO products (code, name, price, dttm, category, wrecipe, persets) VALUES (?, ?, ?, now(), ?, ?, ?)");
      $stmt->bind_param("ssdsis", $rand, $_POST['pname'], $_POST['price'], $_POST['category'], $_POST['recipe'], $_POST['per_sets']);
      if($stmt->execute() === TRUE){
        alert("admin/prodlist", "Adding successfull.");
      }else{
        echo $conn->error();
      }
    }else{
      alert("admin/newproduct", "Check your details");
    }
  }

?>