<?php
  include("utils/guid_utils.inc");
  
  include("utils/net.inc");

  include("config/db-creds.inc");
  include("utils/mysqli_utils.php");

  $stmt = $con->prepare("INSERT INTO `result` (`guid`, `project_guid`, `ip_addr`, `content`, `headers`) VALUES (?, ?, ?, ?, ?);");
  $guid = create_guid();
  $project_guid = $_REQUEST['id'];
  $ip = getip();
  $content = '';
  foreach ($_REQUEST as $name => $value) {
    if ($name != 'id') {
      $content = $content."$name: $value<br/>";
    }
  }
  $headers = '';
  foreach (getallheaders() as $name => $value) {
    $headers = $headers."$name: $value<br/>";
  }
  $stmt->bind_param("sssss", $guid, $project_guid, $ip, htmlspecialchars($content, ENT_QUOTES), htmlspecialchars($headers, ENT_QUOTES));
  $stmt->execute();
?>
