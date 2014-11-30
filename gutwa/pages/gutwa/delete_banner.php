<?php
 /*
*
*  gutwa
*
*/ 

	   global $plugin;
	$plugin = elgg_get_plugin_from_id('gutwa');

$_SERVER['REQUEST_URI']=$_SERVER['PHP_SELF'].'?'.$_SERVER['argv'][0];
$uri = urldecode($_SERVER['REQUEST_URI']);
$result = explode('&',$uri);
$ban_id = explode('=',$result[2]);
$camp_id = explode('=',$result[3]);
$bannerID = $ban_id[1];
$campaignId = $camp_id[1];
//$bannerId = $ids[1];

$oClient = new XML_RPC_Client( $plugin->red5port , $plugin->red5host );
$aParams = array(new XML_RPC_Value($plugin->admin_user , 'string'),new XML_RPC_Value($plugin->admin_password , 'string'));
$oMessage  = new XML_RPC_Message('ox.logon', $aParams);
$oResponse = $oClient->send($oMessage);
$sessionId = returnXmlRpcResponseData($oResponse);


//Delete banner

$aParams = array(
new XML_RPC_Value($sessionId, 'string'),
new XML_RPC_Value($bannerID, 'int')
);
$oMessage  = new XML_RPC_Message('ox.deleteBanner', $aParams);
$oResponse = $oClient->send($oMessage);
if (!$oResponse) {
    die('Communication error: ' . $oClient->errstr);
}
returnXmlRpcResponseData($oResponse); 

//Delete Campaign


$aParams    = array(
            new XML_RPC_Value($sessionId, 'string'),new XML_RPC_Value($campaignId, 'int'),
           
            );				
$oMessage  = new XML_RPC_Message('ox.deleteCampaign', $aParams);
$oResponse = $oClient->send($oMessage);


// Logoff
$aParams = array(new XML_RPC_Value($sessionId, 'string'));
$oMessage = new XML_RPC_Message('ox.logoff', $aParams);
$oResponse = $oClient->send($oMessage);

register_error(elgg_echo("Banner and its associated statistics have been deleted"));

forward(REFERER);
?>