<?php


/*
 * The RPC.php gutwa provides: Web Services and PHP implementation of the XML-RPC protocol
 * This is a PEAR-ified version of Useful inc's XML-RPC for PHP.d
 * It has support for HTTP transport, proxies and authentication.
 * PHP versions 4 and 5
 */


        global $plugin;
	$plugin = elgg_get_plugin_from_id('gutwa');



$xmlRpcHost = $plugin->red5host; // This is the Hostname or domain where Openx is installed
$webXmlRpcDir = $plugin->red5port; //THis is the opnex API v2 xmlrpc gutwa location 
$serviceUrl = $webXmlRpcDir;
 $username = $plugin->admin_user;       //
$password = $plugin->admin_password;

$debug = true;





//$oClient->setdebug($debug);


function returnXmlRpcResponseData($oResponse)
{
    if (!$oResponse->faultCode()) {
        $oVal = $oResponse->value();
        $data = XML_RPC_decode($oVal);
        return $data;
    } else {
        die('Fault Code: ' . $oResponse->faultCode() . "\n" .
         'Fault Reason: ' . $oResponse->faultString() . "\n");
    }
}