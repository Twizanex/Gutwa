<?php
/**
 * Upload a new gutwa admin
 *
 * @package ElggGutwa  
 */
 
        global $plugin;
	$plugin = elgg_get_plugin_from_id('gutwa');
 
 
elgg_load_library('elgg:gutwa');
elgg_load_js('elgg.qtips');
$owner = elgg_get_page_owner_entity();

$user = elgg_get_logged_in_user_entity();


$details = elgg_get_logged_in_user_entity();
$user_details = array_values((array) $details);
//$advertiser_id = $user_details[6]['advertiser_account'];
$advertiser_id = $user->advertiser_account;

$aParams = array(new XML_RPC_Value($plugin->admin_user , 'string'),new XML_RPC_Value($plugin->admin_password , 'string'));
$oMessage  = new XML_RPC_Message('ox.logon', $aParams);
$oClient = new XML_RPC_Client( $plugin->red5port , $plugin->red5host );
$oResponse = $oClient->send($oMessage);
$sessionId = returnXmlRpcResponseData($oResponse);



//Check if advertiser account exist in openx,if not create new account
$aParams    = array(
            new XML_RPC_Value($sessionId, 'string'),new XML_RPC_Value($advertiser_id, 'int'));							
$oMessage  = new XML_RPC_Message('ox.getAdvertiser', $aParams);
$oResponse = $oClient->send($oMessage);
$account_details = returnXmlRpcResponseData($oResponse);

//print_r ($account_details); // deburging

if(empty($account_details)) {
	register_error(elgg_echo('Kindly login out and login back again to create your advertiser account'));
	forward(REFERER);
}


//Get campaignIDs for current advertiser
$aParams    = array(
            new XML_RPC_Value($sessionId, 'string'),new XML_RPC_Value($advertiser_id, 'int'),

            );	
			
$oMessage  = new XML_RPC_Message('ox.getCampaignListByAdvertiserId', $aParams);
$oResponse = $oClient->send($oMessage);
$oCamp_list = returnXmlRpcResponseData($oResponse);

$i=0;
foreach($oCamp_list as $value) {
$i++;
$camp_id = $value['campaignId'];
$aParams    = array(
            new XML_RPC_Value($sessionId, 'string'),new XML_RPC_Value($camp_id, 'int'),

            );	
			
$oMessage  = new XML_RPC_Message('ox.getBannerListByCampaignId', $aParams);
$oResponse = $oClient->send($oMessage);
$oBanner_list[$i] = returnXmlRpcResponseData($oResponse);
}



function printValue($value, $key, $userData) 
{
	
	$userData[] = $value;
}


$result = new ArrayObject();
array_walk_recursive($oBanner_list, 'printValue', $result);

$Banner_list =  (array) $result;
$output_array = array_chunk($Banner_list, 28);

// Logoff
$aParams = array(new XML_RPC_Value($sessionId, 'string'));
$oMessage = new XML_RPC_Message('ox.logoff', $aParams);
$oResponse = $oClient->send($oMessage);


elgg_register_menu_item('title', array(
	'name' => 'download',
	'text' => elgg_echo('Upload a Banner'),
	'href' => "gutwa/addBanner/id=$advertiser_id",
	'link_class' => 'elgg-button elgg-button-action',
));



gatekeeper();
group_gatekeeper();

$title = elgg_echo('Openx Banners');

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('gutwa'), "gutwa/all");
if (elgg_instanceof($owner, 'user')) {
	elgg_push_breadcrumb($owner->name, "gutwa/owner/$owner->username");
} else {
	elgg_push_breadcrumb($owner->name, "gutwa/group/$owner->guid/all");
}
elgg_push_breadcrumb($title);

// create form


$form_vars = array('enctype' => 'multipart/form-data');
$body_vars = gutwa_prepare_form_vars();
$content = "<table style='border:#0099FF thin 1px;width:100%;'><thead>
<tr>
<td width='25%'><b>Banner Name<b/></td><td width='25%'><b>Destination Link</b></td><td width='25%'><b>Status</b></td><td width='25%'><b>Action</b></td></tr></thead>";
if(!empty($oBanner_list)) {

foreach($output_array as $value) {

if($value[12]==1)
  $Status = "In-active"	;
if($value[12]==0)
  $Status = "Running"	;
if($value[12]==2)
  $Status = "Not-yet Approved"	;
if($value[12]==3){
$tool_tip = $value[27];
  $Status = "<a href='#' title='$tool_tip'>Denied</a>"	;
}
$content .="<tr>
<td>".$value[2]."</td>
<td>".$value[10]."</td>

<td>".$Status."</td>";
if($value[12]==3) {
$content .="<td>" .$delete = elgg_view('output/url', array(
		'href' => "gutwa/delete?banner_id=".$value[0]."&camp_id=".$value[1]."",
		'text' => '<span class="elgg-icon elgg-icon-delete"></span>',
		'is_action' => true,
		'confirm' => elgg_echo('deleteconfirm'),
		//'encode_text' => false
		'is_trusted' => true,
	)). 
" &nbsp;&nbsp;<a href='stat?ban_id =".$value[0]."'>View Statistics</a> &nbsp;&nbsp;<a href='banner_edit?ban_id=".$value[0]."'>Edit</a> </td></tr>";
}
elseif($value[12]==1) {
$content .="<td>" .$delete = elgg_view('output/url', array(
		'href' => "gutwa/delete?banner_id=".$value[0]."&camp_id=".$value[1]."",
		'text' => '<span class="elgg-icon elgg-icon-delete"></span>',
		'is_action' => true,
		'confirm' => elgg_echo('deleteconfirm'),
		//'encode_text' => false
		'is_trusted' => true,
	)). 
" &nbsp;&nbsp;<a href='stat?ban_id =".$value[0]."'>View Statistics</a> &nbsp;&nbsp;
<a href='renew?renew_id=".$value[0]."&camp_id=".$value[1]."'>Renewal</a> </td></tr>";

}
else {
$content .="<td>" .$delete = elgg_view('output/url', array(
		'href' => "gutwa/delete?banner_id=".$value[0]."&camp_id=".$value[1]."",
		'text' => '<span class="elgg-icon elgg-icon-delete"></span>',
		'is_action' => true,
		'confirm' => elgg_echo('deleteconfirm'),
		//'encode_text' => false
		'is_trusted' => true,
	)). 
" &nbsp;&nbsp;<a href='stat?ban_id =".$value[0]."'>View Statistics</a></td>
</tr>";
	}
}
}
else {
$content .="<tr><td rowspan=4> No Banners available to display</td></tr>";
}
$content .="</table>";

//$content .= elgg_view_form('gutwa/upload', $form_vars, $body_vars);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);
