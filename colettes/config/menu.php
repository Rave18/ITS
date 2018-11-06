<?php   if($access->level >= 1){    ?>
    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">
            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="./"><h4>Collette's Admin</h4></a>
                <a class="navbar-brand hidden" href="./"><h4>Col</a>
            </div>

            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="./"> <i class="menu-icon fa fa-dashboard"></i>Dashboard </a>
                    </li>
                    <h3 class="menu-title">Products</h3><!-- /.menu-title -->
                    <?php
                        if($access->level > 2){
                    ?>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-pie-chart"></i>Products</a>
                        <ul class="sub-menu children dropdown-menu">
                            <?php
                                if($access->level > 2){
                            ?>
                            <li><i class="fa fa-plus"></i><a href="admin/newproduct">New Product</a></li>
                            <li><i class="fa fa-list"></i><a href="admin/prodlist">Product Maintenance</a></li>
                            <li><i class="fa fa-list"></i><a href="admin/recipe">Inhouse Product</a></li>                            
                            <?php
                                }
                                if($access->level == 1 || $access->level > 2){
                            ?>
                            <li><i class="fa fa-cog"></i><a href="admin/production">Production</a></li>
                            <?php
                                }
                            ?>
                        </ul>
                    </li>
                    <?php
                        }
                        if($access->level >= 2){
                    ?>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-table"></i>Inventory</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-table"></i><a href="admin/b_stocks">Branch Inventory</a></li>
                            <li><i class="fa fa-table"></i><a href="admin/raw">Raw Materials</a></li>
                            <li><i class="fa fa-table"></i><a href="admin/prod_inve">Product Inventory</a></li>
                        </ul>
                    </li>
                    <?php
                        }
                    ?>
                    <?php
                        if($access->level > 2){
                    ?>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-folder"></i>Reports</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-folder"></i><a href="admin/s_rep">Sales Report</a></li>
                            <li><i class="fa fa-folder"></i><a href="admin/i_rep">Inventory Report</a></li>
                            <li><i class="fa fa-folder"></i><a href="admin/v_rep">Cancelled Transaction Report</a></li>
                            <li><i class="fa fa-folder"></i><a href="admin/d_rep">Dispose Report</a></li>
                            <li><i class="fa fa-folder"></i><a href="admin/bo_rep">Return To Supplier Report</a></li>
                        </ul>
                    </li>
                    <?php
                        }
                    ?>
                    <!--
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-folder"></i>Reports</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="menu-icon fa fa-th"></i><a href="forms-basic.html">Sales Report</a></li>
                        </ul>
                    </li>
                    -->
                    <?php
                        if($access->level > 2){
                    ?>
                    <h3 class="menu-title">System Management</h3><!-- /.menu-title -->
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-tasks"></i>Misc.</a>
                        <ul class="sub-menu children dropdown-menu">                            
                            <li><i class="menu-icon fa fa-fort-awesome"></i><a href="admin/branches">Branch</a></li>
                            <li><i class="menu-icon fa fa-user"></i><a href="accounts">Accounts</a></li>
                            <!--<li><i class="menu-icon ti-themify-logo"></i><a href="font-themify.html">User Management</a></li>-->
                        </ul>
                    </li>
                    <?php
                        }
                    ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside><!-- /#left-panel -->
<?php   }   ?>
