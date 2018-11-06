<?php
    ini_set('session.gc_maxlifetime', 86400);
    session_start();
    include 'config/conf.php';
    include 'config/title.php';
    include 'config/header.php';
    if(isset($_SESSION['insta_acc'])){
      $access = "SELECT * FROM user where account_id = '$_SESSION[insta_acc]'";
      if($conn->query($access)->num_rows <= 0){
        $_GET['module'] = 'logout';
      }
      $access = $conn->query($access)->fetch_object();
      $branch = "SELECT * FROM branches where branch_id = '$access->group_id'";
      $branch = $conn->query($branch)->fetch_object();
      if($access->level >= 50){
        $b_name = "";
      }else{
        $b_name = $branch->b_name;
      }
      $_SESSION['branch_id'] = $access->group_id;
      include('config/menu.php');
      if(!isset($_GET['module'])){
        $_GET['module'] = 'main';
        if(isset($_SESSION['insta_acc']) && $access->level > 50){
          $_GET['module'] = 'admin';
        }
      }
?>

<div class="modal fade" id="changepass" role="dialog">
      <div class="modal-dialog">    
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header" style="padding:35px 50px;">
            <h5 class="modal-title" id="modalLabel" align="center">Update Password</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="padding:40px 50px;">
            <form role="form" action = "" method = "post">
              <div class="form-group">
                <label for="usrname"><span class="icon-eye-blocked"></span> Old Password</label>
                <input type="password" class="form-control input-sm" required id="oldpsw" name = "oldpword" autocomplete="off"placeholder="Enter password">
              </div>
              <div class="form-group">
                <label for="usrname"><span class="icon-eye"></span> New Password</label>
                <input type="password" class="form-control input-sm" required id="psw" name = "pword" autocomplete="off"placeholder="Enter password">
              </div>
              <div class="form-group">
                <label for="psw"><span class="icon-eye"></span> Confirm New Password</label>
                <input type="password" class="form-control input-sm" required id="psw1" name = "pword2" autocomplete="off"placeholder="Enter password">
              </div>
              <button type="submit" id = "submitss" name = "submitpw" class="btn btn-success btn-block"><span class="icon-switch"></span> Update</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php 
    if(isset($_POST['submitpw'])){
      if(!empty($_POST['pword']) && !empty($_POST['pword2'])){
        $oldpass = mysqli_real_escape_string($conn, $_POST['oldpword']);
        $pword = mysqli_real_escape_string($conn, $_POST['pword']);
        $pword2 = mysqli_real_escape_string($conn, $_POST['pword2']);
        if($pword != $pword2){
          echo '<script type="text/javascript">alert("New password doesn not match."); window.location.href = "'. $_SESSION["REQUEST_URI"]  . '";</script>';
          exit;
        }
        $pass = "SELECT * FROM user where account_id = '" . $_SESSION['insta_acc'] . "' and pword = '" . $oldpass . "' limit 1";
        $pass = $conn->query($pass);
        if($pass->num_rows > 0){
          $uppass ="UPDATE user set pword = '$pword' where account_id = '" . $_SESSION['insta_acc'] . "' and pword = '" . $oldpass . "'";
          if($conn->query($uppass) === TRUE){
            echo '<script type="text/javascript">alert("Change password successful."); window.location.href = "'. $_SESSION["REQUEST_URI"]  . '";</script>';
          }else{
            $conn->error();
          }
        }else{
          echo '<script type="text/javascript">alert("Old password does not match."); window.location.href = "'. $_SESSION["REQUEST_URI"]  . '";</script>';
        }
      }else{
        echo '<script type="text/javascript">alert("Empty password"); window.location.href = "'. $_SESSION["REQUEST_URI"]  . '";</script>';
      }
    }
  ?>
  <!-- Page Content -->
      <div id="right-panel" class="right-panel">
      <?php 
        include 'config/head.php';
        include 'ajax/func.php';
        if(!isset($_GET['action'])){
            $acc = 'index.php';
        }else{
            $acc = $_GET['action'].'.php';
        }

        if(!isset($_GET['module'])){
          include 'modules/main/index.php';
        }elseif($_GET['module'] == 'logout'){
            include 'modules/logout.php';
        }elseif(!file_exists('modules/'.$_GET['module'].'/'.$acc)){
            include 'config/404.php';
        }else{
            include 'modules/'.$_GET['module'].'/'.$acc;
        }
    ?>
    </div>
    <?php
     }elseif((isset($_GET['module']) && $_GET['module'] == 'login' && !isset($_SESSION['insta_acc'])) || (!isset($_SESSION['insta_acc']))){
      $_SESSION["REQUEST_URI"] = "$_SERVER[REQUEST_URI]";
    ?>
    <style type="text/css">
  body, html {
    
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        display:table;
    }
    body {
        display:table-cell;
        vertical-align:middle;
        background-color: white;
    }
    form {
        display:table;/* shrinks to fit conntent */
        margin:auto;
    }

    #form_login{
      width: 30%;
      border: 1px solid gray;
      padding: 20px;
      border-radius: 10px;
    }
</style>
<div class="container">
  <form id="form_login" method="post" action="">
    <h4 align="center">LOGIN FORM</h4>
    <hr>
    <div class="form-group">
      <label>Username <font color = "red"> * </font></label>
      <input type = "text" autocomplete = "off" class="form-control form-control-md" placeholder = "Enter username." required autofocus name = "uname">
    </div>    
    <div class="form-group">
      <label>Password <font color = "red"> * </font></label>
      <input type = "password" class="form-control form-control-md" placeholder = "Enter password." required name = "password">
    </div>
    <div class="form-group" align="center">
      <button class="btn btn-primary btn-sm" name = "submit"> Login </button>
    </div>
    </form>
</div>
<br>

<?php
	if(isset($_SESSION['logout']) && $_SESSION['logout'] != null){
		echo  '<div class="alert alert-warning" align = "center"><strong>You\'ve been logged out.</strong></div>';
		$_SESSION['logout'] = null;
	}
  if(isset($_GET['module']) && $_GET['module'] == 'logout'){
    $_SESSION["REQUEST_URI"] = "/" . $pagename;
  }
?>
<?php
	if(isset($_POST['submit'])){
		$uname = mysqli_real_escape_string($conn, $_POST['uname']);
		$password =  mysqli_real_escape_string($conn, $_POST['password']);
		
		$sql = "SELECT * FROM `user` where uname = '$uname' and pword = '$password'";
		$result = $conn->query($sql);		
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){								
				$_SESSION['insta_acc'] = $row['account_id'];
				$_SESSION['nameinsta'] = $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'];
				$_SESSION['usernameinsta'] = $row['uname'];
				$stmt = $conn->prepare("UPDATE sales set temp = 2 WHERE branch_id = ? and temp = 1");
				$stmt->bind_param("i", $row['group_id']);
				if($stmt->execute()===TRUE){}
				  echo '<div class="alert alert-success" align = "center"><strong>Logging in ~!</strong></div>';
					  echo '<script type="text/javascript">setTimeout(function() { window.location.href = "'. $_SESSION["REQUEST_URI"]  . '"; }, 1000); </script>';	
				}				
    }else{
      echo  '<div class="alert alert-warning" align = "center"><strong>Warning!</strong> Incorrect Login. </div>';
	  }
		$conn->close();
	}
}
include('config/footer.php');
?>