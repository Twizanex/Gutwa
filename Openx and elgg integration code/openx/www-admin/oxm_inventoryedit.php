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
$Id: advertiser-campaigns.php 34688 2009-04-01 16:18:28Z andrew.hill $
*/

// Require the initialisation file
require_once '../../init.php';
//require_once MAX_PATH . '/lib/OX/Util/Utils.php';

// Required files
require_once MAX_PATH . '/www/admin/lib-maintenance-priority.inc.php';
require_once MAX_PATH . '/lib/OA/Dal.php';
require_once MAX_PATH . '/lib/OA/Dll.php';
require_once MAX_PATH . '/www/admin/config.php';
require_once MAX_PATH . '/www/admin/lib-statistics.inc.php';
require_once MAX_PATH . '/lib/max/Admin/Languages.php';
require_once MAX_PATH . '/lib/OX/Admin/Redirect.php';

	$con = mysql_connect($GLOBALS['_MAX']['CONF']['database']['host'],$GLOBALS['_MAX']['CONF']['database']['username'],$GLOBALS['_MAX']['CONF']['database']['password']);
	mysql_select_db($GLOBALS['_MAX']['CONF']['database']['name'], $con)or die("culnot select:".mysql_error());
	$table_prefix = $GLOBALS['_MAX']['CONF']['table']['prefix'];

// Security check
OA_Permission::enforceAccount(OA_ACCOUNT_MANAGER);

// Create a new option object for displaying the setting's page's HTML form
//$oOptions = new OA_Admin_Option('settings');
//$prefSection = "inventory-products";
$aErrormessage = array();




// Set the correct section of the preference pages and display the drop-down menu
//$setPref = $oOptions->getSettingsPreferences($prefSection);
//$title = $setPref[$prefSection]['name'];

// Display the settings page's header and sections
$oHeaderModel = new OA_Admin_UI_Model_PageHeaderModel($title);
phpAds_PageHeader('inventory_products');


//$oOptions->show($aSettings, $aErrormessage);



$table_prefix = $GLOBALS['_MAX']['CONF']['table']['prefix'];


if(isset($_POST['submit'])){


if(!empty($_POST['cost']) && !empty($_POST['numbers']) && !empty($_POST['name'])){



		$se= $_POST['pricing_model'];
		
		if($se=='impressions'){
		 $imp= $_POST['numbers'];
		 $clk=0;
		 $cov=0;
		}
		else if($se=='clicks'){
		 $clk= $_POST['numbers'];
		 $imp=0;
		 $cov=0;
		}
		else{
		 $cov= $_POST['numbers'];
		 $clk=0;
		 $imp=0;
		}
		
    

	   
	   
		mysql_query("UPDATE ox_inventory  SET name='".$_POST['name']."' , detail='".$_POST['detail']."', clicks='".$clk."', impressions='".$imp."', conversion='".$cov."', cost='".$_POST['cost']."' WHERE inventory_id=".$_POST['inventory_id']) or die("Inventory is already deleted.");
		
		
		
		   $translation = new OX_Translation ();
        $translated_message = $translation->translate("Your Product has been Edited successfully",array(htmlspecialchars($title)));
        OA_Admin_UI::queueMessage($translated_message, 'local', 'confirm', 0);
			
        OX_Admin_Redirect::redirect("oxm_inventory_products.php");

}else{
echo '<font size="2" color="#FF0000"><b>Fill all the basic Information.<b/></font></br/></br>';
}


}




if(isset($_GET['inventoryid']))
{


$qur=	mysql_query("SELECT * from ox_inventory WHERE inventory_id=".$_GET['inventoryid']) or die("Inventory is already deleted.");

$getinvrow=mysql_fetch_array($qur);

if($getinvrow['impressions']!=0)
{
$num=$getinvrow['impressions'];
$imp="selected=selected";
$clk="";
$cov="";
}
else if($getinvrow['clicks']!=0)
{
$num=$getinvrow['clicks'];
$clk="selected=selected";
$imp="";
$cov="";
}
else{
$num=$getinvrow['conversion'];
$cov="selected=selected";
$clk="";
$imp="";
}



?>
<table border='0' width='100%' cellpadding='0' cellspacing='0'>
<tr><td height='25' colspan='3'><b>Edit Products</b></td></tr>
<tr height='1'><td colspan='3' bgcolor='#888888'><img src='images/break.gif' height='1' width='100%'></td></tr>
<tr><td height='10' colspan='3'>&nbsp;</td></tr>
<tr><td height='10' colspan='3'>
<table width="100%">
			
							<tr>
								<td>
									<form action="" method="post" name="prodforms">
									<table width="100%" bgcolor="#CCCCCC" cellpadding="5">
										<tr>
											<td width="60%">
												<table width="100%">
													<tr>
														<td style="font-size:12px;"><b>Name:</b></td>
														<td><input name="name" type="text" size="52" value="<?php print_r($getinvrow['name']); ?>" />
														</td>
														
													</tr>
													<tr>
														<td valign="top" style="font-size:12px;"><b>Description:</b>														</td>
														<td>
														<textarea name="detail" cols="50" rows="3" ><?php print_r($getinvrow['detail']); ?></textarea>
														</td>
													</tr>
												
											  </table>
											</td>
											<td valign="top">
												<table width="100%">
												
													<tr>
												
														<input type="hidden" name="inventory_id" id="inventory_id" value="<?=$getinvrow['inventory_id']?>"/>
														
														<td align="left" style="font-size:12px;" width="100"><b>Cost:</b>
														</td>
														<td>
														<input name="cost" type="text"  value="<?php print_r($getinvrow['cost']); ?>"/>
														</td>
													</tr>
														<tr>
														<td style="font-size:12px;"><b>Pricing model:</b>
														</td>


														<td>
									<select name="pricing_model" id="pricing_model" class="small"  >
									  	<option value="impressions"  <?php print_r($imp); ?>>Impressions</option>
										<option value="clicks" <?php print_r($clk); ?>>Clicks</option>
										<option value="conversion" <?php print_r($cov); ?> >Conversions</option>
									</select>
														</td>
													</tr>
													<tr>
														<td style="font-size:12px;"><b>Numbers:</b></td>
														<td><input name="numbers" type="text"  value="<?php print_r($num); ?>"/>
														</td>
													</tr>
													<tr>
														<td style="font-size:12px;">&nbsp;</td>
														<td><input name="submit" type="submit" value="Save Changes"/>&nbsp;&nbsp;&nbsp;
														</td>
													</tr>
											  </table>
											</td>
										</tr>
									</table>
									</form>
								  </td>
							</tr>
							<tr>
								<td>&nbsp;
								</td>
							</tr>
				<tr>
					<td>
					</td>
				</tr>
			</table>
</td></tr>

<tr><td height='10' colspan='3'>&nbsp;</td></tr>
<tr height='1'><td colspan='3' bgcolor='#888888'><img src='images/break.gif' height='1' width='100%'></td></tr>
</table>
<?
}


phpAds_PageFooter();

?>
