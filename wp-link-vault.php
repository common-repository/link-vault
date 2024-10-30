<?php 
/*
Plugin Name: Link Vault
Plugin URI: http://wphost.co/plugins/link-vault?ap_id=linkvault
Description: Link Vault allows you to store your favorite links, including affiliate links, right in your WordPress editor so that you can drag and drop links into your post.  Proper usage saves 9 keyboard/mouse touches when creating a hyperlink.  For usage and updates, visit http://wphost.co/plugins/link-vault .
Version: 1.0.9
Author: HostCo
Author URI: http://wphost.co?ap_id=linkvault
*/
define( 'WPLINKVAULT_DEBUG', false );
if (!class_exists('WpLinkVault')) {

	class WpLinkVault {	
		var $version = '1.0.8';		
		function WpLinkVault() {
			$this->addVaultActions();
			wp_register_style('wpLinkVault', plugins_url('css/wpLinkVault.css', __FILE__), array(), $this->version);
		}
		
		function addVaultActions() {
			add_action('admin_menu', 'my_plugin_menu');
			function my_plugin_menu() {
				add_options_page('Link Vault Options', 'Link Vault', 'manage_options', 'link_vault', 'link_vault_options');
			}
			
			function link_vault_options() {
				$varPath = get_option('home');
				$uploadFiles = $varPath."/wp-content/plugins/link-vault/hostco_120x240.jpg";
			?>
				<div class="wrap">
				<h2><?php echo "Link Vault"; ?></h2>
				
				<form name="form" action="options-permalink.php" method="post">
				<?php wp_nonce_field('update-permalink') ?>
				
				  <table align="center">
				  	<tr>
						<td width="50%" height="80px" valign="top"><?php _e('Link Vault allows you to organize your links prior to writing a blog post and persistently store commonly used links including affiliate marketing links'); ?></td>					
						<td rowspan="2"><a href="http://wphost.co?ap_id=linkvault" target="_blank"><img src="<?php echo $uploadFiles;?>" /></a></td>
					</tr>
					<tr>
						<td valign="top"><?php _e('Visit http://wphost.co/plugins/link-vault for a screencast walk through. Brought to you by the premium Wordpress hosting team at ')?><a href="http://wphost.co?ap_id=linkvault"><?php _e('HostCo');?></a></td>
					</tr>
				</table>
				<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
				<script type="text/javascript">
					function deleteLinkVault(path,meta_id,clearlink,clearLinkValue) {
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
							}
						})
					}
				</script>
				  
				<h3><?php _e('Persistent Link'); ?></h3>
				<table width="50%">
					<tr><td>
				<?php
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
						if($metaArray[1] == 1){
				?>
						<a href="javascript:void(0)" id="clearLink<?php echo $i;?>" onclick="deleteLinkVault('<?php echo $path; ?>','<?php echo $meta_id;?>','clearLink<?php echo $i;?>','clearLinkValue<?php echo $i;?>')"><img src="<?php echo $varPath;?>/wp-admin/images/no.png" border="0" width="8px"></a>&nbsp;<a href="<?php echo $metaArray[0];?>" target="_blank" id="clearLinkValue<?php echo $i;?>"><?php echo $meta_key; ?></a>&nbsp;&nbsp;						
				<?php
						$j++;
					}
					$i++;
					
					if($j > 5) {
						echo "<br /><br />";
						$j=0;
					}
				}
				?></td>
				</tr>
				</table>				
		<?php }
			add_action('admin_menu', array(&$this, 'addAdminInterfaceLinks'));
			register_deactivation_hook(__FILE__, array(&$this, 'removeWpLinkVault'));
		}
		
		function addAdminInterfaceLinks() {
			foreach($this->getSupportedPostTypesVault() as $type) {
				add_meta_box('wpLinkVault', __('Link Vault'), array(&$this, 'displayLinkVaultMetaBox'), $type, 'side', 'high');
			}

			global $pagenow;
			if (false !== strpos($pagenow, 'post') || false !== strpos($pagenow, 'page') || false !== strpos($pagenow, 'scribe') || $_GET['page'] == 'scribe' || false !== strpos($pagenow, 'edit')) {
				wp_enqueue_style('wpLinkVault');
				wp_enqueue_script('wpLinkVault');
			}
		}
		
		function getSupportedPostTypesVault() {
			$settings = $this->getSettings();
			if(!is_array($settings['wpLinkVault-post-types'])) {
				return array('post','page','spodcast','videopost','photopost');
			} else {
				return $settings['wpLinkVault-post-types'];
			}
		}
		
		function getSettings() {
			if (null === $this->settings) {
				$this->settings = get_option($this->_option_wpLinkVaultSettings, array());
				$this->settings = is_array($this->settings) ? $this->settings : array();
			}
			return $this->settings;
		}
		
		function displayLinkVaultMetaBox($post) {
			include('views/link-vault.php');
		}
		
		function removeWpLinkVault() {
			global $wpdb;
			$wpdb->query("DELETE FROM $wpdb->postmeta WHERE post_id = '0'");
		}
	}
}
$WpLinkVault = new WpLinkVault;
?>