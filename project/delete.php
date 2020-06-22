<?php
  session_start();
  if ($_SESSION['user_guid']==null) {
    header("Location: /user/loginForm.php");
    exit;
  }
  include("../config/db-creds.inc");
  include("../utils/mysqli_utils.php");
  $stmt = $con->prepare("DELETE FROM `project` WHERE `guid`=?;");
  $stmt->bind_param("s", $_GET['id']);
  $stmt->execute();
  $stmt->close();
  $stmt = $con->prepare("DELETE FROM `result` WHERE `project_guid`=?;");
  $stmt->bind_param("s", $_GET['id']);
  $stmt->execute();
  $stmt->close();
  header("Location: /project/index.php");
?>
