      <!-- Header-->
        <header id="header" class="header">
            <div class="header-menu">
                <div class="col-sm-7">
                    <?php   if($access->level >= 50 ){    ?>
                    <!--<a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>-->
                    <div class="header-left">
                        <button class="search-trigger"  style="display: none;"><i class="fa fa-search"></i></button>
                        <div class="form-inline">
                            <form class="search-form">
                                <input class="form-control mr-sm-2" type="text" placeholder="Search ..." aria-label="Search">
                                <button class="search-close" type="submit"><i class="fa fa-close"></i></button>
                            </form>
                        </div>
                        <div class="dropdown for-notification">
                          <button class="btn btn-secondary dropdown-toggle" type="button" id="notification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bell"></i>
                            <?php
                                $count = "SELECT count(*) as counter FROM message where state = 0";
                                $counter = $conn->query($count)->fetch_object();

                                $countx = "SELECT * FROM message left join branches on message.branch_id = branches.branch_id where state = 0 order by message.dttm desc";
                                $counterx = $conn->query($countx);
                            ?>
                            <span class="count bg-danger"><?php echo $counter->counter;?></span>
                          </button>
                          <div class="dropdown-menu" aria-labelledby="notification" >
                            <p class="red"><?php echo $counter->counter;?> unread messages</p>
                            <?php
                                if($counterx->num_rows > 0){
                                    while ($row = $counterx->fetch_object()) {
                            ?>
                                <a class="dropdown-item media" href="#">
                                    <i class="fa fa-mail-reply"></i>
                                    <p><b><?php echo $row->b_name;?></b><br><?php echo $row->message;?></p>
                                </a>
                            <?php
                                    }
                                }
                            ?>
                            <a href="admin/messages" style="font-size: 12px;"> <i>See all messages</i> </a>
                          </div>                            
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-sm-5">
                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="user-avatar rounded-circle" src="images/admin.png" alt="User Avatar">
                        </a>
                        <div class="user-menu dropdown-menu">
                            <a class="nav-link" href = "#" data-toggle="modal" data-target="#changepass"><span class="icon-eye"></span> Change Password </a>
                            <?php
                                if($access->level <= 1){
                                    //echo '<a class="nav-link" href = "#" data-toggle="modal" data-target="#msg">Message Admin</button>';
                                    echo '<a class="nav-link" href = "#" data-toggle="modal" data-target="#transfer">Transfer Stocks to Other Branch</button>';
                                    echo '<a class="nav-link" href = "#" data-toggle="modal" data-target="#void">Cancel Transaction</button>';
                                    echo '<a class="nav-link" href = "admin/b_stocks">Branch Inventory</button>';
                                }else{
                                    echo '<a class="nav-link" href = "#" data-toggle="modal" data-target="#dispose">Dispose</button>';
                                    echo '<a class="nav-link" href = "#" data-toggle="modal" data-target="#bo">Return to Supplier</button>';
                                }
                            ?>
                            <a class="nav-link" href="logout"><i class="fa fa-power -off"></i>Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </header><!-- /header -->
        <!-- Header-->