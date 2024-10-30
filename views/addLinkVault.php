<?php 
	require("../../../../wp-blog-header.php");
	header("HTTP/1.1 200 OK");
	global $wpdb;
	$anchorLink = $_POST['anchor-link-vault-phrase'];
	$link = $_POST['wp-link-vault-phrase'];
	$globalLink = $_POST['globalLink'];
	$postId = $_POST['postId'];
	$sqlSElect = "SELECT meta_key from wp_postmeta WHERE post_id=0 AND meta_key='".$anchorLink."'";
	$result = mysql_query($sqlSElect);
	
	$sqlMax = "SELECT max(meta_id) from wp_postmeta";
	$resultMax = mysql_query($sqlMax);
	$row1 = mysql_fetch_array($resultMax);
	$max_id = $row1[0];

	$num = mysql_num_rows($result);
	$new_value = $link."#####".$globalLink;
	$varPath = get_option('home');
	if($num<=0) {
		$sql1  = "INSERT INTO wp_postmeta(post_id,meta_key,meta_value) VALUES ('".$postId."','".$anchorLink."','".$new_value."')";
		$result1 = $wpdb->query($sql1);
		$meta_id=mysql_insert_id();
		if($globalLink != '1') {
	?>
			<a href="javascript:void(0)" id="clearLink<?php echo $max_id;?>" onclick="deleteWpLinkVault('<?php echo $varPath;?>','<?php echo $meta_id;?>','clearLink<?php echo $max_id;?>','clearLinkValue<?php echo $max_id;?>')"><img src="<?php echo $varPath;?>/wp-admin/images/no.png" border="0" width="8px" height="8px"></a><a href="<?php echo $link;?>" target="_blank" id="clearLinkValue<?php echo $max_id;?>"><?php echo $anchorLink;?></a>
	<?php	} else if($globalLink == '1'){ ?>	
			<a href="javascript:void(0)" id="clearLink<?php echo $max_id;?>" onclick="deleteWpLinkVault('<?php echo $varPath;?>','<?php echo $meta_id; ?>','clearLink<?php echo $max_id; ?>','clearLinkValue<?php echo $max_id; ?>')"><img src="<?php echo $varPath;?>/wp-admin/images/no.png" border="0" width="8px" height="8px"></a><a href="<?php echo $link;?>" target="_blank" id="clearLinkValue<?php echo $max_id;?>"><?php echo $anchorLink;?></a>#####1
	<?php
		}
	}
?>
