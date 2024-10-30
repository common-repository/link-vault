<?php 
	require('../../../../wp-blog-header.php');
	header("HTTP/1.1 200 OK");
	global $wpdb;
	$meta_id = $_POST['meta_id'];
	$sql1  = "DELETE FROM wp_postmeta WHERE meta_id='".$meta_id."'";
	$result1 = $wpdb->query($sql1);
?>
