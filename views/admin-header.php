<?php
$settings = $this->getSettings();
?>
<!-- Start wpLinkVault Output -->
<script type="text/javascript">
var wpLinkVault_dependency = '<?php echo $dependency; ?>';
var wpLinkVault_element_title = '';
var wpLinkVault_element_description = '';
<?php if($dependency == 'user-defined') { ?>
wpLinkVault_element_title = '<?php echo $settings['seo-tool']['title']; ?>';
wpLinkVault_element_description = '<?php echo $settings['seo-tool']['description']; ?>';
<?php } ?>
var wpLinkVault = new wpLinkVault(wpLinkVault_dependency, wpLinkVault_element_title, wpLinkVault_element_description);
function wpLinkVault_addTinyMCEEvent(ed) {
	ed.onChange.add(function(ed, e) { wpLinkVault.blurEvent(); } );
}
</script>
<!-- End wpLinkVault Output -->