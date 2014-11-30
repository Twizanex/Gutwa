<?php

/*
*  
*  ElggGutwa
*
*/



    global $plugin;
	$plugin = elgg_get_plugin_from_id('gutwa');

$_SERVER['REQUEST_URI']=$_SERVER['PHP_SELF'].'?'.$_SERVER['argv'][0];
$uri = urldecode($_SERVER['REQUEST_URI']);
$result = explode('&',$uri);
$ids = explode('=',$result[2]);
$bannerId = $ids[1];
//print_r($bannerId);
elgg_load_library('elgg:gutwa');


//print_r($advertiser_id);
$aParams = array(new XML_RPC_Value($plugin->admin_user , 'string'),new XML_RPC_Value($plugin->admin_password , 'string'));

$oMessage  = new XML_RPC_Message('ox.logon', $aParams);
$oClient = new XML_RPC_Client( $plugin->red5port , $plugin->red5host );
$oResponse = $oClient->send($oMessage);
$sessionId = returnXmlRpcResponseData($oResponse);


///Date picker input
$twodates=explode('-',$_POST['date']);

if(count($twodates)==1)
{
$oStartDate=date('Y-m-d',strtotime($twodates[0]));

$oEndDate=date('Y-m-d',strtotime($twodates[0]));
}
else
{
$oStartDate=date('Y-m-d',strtotime($twodates[0]));

$oEndDate=date('Y-m-d',strtotime($twodates[1]));
}
///Date picker input

$user = elgg_get_logged_in_user_entity();
if (isset($user->advertiser_account))
        {
            echo $user->advertiser_account;
        }

$aParams = array(
new XML_RPC_Value($sessionId, 'string'),
new XML_RPC_Value($bannerId, 'int'),
new XML_RPC_Value($oStartDate, 'dateTime.iso8601'),
new XML_RPC_Value($oEndDate, 'dateTime.iso8601'),
);
$oMessage  = new XML_RPC_Message('ox.bannerDailyStatistics', $aParams);
$oResponse = $oClient->send($oMessage);
if (!$oResponse) {
    die('Communication error: ' . $oClient->errstr);
}
$banner_Statistics = returnXmlRpcResponseData($oResponse);




//Get current Banner

$aParams = array(
new XML_RPC_Value($sessionId, 'string'),
new XML_RPC_Value($bannerId, 'int'),

);
$oMessage  = new XML_RPC_Message('ox.getBanner', $aParams);
$oResponse = $oClient->send($oMessage);
if (!$oResponse) {
    die('Communication error: ' . $oClient->errstr);
}
$banner_details = returnXmlRpcResponseData($oResponse);
//echo "<pre>";
//print_r($banner_details);
// Logoff
$aParams = array(new XML_RPC_Value($sessionId, 'string'));
$oMessage = new XML_RPC_Message('ox.logoff', $aParams);
$oResponse = $oClient->send($oMessage);


//echo "<pre>";
//print_r($oBanner_list);

gatekeeper();
group_gatekeeper();

$title = elgg_echo('Banner-Statistics');

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('gutwa'), "gutwa/all");
if (elgg_instanceof($owner, 'user')) {
	elgg_push_breadcrumb($owner->name, "gutwa/owner/$owner->username");
} else {
	elgg_push_breadcrumb($owner->name, "gutwa/group/$owner->guid/all");
}
elgg_push_breadcrumb($title);

// create form

if(!empty($_POST['date']))
{

$date=$_POST['date'];

}
else
{
$date='';
}



$content = "<form name='datepick' method='post'><div>
			<table><tr><td><input type='text' name='date' value='$date' id='rangeC' /> </td><td>&nbsp;</td><td><input type='submit' name='getdate' value='Go' width='15px' height='10px' ></td></tr></table><table><tr><td></td></tr></table>
		</div></form>";

$form_vars = array('enctype' => 'multipart/form-data');
$body_vars = gutwa_prepare_form_vars();

$content .="<table style='border:#0099FF thin 1px;width:100%;'><thead>
<tr>
<td width='25%'>Date</td>
<td width='25%'>Impressions </td><td width='25%'>Clicks</td> </tr></thead>";
if(!empty($banner_Statistics)) {

foreach($banner_Statistics as $value) {

$content .="<tr>
<td>".strftime("%b %e %Y",strtotime($value['day']))."</td>

<td>".$value['impressions']."</td>

<td>".$value['clicks']."</td>

</tr>";
}
}
else {

$content .="<tr><td rowspan=4> No Statistics available to display</td></tr>";
}
$content .="</table>";

//$content .= elgg_view_form('gutwa/upload', $form_vars, $body_vars);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);

?>

<script type="text/javascript">
			$(function(){
				
					$('#rangeC').daterangepicker({arrows: true});
					
			 });

  function periodFormSubmit() {
           document.datepick.submit();
           
        }

		</script>