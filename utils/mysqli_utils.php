<?php
  $con = new mysqli($host, $dbuser, $dbpass, $dbname);
  if ($con->connect_error)
  {
    die("connected failed:".$con->connect_error);
  }
  $con->query("set NAMES utf8");
?>
