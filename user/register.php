<?php
  session_start();
  include("../config/db-creds.inc");
  include("../utils/mysqli_utils.php");
  include("../utils/guid_utils.inc");
  $username = htmlspecialchars(trim($_POST["username"]),ENT_QUOTES);
  $password = htmlspecialchars(trim($_POST["password"]),ENT_QUOTES);
  $invite_code = htmlspecialchars(trim($_POST["invite_code"]),ENT_QUOTES);
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

    <!-- CSS -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      /* Sticky footer styles
      -------------------------------------------------- */

      html,
      body {
        height: 100%;
        /* The html and body elements cannot have any padding or margin. */
      }

      /* Wrapper for page content to push down footer */
      #wrap {
        min-height: 100%;
        height: auto !important;
        height: 100%;
        /* Negative indent footer by it's height */
        margin: 0 auto -60px;
      }

      /* Set the fixed height of the footer here */
      #push,
      #footer {
        height: 60px;
      }
      #footer {
        background-color: #f5f5f5;
      }

      /* Lastly, apply responsive CSS fixes as necessary */
      @media (max-width: 767px) {
        #footer {
          margin-left: -20px;
          margin-right: -20px;
          padding-left: 20px;
          padding-right: 20px;
        }
      }

      /* Custom page CSS
      -------------------------------------------------- */
      /* Not required for template or sticky footer method. */

      .container {
        width: auto;
        max-width: 680px;
      }
      .container .credit {
        margin: 20px 0;
      }
    </style>
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
  </head>
  <body>
    <!-- Part 1: Wrap all page content here -->
    <div id="wrap">
      <!-- Begin page content -->
      <div class="container">
        <div class="page-header">
          <h1>提示</h1>
        </div>
        <?php
        if(preg_match('/^[A-Za-z0-9_]{6,20}$/u',$username)) {
          $stmt = $con->prepare("SELECT * FROM `invite` WHERE `code`=?;");
          $stmt->bind_param("s", $invite_code);
          $stmt->execute();
          $result = $stmt->get_result();
          if (!empty($result) && mysqli_num_rows($result) != 0) {
            $stmt = $con->prepare("INSERT INTO `user` (`guid`, `username`, `password`, `nickname`) VALUES (?, ?, md5(concat(?,guid)), ?);");
            $stmt->bind_param("ssss", create_guid(), $username, $password, $username);
            $stmt->execute();
            $result = $stmt->get_result();
            echo '<div class="alert">';
            echo '  <p class="text-success">注册成功<strong></strong>，登录试试。</p>';
            echo '</div>';
            echo '<p>点击<a href="../user/loginForm.php">这里</a>去登陆~</p>';
          } else {
            echo '<div class="alert">';
            echo '  <p class="text-success">不好意思<strong></strong>，邀请码是不抄错啦？</p>';
            echo '</div>';
            echo '<p>点击<a href="../user/registerForm.php">这里</a>再试试~</p>';
          }
        }else{
          echo '<div class="alert">';
          echo '  <p class="text-success">不好意思<strong></strong>，用户名应当由6~20位字母、数字、下划线组成。</p>';
          echo '</div>';
          echo '<p>点击<a href="../user/registerForm.php">这里</a>再试试~</p>';
        }
        ?>
      </div>
      <div id="push"></div>
    </div>
    <div id="footer">
      <div class="container">
        <p class="muted credit">注意：未获授权的安全测试将承担法律责任，请只在获得授权的情况下使用本工具。</p>
      </div>
    </div>

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
  </body>
</html>
