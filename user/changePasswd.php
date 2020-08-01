<?php
  session_start();
  
  if ($_SESSION['user_guid']==null) {
    header("Location: /user/login.php");
    exit;
  }
  
  include("../config/db-creds.inc");
  include("../utils/mysqli_utils.php");
  
  $guid= $_SESSION['user_guid'];
  $old = htmlspecialchars(trim($_POST["old"]),ENT_QUOTES);
  $password = htmlspecialchars(trim($_POST["password"]),ENT_QUOTES);
  
  if ( !empty($_POST["old"]) && !empty($_POST["password"]) ) {
    $stmt = $con->prepare("update `user` set `password`=md5(concat(?,?)) where `guid`=? and`password`=md5(concat(?,?));");
    $stmt->bind_param("sssss", $password, $guid, $guid, $old, $guid);
    $stmt->execute();
    
    header("Location: /user/logout.php");
    exit;
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>修改密码 · XSS Platform</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }
    </style>
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <form class="form-signin" action="changePasswd.php" method="POST" onsubmit="return checkForm();">
        <h2 class="form-signin-heading">修改密码</h2>
        <input type="password" class="input-block-level" placeholder="请输入原密码" id="old"/>
        <input type="hidden" class="input-block-level" placeholder="请输入原密码" id="md5_old" name="old"/>
        <input type="password" class="input-block-level" placeholder="请输入新密码" id="password"/>
        <input type="hidden" class="input-block-level" placeholder="请输入新密码" id="md5_password" name="password"/>
        <button class="btn btn-large btn-primary" type="submit">确定</button>
        <button class="btn btn-large btn-primary pull-right" type="reset">重置</button>
      </form>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap-transition.js"></script>
    <script src="../assets/js/bootstrap-alert.js"></script>
    <script src="../assets/js/bootstrap-modal.js"></script>
    <script src="../assets/js/bootstrap-dropdown.js"></script>
    <script src="../assets/js/bootstrap-scrollspy.js"></script>
    <script src="../assets/js/bootstrap-tab.js"></script>
    <script src="../assets/js/bootstrap-tooltip.js"></script>
    <script src="../assets/js/bootstrap-popover.js"></script>
    <script src="../assets/js/bootstrap-button.js"></script>
    <script src="../assets/js/bootstrap-collapse.js"></script>
    <script src="../assets/js/bootstrap-carousel.js"></script>
    <script src="../assets/js/bootstrap-typeahead.js"></script>
    <script src="../assets/js/md5.js"></script>
    <script>
      function checkForm(){
        var old= document.getElementById('old');
        var md5_old= document.getElementById('md5_old');

        var password= document.getElementById('password');
        var md5_pwd= document.getElementById('md5_password');
        
        md5_pwd.value= md5(password.value);
        md5_old.value= md5(old.value);
        return true;
      }
    </script>
  </body>
</html>
