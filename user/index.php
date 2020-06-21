<?php
  session_start();
  include("../config/db-creds.inc");
  include("../utils/mysqli_utils.php");
  $stmt = $con->prepare("SELECT guid, username, nickname FROM user WHERE username = ? and password = md5(concat(?,guid));");
  $username = $_POST["username"];
  $password = $_POST["password"];
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $result = $stmt->get_result();
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
        if (!empty($result) && mysqli_num_rows($result) != 0) {
          while ($row = $result->fetch_array()) {
            echo '<div class="alert">';
            echo '  <p class="text-success"><strong>'.$row['nickname'].'</strong>，欢迎你！</p>';
            echo '</div>';
            echo '<p>点击<a href="../project/index.php">这里</a>查看我的项目。</p>';
            $_SESSION['nickname'] = $row['nickname'];
            $_SESSION['user_guid'] = $row['guid'];
          }
        } else {
          echo '<div class="alert">';
          echo '  <p class="text-success">不好意思<strong></strong>，你是不是记错用户名密码啦？</p>';
          echo '</div>';
          echo '<p>点击<a href="../user/loginForm.php">这里</a>再试试~</p>';
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
