<?php
  session_start();

  if ($_SESSION['user_guid']==null) {
    header("Location: /user/login.php");
    exit;
  }
  
  include("../config/db-creds.inc");
  include("../utils/mysqli_utils.php");
  
  $stmt = $con->prepare("SELECT s.`project_name` as s_name, r.`timestamp` as `timestamp`, ip_addr, content, headers FROM `project` s, `result` r WHERE s.`guid`=r.`project_guid` and `user_guid`= ? and s.`guid`=?");
  $stmt->bind_param("ss", $_SESSION['user_guid'], $_GET['id']);
  $stmt->execute();
  $result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>项目内容 · XSS Platform</title>
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
        <?php include("../commons/nav.inc");?>
        
        <hr>
        
        <table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th width='4%'>
                #
            </th>
            <th width='13%'>
                时间
            </th>
            <th width='13%'>
                IP地址
            </th>
            <th width='35%'>
                回传内容
            </th>
            <th width='35%'>
                请求头
            </th>
          </tr>
        </thead>
        <tbody>
          <?php
            if (!empty($result) && mysqli_num_rows($result) != 0) {
            $i = 1;
            while ($row = $result->fetch_array()) {
          ?>
          <tr>
            <td>
              <?php echo $i; ?>
            </td>
            <td>
              <?php echo $row['timestamp']; ?>
            </td>
            <td>
              <?php echo $row['ip_addr']; ?>
            </td>
            <td>
              <?php echo str_replace('&lt;br/&gt;','<br/>',$row['content']); ?>
            </td>
            <td>
              <?php echo str_replace('&lt;br/&gt;','<br/>',$row['headers']); ?>
            </td>
          </tr>
          <?php
            $i = $i + 1;
            }
          }
          ?>
        </tbody>
        </table>
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
