<?php global $wpdb; ?>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"><!-- //--></script>
<script type="text/javascript"><!--
	function wpLinkVault(path,postId) {
		var anchorLink = $("input[name=anchor-link-vault-phrase]").val();
		var linkVault = $("input[name=wp-link-vault-phrase]").val();
		var persistentValue = $("input[name=persistentValue]").val();
		if(document.getElementById('global-link-check').checked == true) {
			var globalLink = '1';
			var post_id = '0';
		} else {
			var globalLink = '0';
			var post_id = postId;
		}
		if(anchorLink == false || linkVault == false) {
			$("#results").html("<p><b>Anchor List Or Link Cannot Be Blank</b></p>");
		} else {
			jQuery.ajax({
				type: "POST",
				url: path+"/wp-content/plugins/link-vault/views/addLinkVault.php",
				data: "anchor-link-vault-phrase=" + anchorLink + "&wp-link-vault-phrase=" + linkVault + "&globalLink=" + globalLink + "&persistentValue=" + persistentValue + "&postId=" + post_id,
				error: function() {
					alert("Sorry internal error");
				},
				timeout:3000,
				success: function(success) {
					links = success.split('#####');
										
					if(links[1] != 1) {
						$("#results").html("<span style='position:static;'>"+success+"</span>");
					} else {
						$("#results1").html("<span style='position:static;'>"+links[0]+"</span>");
					}
					document.getElementById('anchor-link-vault-phrase').value="";
					document.getElementById('wp-link-vault-phrase').value="";
					$("#refreshPage").load(path+"/wp-admin/post.php?post="+postId+"&action=edit #refreshPage");
					
				}
			})
		}
	}

	function deleteWpLinkVault(path,meta_id,clearlink,clearLinkValue,postId) {
		jQuery.ajax({
			type: "POST",
			url: path+"/wp-content/plugins/link-vault/views/deleteLinkVault.php",
			data: "meta_id=" + meta_id,
			error: function() {
				alert("Sorry internal error");
			},
			timeout:3000,
			success: function(success) {
				$("#results").html("<p>"+success+"</p>");
				document.getElementById(clearlink).innerHTML="";
				document.getElementById(clearLinkValue).innerHTML="";
				$("#refreshPage").load(path+"/wp-admin/post.php?post="+postId+"&action=edit #refreshPage");
			}
		})
	}
	
	function refreshPartOfPage() {
		$("#refreshPage").load(path+"/wp-admin/post-new.php #refreshPage");
	}
//--></script>
<?php 
global $post;	
$custom = $post->ID;
?>
<style>
.vaultLinks {
	float:left;
	margin: 8px 0 0 -9px;
	display: inline-block;
	display: block;
	
	margin-top:3px;
	margin-bottom:1em;
	font-size:12px;	
}
</style>
<div id="refreshPage">
	<input type="hidden" name="returnValue" value="" />
	<p>
		<?php _e('Anchor Text'); ?> <input type="text" name="anchor-link-vault-phrase" id="anchor-link-vault-phrase" />
	</p>
	<p>
		<?php _e('Link'); ?><input class="large-text" type="text" name="wp-link-vault-phrase" id="wp-link-vault-phrase" />
	</p>
	<p>
		<?php _e('Persistent Link'); ?> <input type="checkbox" name="global-link-check" id="global-link-check" />
	</p>
	<p>
		 <input type="button" onClick="wpLinkVault('<?php echo get_option('home'); ?>','<?php echo $custom;?>')" value="Add" />
	</p>
	<div class="wpLinkVault-analyze-action">
		<div>
			<table width="100%"><tr><td width="42%" valign="top">Persistent Link : </td>
			<td align="justify">
			<?php
			$postId1 = $_GET['post'];
			$query = "SELECT meta_id,meta_key,meta_value FROM wp_postmeta WHERE post_id=0";
			$result=mysql_query($query);
			$num=mysql_num_rows($result);
			$varPath = get_option('home');
			$i=0;
			$j=0;
			$path = get_option('home');
			while ($i < $num) {
				$meta_id = mysql_result($result,$i,"meta_id");
				$meta_key = mysql_result($result,$i,"meta_key");
				$meta_value = mysql_result($result,$i,"meta_value");
				$metaArray = explode("#####",$meta_value);
				if($metaArray[1] == 1) {
			?>
				<a href="javascript:void(0)" id="clearLink<?php echo $i;?>" onclick="deleteWpLinkVault('<?php echo $path; ?>','<?php echo $meta_id;?>','clearLink<?php echo $i;?>','clearLinkValue<?php echo $i;?>','<?php echo $postId1; ?>')"><img src="<?php echo $varPath;?>/wp-admin/images/no.png" border="0" width="8px"></a><a href="<?php echo $metaArray[0];?>" target="_blank" id="clearLinkValue<?php echo $i;?>"><?php echo $meta_key; ?></a> &nbsp;
			<?php
				$j++;
				}
				$i++;
				if($j > 1) {
					echo "<br /><br />";
					$j = 0;
				}
			}
			?>
			</td>
			</tr>
			</table>
	</div>
	<div id="results1"></div>
		<br class="clear" />
		<div>
			<table width="100%"><tr><td width="35%" valign="top">Post Link : </td>
			<td align="justify">
			<?php			
			$postId = $_GET['post'];
			$queryVault = "SELECT meta_id,meta_key,meta_value FROM wp_postmeta WHERE (post_id='$postId' AND post_id!='0')";
			$resultVault=mysql_query($queryVault);
			$numVault=mysql_num_rows($resultVault);
			$varPathVault = get_option('home');
			$a=0;
			$id=0;
			$k=0;
			$pathVault = get_option('home');
			while ($rs = mysql_fetch_array($resultVault)) {
				$meta_idVault = $rs["meta_id"];
				$meta_keyVault = $rs["meta_key"];
				$meta_valueVault = $rs["meta_value"];
				$metaArrayVault = explode("#####",$meta_valueVault);
				if($metaArrayVault[1] === "0") {
					$id1 = $id * 10;
			?>
				<a href="javascript:void(0)" id="clearLink<?php echo $id1;?>" onclick="deleteWpLinkVault('<?php echo $pathVault; ?>','<?php echo $meta_idVault;?>','clearLink<?php echo $id1;?>','clearLinkValue<?php echo $id1;?>','<?php echo $postId; ?>')"><img src="<?php echo $varPathVault;?>/wp-admin/images/no.png" border="0" width="8px"></a><a href="<?php echo $metaArrayVault[0];?>" target="_blank" id="clearLinkValue<?php echo $id1;?>"><?php echo $meta_keyVault; ?></a> &nbsp;
			<?php
				$id++;
				$k++;
				}
				$a++;
				if($k > 1) {
					echo "<br /><br />";
					$k = 0;
				}
			}
			?>
			</td>
			</tr>
			</table>
	</div>
	<div id="results" class="vaultLinks"></div>
		<br class="clear" />
	</div>
	<input type="hidden" name="persistentValue" value="<?php echo $i;?>" />
</div>