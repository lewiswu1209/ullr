<?php
  function short($data) {
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
?>
