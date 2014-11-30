<?php
/** 
 *       
 * Elgg gutwa upload/save form
 *
 * @package ElggGutwa
 */

        global $plugin;
	$plugin = elgg_get_plugin_from_id('gutwa');


$_SERVER['REQUEST_URI']=$_SERVER['PHP_SELF'].'?'.$_SERVER['argv'][0];
$uri = urldecode($_SERVER['REQUEST_URI']);
//print_r($uri);
$ban_id = explode('&',$uri);
$banner_id = explode('=',$ban_id[2]);
$camp = explode('=',$ban_id[3]);
$campaignID = $camp[1];

$title = elgg_extract('title', $vars, '');
$ad_text = elgg_extract('ad_text', $vars, '');
$destination_url = elgg_extract('destination_url',$vars,'');
if($banner_id[0]=='ban_id') 

$bannerId = $banner_id[1];

if($banner_id[0]=='renew_id')
$renewId = $banner_id[1];


//$advertiser_id = $_GET['id'] || $_POST['id'];
//print_r($advertiser_id);
//$debug = true;

// $kenyawetu =$CONFIG->url;
 
// print_r ($kenyawetu); // TM: For debug

$oClient = new XML_RPC_Client( $plugin->red5port , $plugin->red5host );
//$oClient->setdebug($debug);
$aParams = array(new XML_RPC_Value($plugin->admin_user , 'string'),new XML_RPC_Value($plugin->admin_password , 'string'));
$oMessage  = new XML_RPC_Message('ox.logon', $aParams);
$oResponse = $oClient->send($oMessage);
$sessionId = returnXmlRpcResponseData($oResponse);
if(!isset($bannerId) ) {
$aParams    = array(
            new XML_RPC_Value($sessionId, 'string'),new XML_RPC_Value('1','int')
            
            );							

$oMessage  = new XML_RPC_Message('ox.getInventoryList', $aParams);

$oResponse = $oClient->send($oMessage);

$inventory_details = returnXmlRpcResponseData($oResponse);
}
$aParams    = array(
            new XML_RPC_Value($sessionId, 'string'),
            
            );							

$oMessage  = new XML_RPC_Message('ox.getSpecificationList', $aParams);

$oResponse = $oClient->send($oMessage);

$banner_spec = returnXmlRpcResponseData($oResponse);



//$account_id = $account_details['accountId'];
if(!isset($bannerId) ) {
$Inv_details = array();
$sep = '_';
foreach($inventory_details as $value)
	{
		
		$Inv_details[$value['cost'].$sep.$value['clicks'].$sep.$value['inventory_id'].$sep.$value['impressions']] = $value['detail']; 
//		$cost[$value['inventory_id']] = $value['cost'];

	}
}	
$open_braces = '(';
$close_braces = ')';
$sep = '_';
foreach($banner_spec as $value)
	{
		
		$banner_specification[$value['width'].$sep.$value['height']] = ucfirst($value['spec_name']).$open_braces.$value['width'].'x'.$value['height'].$close_braces; 
//		$cost[$value['inventory_id']] = $value['cost'];

	}
//print_r($cost);
// Logoff
$aParams = array(new XML_RPC_Value($sessionId, 'string'));
$oMessage = new XML_RPC_Message('ox.logoff', $aParams);
$oResponse = $oClient->send($oMessage);

if (!$container_guid) {
	$container_guid = elgg_get_logged_in_user_guid();
}
$guid = elgg_extract('guid', $vars, null);

if ($guid) {
	$gutwa_label = elgg_echo("gutwa:replace");
	$submit_label = elgg_echo('save');
} else {
	$gutwa_label = elgg_echo("gutwa:gutwa");
	$submit_label = elgg_echo('upload');
}
if(!isset($bannerId) && !isset($campaignID) ) {
$title = isset($_SESSION['title'])?$_SESSION['title']:$title;
$ad_text = isset($_SESSION['banner_text'])?$_SESSION['banner_text']:$ad_text;
//$destination_url = isset($_SESSION['destination_url'])?$_SESSION['destination_url']:$destination_url;
unset($_SESSION['banner_text']);
unset($_SESSION['destination_url']);
unset($_SESSION['title']);
$country_targeting = 'IN';
?>

<div>
	<label><?php echo elgg_echo('Banner Name'); ?></label><br />
	<?php echo elgg_view('input/text', array('name' => 'title', 'value' => $title,'maxlength'=>25)); ?>
</div>

<div>
	<label><?php echo elgg_echo('Ad Text'); ?></label><br />
	<?php echo elgg_view('input/text', array('name' => 'ad_text', 'value' => $ad_text,'maxlength'=>70)); ?>
</div>

<div>
	<label><?php echo elgg_echo('Allowed Banner sizes'); ?></label>
	<?php echo elgg_view('input/dropdown', 
		array(
		'options_values' => $banner_specification,
		'name' => 'banner_specification',
		'id' => 'banner_specification',
		'value' => $cost)
); ?> 
</div>
<div>
	<label><?php echo $gutwa_label; ?></label><br />
	<?php echo elgg_view('input/file', array('name' => 'upload')); ?>
</div>
<div>
	<label><?php echo elgg_echo('Destination-Url'); ?></label><br />
	<?php echo elgg_view('input/text', array('name' => 'destination_url', 'value' => $destination_url)); ?>
</div>

<div>
	<label><?php echo elgg_echo('Campaign Package'); ?></label>
	<?php echo elgg_view('input/dropdown', 
		array(
		'options_values' => $Inv_details,
		'name' => 'inventory_products[]',
		'id' => 'inventory_prod',
		'value' => $cost)
); 

unset($_SESSION['banner_text']);
unset($_SESSION['destination_url']);
unset($_SESSION['title']);

?> 

<script type="text/javascript">
function payment_detail(){



var e = document.getElementById("inventory_prod");
    var strSel = e.options[e.selectedIndex].value;
	var nwScript = document.getElementById('pay_now');
	window.open("<?=$CONFIG->url?>gutwa/elgg_payment?details="+strSel, '__blank');
//alert(strSel);
// =$CONFIG->url Output    http://yoursite.com/ 
}
</script>

<script type="text/javascript">
        $(document).ready(function() {
        
       
        

	   $(".chzn-select").chosen(); 	
            $("#input-facebook-theme").tokenInput("<?=$CONFIG->url?>gutwa/country", {
                theme: "facebook"
            });

	   $("#input-facebook-theme-category").tokenInput("<?=$CONFIG->url?>gutwa/category", {
                theme: "facebook"
            });	

	 $("#input-facebook-theme-keyword").tokenInput("<?=$CONFIG->url?>gutwa/gutwa/keyword", {
                theme: "facebook"
            });	
        });
        </script>
<label>

<a style="text-decoration:none"  id="pay_now" 
href="javascript:void(0);" onclick="payment_detail();">Pay-Now</a></label>


</div>


<div>
	<label><?php echo elgg_echo('Country Targeting'); ?></label><br />
	<select data-placeholder="Select Country to target" style="width:350px;" multiple class="chzn-select" tabindex="8" name="country_targeting[]">
          <option value=""></option>
  <option value="AD">Andorra</option>
<option value="AE">United Arab Emirates</option>
<option value="AF">Afghanistan</option>
<option value="AG">Antigua and Barbuda</option>
<option value="AI">Anguilla</option>
<option value="AL">Albania</option>
<option value="AM">Armenia</option>
<option value="AN">Netherlands Antilles</option>

<option value="AO">Angola</option>
<option value="AP">Asia/Pacific Region</option>
<option value="AQ">Antarctica</option>
<option value="AR">Argentina</option>
<option value="AS">American Samoa</option>

<option value="AT">Austria</option>
<option value="AU">Australia</option>
<option value="AW">Aruba</option>
<option value="AX">Aland Islands</option>
<option value="AZ">Azerbaijan</option>

        </select>
</div>


<div>
	<label><?php echo elgg_echo('Category'); ?></label><br />
	<select data-placeholder="Select Your Favorite Category" style="width:350px;" 
multiple class="chzn-select" tabindex="8" name="category_targeting[]">
          <option value=""></option>
          <option value="Art">Art</option>
          <option value="Music">Music</option>
          <option value="Photo">Photo</option>
          <option value="Rentals">Rentals</option>
          <option value="Books">Books</option>
          <option value="Television">Television</option>
          <option value="Magazine">Magazine</option>
          <option value="Office">Office</option>
	<option value="Marketing">Marketing</option>

        </select>
</div>

<div>
	<label><?php echo elgg_echo('Additional Data'); ?></label><br />
	<select data-placeholder="Select Your Keyword" style="width:350px;" 
multiple class="chzn-select" tabindex="8" name="extra_info[]">
          <option value=""></option>
        
          <option value="Reebok">Reebok</option>
          <option value="Cricket">Cricket</option>
          <option value="FootBall">FootBall</option>
          <option value="BaseketBall">BaseketBall</option>
          <option value="Nike">Nike</option>
          <option value="Television">Television</option>
          <option value="Magazine">Magazine</option>
          <option value="Office">Office</option>
	<option value="Marketing">Marketing</option>
        </select>
</div>
<?php
$agree = array(0=>'I agree to the terms and condition',1=>'I dont agree to the terms and condition');
?>

<div>
	<label><?php echo elgg_echo('User Agreement'); ?></label><br />
	<?php echo elgg_view('input/radio', array('name' => 'aggree[]', 'options' => $agree,'id'=>'agree_rad')); ?>
</div>

<?php 
}

if(isset($renewId) && isset($campaignID) ) {?>

<div>
	<label><?php echo elgg_echo('Campaign Package'); ?></label>
	<?php 
	echo elgg_view('input/hidden', array('name' => 'baner_id', 'value' => $renewId));
	echo elgg_view('input/hidden', array('name' => 'campaign_id', 'value' => $campaignID));
	echo elgg_view('input/dropdown', 
		array(
		'options_values' => $Inv_details,
		'name' => 'inventory_products[]',
		'id' => 'inventory_prod',
		'value' => $cost)
); ?>  </div>



<?php
}
if(isset($bannerId) ) {
?>
<div>
	<label><?php echo elgg_echo('Allowed Banner sizes'); ?></label>
	<?php echo elgg_view('input/dropdown', 
		array(
		'options_values' => $banner_specification,
		'name' => 'banner_specification',
		'id' => 'banner_specification',
		'value' => $cost)
); ?> 
</div>
<div>
	<label><?php echo $gutwa_label; ?></label><br />
	<?php echo elgg_view('input/gutwa', array('name' => 'reupload')); ?>
</div>


<div class="elgg-foot">

<?php
}
echo elgg_view('input/hidden', array('name' => 'banner_id', 'value' => $bannerId));


echo elgg_view('input/submit', array('value' => $submit_label));

?>
</div>
