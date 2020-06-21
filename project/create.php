<?php
  session_start();
  include("../config/db-creds.inc");
  include("../utils/mysqli_utils.php");
  $stmt = $con->prepare("INSERT INTO `project` (`guid`, `user_guid`, `project_name`, `payload`) VALUES (?, ?, ?, ?);");
  
  include("../utils/guid_utils.inc");
  
  function short($data)
  {
    $base = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $datahash = crc32($data);
    $len = strlen($datahash);
    $datahash_piece = $datahash;
    $hex = hexdec($datahash_piece) & 0x3fffffff;
    $short = "";
    for ($j = 0; $j < 6; $j++) {
      $short .= $base[$hex & 0x0000003d];
      $hex = $hex >> 5;
    }
    return $short;
  }
  $guid=short(create_guid());
  $user_guid=$_SESSION['user_guid'];
  $project_name = htmlspecialchars($_POST["title"], ENT_QUOTES);
  if ($_POST["module"]=="") {
    $payload = htmlspecialchars($_POST["code"], ENT_QUOTES);
  } else {
    $payload = htmlspecialchars($_POST["module"], ENT_QUOTES);
  }
  $stmt->bind_param("ssss", $guid, $user_guid,$project_name, $payload);
  $stmt->execute();
  header("Location: /project/index.php");
?>
