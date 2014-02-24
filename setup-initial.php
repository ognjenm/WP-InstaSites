<?php				
	// handle if this is a post
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(!empty($_POST['bapi_secureurl'])){ //In case client-side validation is not triggered, prevent protocol from being included in secure url.
			$securl = $_POST['bapi_secureurl'];
			$securl = str_replace("http://","",$securl);
			$securl = str_replace("https://","",$securl);
		}
		update_option('bapi_site_cdn_domain', $_POST['bapi_site_cdn_domain']);
		update_option('api_key', $_POST['api_key']);
		update_option('bapi_baseurl', $_POST['bapi_baseurl']);
		update_option('bapi_secureurl', $securl);
		update_option('bapi_cloudfronturl', $_POST['bapi_cloudfronturl']);
		
		echo '<div id="message" class="updated"><p><strong>Settings saved.</strong></p></div>';
	}
	
	$bapi_baseurl = 'connect.bookt.com';
	if(get_option('bapi_baseurl')){ $bapi_baseurl = get_option('bapi_baseurl'); }
	if(empty($bapi_baseurl)){ $bapi_baseurl = 'connect.bookt.com'; }
	
	$cdn_url = get_option('home');
	if(get_option('bapi_site_cdn_domain')){ $cdn_url = get_option('bapi_site_cdn_domain'); }
	
	$surl = '';
	if(get_option('bapi_secureurl')){ $surl = get_option('bapi_secureurl'); }
	
	$cloudfronturl = get_option('bapi_cloudfronturl');		
	
	$lang = get_option('bapi_language');
	if((get_option('bapi_language')=='')&&(get_option('api_key')=='')){
		$lang = 'en-US';
	}
?> 
<script type="text/javascript">
	var baseURL = '<?= getbapiurl() ?>'; // TODO: (JACOB) set this to bapi_baseurl
	jQuery(document).ready(function($){
		$('#validate-apikey').click(function() {
			var apikey = $('#apikey').val();
			if (apikey===null || apikey=='') {
				alert("API key must not be blank");
				return;
			}
			var url = baseURL + "/ws/?method=search&entity=property&pagesize=10&page=1&apikey=" + apikey;
			$.ajax({ 
				"url": url, 
				"success": function(data, textStatus, jqXHR) { 
					//console.log(data);
					if(data.status !== undefined && data.error === undefined){
						alert("This is a valid api key"); 
					}
					else{
						alert(data.error.message);
					}
				},
				"error": function(a,b,c) { alert("This is not a valid api key"); } 
			});			
		});
	});
	function validateURL(val){
		if(val.slice(-1)=="/"){
			val = val.substring(0,val.length-1);
			jQuery('#site_url_input').val(val);
		}
		if(val.indexOf("www")==-1){
			var c = confirm('The Site URL you have entered does not contain "www".\n\nCloudFront CDN and Redirection not supported in this configuration.\n\nAre you sure you wish to proceed?');
			if(c){
				return true;
			}
			else{
				jQuery('#site_url_input').focus();
				return false;
			}
		}
	}
	function cleanSecureURL(val){
		var n = val.replace("https://","");
		var n = n.replace("http://","");
		jQuery('#site_secure_url_input').val(n);
	}
</script>
<div class="wrap">
<h1><a href="http://www.bookt.com" target="_blank"><img src="<?= plugins_url('/img/logo.png', __FILE__) ?>" /></a></h1>
<h2>InstaSite Plugin - Initial Setup</h2>
<form method="post">
<table class="form-table">
<tr valign="top">
	<td scope="row">Site URL:</td>
	<td><input type="text" name="bapi_site_cdn_domain" id="site_url_input" size="60" value="<?php echo $cdn_url; ?>" onBlur="validateURL(this.value)"/></td>
</tr>
<tr valign="top">
	<td scope="row">API Key:</td>
	<td><input type="text" name="api_key" id="apikey" size="60" value="<?php echo get_option('api_key'); ?>" />
		<a href="javascript:void(0)" id="validate-apikey">Validate</a>
	</td>
</tr>
<tr valign="top" style="<?php if(!is_super_admin()){echo 'display:none;'; } ?>">
	<td scope="row">BAPI Base URL:</td>
	<td><input type="text" name="bapi_baseurl" size="60" value="<?php echo $bapi_baseurl; ?>" /></td>
</tr>
<tr valign="top" style="<?php if(!is_super_admin()){echo 'display:none;'; } ?>">
	<td scope="row">Secure Site URL:</td>
	<td><input type="text" id="site_secure_url_input" name="bapi_secureurl" size="60" value="<?php echo $surl; ?>" onBlur="cleanSecureURL(this.value)" /></td>
</tr>
<tr valign="top" style="<?php if(!is_super_admin()){echo 'display:none;'; } ?>">
	<td scope="row">Cloudfront URL:</td>
	<td><input type="text" name="bapi_cloudfronturl" size="60" value="<?php echo $cloudfronturl; ?>" /></td>
</tr>
<tr>
	<td colspan="2"><em>If you do not already have an API key for Bookt, please contact <a href="mailto:support@bookt.com?subject=API%20Key%20-%20Wordpress%20Plugin">support@bookt.com</a> to obtain an API key.</em></td>
</tr>
</table>
<div class="clear"></div>
<?php submit_button(); ?>
</form>
</div>
