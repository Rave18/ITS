<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Branches</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li><a href="./">Dashboard</a></li>
                    <li class="active">Branches</li>
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
                  <strong class="card-title">Branches List</strong>
                  <button class="btn btn-primary btn-sm pull-right" onclick = "addx('branches')"><span class="fa fa-plus"></span></button>
              </div>
              <div class="card-body">
                  <table class="table">
                    <thead class="thead-dark" align="center">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Branch</th>
                        <th scope="col">Location</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody align = "center">
                      <?php
                        $prod = "SELECT * FROM branches ORDER BY b_name asc";
                        $prod = $conn->query($prod);
                        if($prod->num_rows > 0){
                          $num = 0;
                          while ($row = $prod->fetch_object()) {
                            $num += 1;
                            echo '<tr>';
                              echo '<th scope="row">' . $num . '</th>';
                              echo '<td>' . $row->b_name . '</td>';
                              echo '<td>' . $row->b_addr . '</td>';
                              echo '<td>';
                                echo '<a onclick = "edit('.$row->branch_id.',\'branch\')" name = "edit" class = "btn btn-sm btn-success"> Edit </a>';
                                //echo ' <a onclick = "return confirm(\'Are you sure?\')" href = "admin/delete?idx=' . $row->branch_id . '" class = "btn btn-sm btn-danger"> Delete </a>';
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
  if(isset($_POST['add'])){
    if(!empty($_POST['b_name']) && !empty($_POST['b_addr'])){
      $stmt = $conn->prepare("INSERT INTO branches (b_name, b_addr) VALUES (?, ?)");
      $stmt->bind_param("ss", $_POST['b_name'], $_POST['b_addr']);
      if($stmt->execute() === TRUE){
        alert("admin/branches", "Adding successfull.");
      }
    }else{
      alert("admin/branches", "Check your details");
    }
  }
  if(isset($_POST['update'])){
    if(!empty($_POST['b_name']) && !empty($_POST['b_addr'])){
      $stmt = $conn->prepare("UPDATE branches SET b_name = ?, b_addr = ? where branch_id = ?");
      $stmt->bind_param("ssi", $_POST['b_name'], $_POST['b_addr'], $_SESSION['edit_id']);
      if($stmt->execute() === TRUE){
        alert("admin/branches", "Update successfull.");
      }
    }else{
      alert("admin/branches", "Check your details");
    }
  }
  unset($_SESSION['edit_id']);
?>