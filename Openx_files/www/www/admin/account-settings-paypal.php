<?php

/*
+---------------------------------------------------------------------------+
| OpenX v2.8                                                                |
| ==========                                                                |
|                                                                           |
| Copyright (c) 2003-2009 OpenX Limited                                     |
| For contact details, see: http://www.openx.org/                           |
|                                                                           |
| This program is free software; you can redistribute it and/or modify      |
| it under the terms of the GNU General Public License as published by      |
| the Free Software Foundation; either version 2 of the License, or         |
| (at your option) any later version.                                       |
|                                                                           |
| This program is distributed in the hope that it will be useful,           |
| but WITHOUT ANY WARRANTY; without even the implied warranty of            |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
| GNU General Public License for more details.                              |
|                                                                           |
| You should have received a copy of the GNU General Public License         |
| along with this program; if not, write to the Free Software               |
| Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
+---------------------------------------------------------------------------+
$Id: account-settings-user-interface.php 34688 2009-04-01 16:18:28Z andrew.hill $
*/

// Require the initialisation file
require_once '../../init.php';

// Required files
require_once MAX_PATH . '/lib/OA/Admin/Option.php';
require_once MAX_PATH . '/lib/OA/Admin/Settings.php';

require_once MAX_PATH . '/lib/max/Plugin/Translation.php';
require_once MAX_PATH . '/www/admin/config.php';


// Security check
OA_Permission::enforceAccount(OA_ACCOUNT_ADMIN);

$con = mysql_connect($GLOBALS['_MAX']['CONF']['database']['host'],$GLOBALS['_MAX']['CONF']['database']['username'],$GLOBALS['_MAX']['CONF']['database']['password']);
mysql_select_db($GLOBALS['_MAX']['CONF']['database']['name'], $con)or die("culnot select:".mysql_error());
$table_prefix = $GLOBALS['_MAX']['CONF']['table']['prefix'];


// Create a new option object for displaying the setting's page's HTML form
$oOptions = new OA_Admin_Option('settings');
//$oOptions = new OA_Admin_Option('user');
$prefSection = "paypal";



// Prepare an array for storing error messages
//$aErrormessage = array();



if (isset($_POST['submitok']) && $_POST['submitok'] == 'true') {

$select1=mysql_query("select *from ox_admin_paypal") or die(mysql_error());

if(mysql_num_rows($select1) >0 ){

	$res = mysql_query("update ox_admin_paypal set paypalid ='".$_POST['paypalid']."' , notifyid ='".$_POST['notifyid']."' ")or die("error in update");
	
}else{

$res = mysql_query("INSERT into ox_admin_paypal(paypalid,notifyid,adv_to_pub_percentage) VALUES('".$_POST['paypalid']."','".$_POST['notifyid']."')  ")or die("error in insert");  

}

}
// Set the correct section of the settings pages and display the drop-down menu
$setPref = $oOptions->getSettingsPreferences($prefSection);
$title = $setPref[$prefSection]['name'];


// Display the settings page's header and sections
$oHeaderModel = new OA_Admin_UI_Model_PageHeaderModel($title);
phpAds_PageHeader('account-settings-index', $oHeaderModel);


$select=mysql_query("select * from ox_admin_paypal") or die(mysql_error());
$row=mysql_fetch_array($select);

$paypalid=$row['paypalid'];

$notifyid=$row['notifyid'];



$aSettings = array(
    array (
        'text'    => 'Admin Paypal Settings',
        'items'   => array (
           			 array (
               			 	'type'    => 'text',
					'name'    => 'paypalid',
					'text'    => 'Paypal ID :',
					'size'    => 30,
					'value'	  => $paypalid	
            				),
				    array (
			       			 'type'    => 'break'
			  		  ),
		    		   array (
					'type'    => 'text',
					'name'    => 'notifyid',
					'text'    => 'Notify Email-ID:',
					'size'    => 30,
							'value'	  => $notifyid	
        			        )
      			  )
   	 )

);

//$oOptions->show($aSettings, $aErrormessage);

$oOptions->show($aSettings, null);

phpAds_PageFooter();

?>
