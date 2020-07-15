<?php
  if ($_GET['id']) {
    header('Content-Type: text/javascript; charset=utf-8');
    include("config/config.inc");
    include("config/db-creds.inc");
    include("utils/mysqli_utils.php");
  
    $stmt = $con->prepare("SELECT `payload` FROM `project` WHERE `guid`=?");
    $stmt->bind_param("s", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!empty($result) && mysqli_num_rows($result) != 0) {
      while ($row = $result->fetch_array()) {
        $payload = str_replace("{projectId}", $_GET['id'], $row['payload']);
        $payload = str_replace("{host}", $https_url, $payload);
        echo htmlspecialchars_decode($payload, ENT_QUOTES);
      }
    }
  } else {
    header("Location: /user/login.php");
  }
?>

