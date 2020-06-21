<?php
  session_start();
  if ($_SESSION['user_guid']==null) {
    header("Location: /user/loginForm.php");
    exit;
  }
  
  include("../config/db-creds.inc");
  include("../utils/mysqli_utils.php");
  
  $stmt = $con->prepare("SELECT * FROM `modules`");
  $stmt->execute();
  $result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>新建项目 · XSS Platform</title>
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
      <form class="form-signin form-horizontal" action="create.php" method="POST">
        <h2 class="form-signin-heading"><center>添加项目</center></h2>
        <div class="control-group">
          <label class="control-label" for="title">项目标题</label>
          <div class="controls">
            <input type="text" id="title" name="title" placeholder="请输入项目标题">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="module">选择模块</label>
          <div class="controls">
            <select id="module" name="module">
              <option value="">不添加内置模块</option>
            <?php
              if (!empty($result) && mysqli_num_rows($result) != 0) {
                $i = 1;
                while ($row = $result->fetch_array()) {
            ?>
              <option value="<?php echo $row['payload']; ?>"><?php echo $row['module_name']; ?></option>
            <?php
                  $i = $i + 1;
                }
              }
            ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="code">自定义代码</label>
          <div class="controls">
            <textarea id="code" name="code" class="span6" rows="10"></textarea>
          </div>
        </div>
        <div class="control-group">
          <div class="controls">
            <button type="submit" class="btn">保存项目</button>
          </div>
        </div>
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
  </body>
</html>
