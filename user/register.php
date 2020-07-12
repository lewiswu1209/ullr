<?php
  session_start();
  include("../config/db-creds.inc");
  include("../utils/mysqli_utils.php");
  include("../utils/guid_utils.inc");
  
  if ($_SESSION['user_guid']!=null) {
    header("Location: /project/index.php");
    exit;
  }
  
  $username = htmlspecialchars(trim($_POST["username"]),ENT_QUOTES);
  $password = htmlspecialchars(trim($_POST["password"]),ENT_QUOTES);
  $invite_code = htmlspecialchars(trim($_POST["invite_code"]),ENT_QUOTES);
  
  $error_msg = "";
  
  if ( !empty($_POST["username"]) && !empty($_POST["password"])  && !empty($_POST["invite_code"]) ) {
    if( preg_match('/^[A-Za-z0-9_]{6,20}$/u', $username) ) {
      if( preg_match('/^[a-z0-9-]{36}$/u', $invite_code) ) {
        $stmt = $con->prepare("SELECT * FROM `invite` WHERE `code`=?;");
        $stmt->bind_param("s", $invite_code);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!empty($result) && mysqli_num_rows($result) != 0) {
          $guid = create_guid();
          $stmt = $con->prepare("INSERT INTO `user` (`guid`, `username`, `password`, `nickname`) VALUES (?, ?, md5(concat(?,guid)), ?);");
          $stmt->bind_param("ssss", $guid, $username, $password, $username);
          $stmt->execute();
          
          $stmt = $con->prepare("UPDATE `invite` SET `invitee` = ? WHERE `invite`.`code` = ?;");
          $stmt->bind_param("ss", $guid, $invite_code);
          $stmt->execute();
          header("Location: /user/login.php");
          exit;
        } else {
          $error_msg = "验证码不正确，你是不抄错啦？脑子不好就复制粘贴吧~";
        }
      } else {
        $error_msg = "验证码格式不正确";
      }
    } else {
      $error_msg = "用户名由大小写字母、数字、下划线组成";
    }
  } else {
    $error_msg = "请输入用户名、密码和邀请码";
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>注册 · XSS Platform</title>
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
      <form class="form-signin" action="register.php" method="POST" onsubmit="return checkForm();">
        <h2 class="form-signin-heading">请注册</h2>
        <div class="alert">
          <p class="text-success"><?php echo $error_msg; ?></p>
        </div>
        <input type="text" class="input-block-level" placeholder="请输入用户名" name="username"/>
        <input type="password" class="input-block-level" placeholder="请输入密码" id="password"/>
        <input type="hidden" class="input-block-level" placeholder="请输入密码" id="md5_password" name="password"/>
        <input type="text" class="input-block-level" placeholder="请输入邀请码" name="invite_code"/>
        <button class="btn btn-large btn-primary" type="submit">注册</button>
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
        var password= document.getElementById('password');
        var md5_pwd= document.getElementById('md5_password');
        md5_pwd.value= md5(password.value);
        return ture;
      }
    </script>
  </body>
</html>
