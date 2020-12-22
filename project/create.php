<?php
  session_start();
  if (!isset($_SESSION['user_guid'])) {
    header("Location: /user/login.php");
    exit;
  }
  
  include("../config/db-creds.inc");
  include("../utils/mysqli_utils.php");
  include("../utils/url_utils.inc");
  include("../utils/guid_utils.inc");
  
  $project_name = htmlspecialchars($_POST["title"], ENT_QUOTES);
  if ($_POST["module"]=="") {
    $payload = htmlspecialchars(base64_decode($_POST["code"]), ENT_QUOTES);
  } else {
    $payload = htmlspecialchars(base64_decode($_POST["module"]), ENT_QUOTES);
  }

  if ( !empty($project_name) && !empty($payload) ) {
    $user_guid=$_SESSION['user_guid'];
    $guid=short(create_guid());
    $stmt = $con->prepare("INSERT INTO `project` (`guid`, `user_guid`, `project_name`, `payload`) VALUES (?, ?, ?, ?);");
    $stmt->bind_param("ssss", $guid, $user_guid,$project_name, $payload);
    $stmt->execute();
    header("Location: /project/index.php");
    exit();
  } else {
    $stmt = $con->prepare("SELECT * FROM `modules`");
    $stmt->execute();
    $result = $stmt->get_result();
  }
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
      <form class="form-signin form-horizontal" action="create.php" method="POST"  onsubmit="return checkForm();">
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
            <select id="module">
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
            <textarea id="base64_module" name="module" style="display:none;" class="span6" rows="10" placeholder="如果要使用自定义代码，在上边选择不添加内置模块选项，然后在这里录入代码。"></textarea>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="code">自定义代码</label>
          <div class="controls">
            <textarea id="code" class="span6" rows="10" placeholder="如果要使用自定义代码，在上边选择不添加内置模块选项，然后在这里录入代码。"></textarea>
            <textarea id="base64_code" name="code" class="span6" style="display:none;" rows="10" placeholder="如果要使用自定义代码，在上边选择不添加内置模块选项，然后在这里录入代码。"></textarea>
          </div>
        </div>
        <div class="control-group">
          <div class="controls">
            <p class="muted"><span class="badge badge-info">1</span> payload中使用{projectId}代替项目ID.</p>
            <p class="muted"><span class="badge badge-info">2</span> payload中使用{host}代替回传的站点.</p>
            <p class="muted"><span class="badge badge-info">3</span> 数据回传至postback.php.</p>
            <p class="muted"><span class="badge badge-info">4</span> 使用GET回传至postback.php的参数均会被接收.</p>
            <p class="muted"><span class="badge badge-info">5</span> 例如{host}/postback.php?id={projectId}&param=value.</p>
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
    <script src="../assets/js/base64.js"></script>
    <script>
      function checkForm(){
        var module = document.getElementById('module');
        var b64_m= document.getElementById('base64_module');
        b64_m.value= Base64.encode(module.value);
        var code = document.getElementById('code');
        var b64_code= document.getElementById('base64_code');
        b64_code.value= Base64.encode(code.value);
        return true;
      }
    </script>
  </body>
</html>
