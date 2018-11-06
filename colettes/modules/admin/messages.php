<?php
  $st = 1;
  $up = $conn->prepare("UPDATE message set state = 1 where state = 0");
  //$up->bind_param("i", $st);
  $up->execute();
?>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Messages</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li><a href="./">Dashboard</a></li>
                    <li class="active">Messages</li>
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
                  <strong class="card-title">Message List</strong>
              </div>
              <div class="card-body">
                  <table class="table">
                    <thead class="thead-dark">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Branch Name</th>
                        <th scope="col">Message</th>
                        <th scope="col">Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $prod = "SELECT *,message.dttm as dttmx FROM message left join branches on message.branch_id = branches.branch_id order by message.state,message.dttm desc";
                        $prod = $conn->query($prod);
                        if($prod->num_rows > 0){
                          $num = 0;
                          while ($row = $prod->fetch_object()) {
                            $num += 1;
                            echo '<tr>';
                              echo '<th scope="row">' . $num . '</th>';
                              echo '<td>' . $row->b_name . '</td>';
                              echo '<td>' . $row->message . '</td>';
                              echo '<td>' . ddatet($row->dttmx) . '</td>';
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