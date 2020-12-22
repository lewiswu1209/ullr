<?php
  session_start();
  $error_flag = false;
  include("../config/db-creds.inc");
  include("../utils/mysqli_utils.php");
  
  if (isset($_SESSION['user_guid'])) {
    header("Location: /project/index.php");
    exit;
  }
  
  if ( !empty($_POST["username"]) && !empty($_POST["password"]) ) {
    $stmt = $con->prepare("SELECT guid, username, nickname FROM user WHERE username = ? and password = md5(concat(?,guid));");
    $username = $_POST["username"];
    $password = $_POST["password"];
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!empty($result) && mysqli_num_rows($result) != 0) {
      while ($row = $result->fetch_array()) {
        $_SESSION['nickname'] = $row['nickname'];
        $_SESSION['user_guid'] = $row['guid'];
        header("Location: /project/index.php");
      }
    } else {
      $error_flag = true;
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>登录 · XSS Platform</title>
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
      <form class="form-signin" action="login.php" method="POST" onsubmit="return checkForm();">
        <h2 class="form-signin-heading">请登录</h2>
        <div style="<?php echo $error_flag?"display:display":"display:none" ?>" class="alert">
          <p class="text-success">不好意思<strong></strong>，你是不是记错用户名密码啦？</p>
        </div>
        <input type="text" class="input-block-level" placeholder="请输入用户名" name="username"/>
        <input type="password" class="input-block-level" placeholder="请输入密码" id="password"/>
        <input type="hidden" class="input-block-level" placeholder="请输入密码" id="md5_password" name="password"/>
        <button class="btn btn-large btn-primary" type="submit">登录</button>
        <a href="register.php" class="btn btn-large btn-primary pull-right">注册</a>
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
        return true;
      }
    </script>
  </body>
</html>
