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


$aErrormessage = array();



$oHeaderModel = new OA_Admin_UI_Model_PageHeaderModel($title);
phpAds_PageHeader('inventory_products', $oHeaderModel);




$table_prefix = $GLOBALS['_MAX']['CONF']['table']['prefix'];

if(isset($_GET['inventoryid']))
{
	mysql_query("delete from ox_inventory WHERE inventory_id=".$_GET['inventoryid']) or die("Inventory is already deleted.");
}
if(isset($_POST['subadd']))
{

if(!empty($_POST['cost']) && !empty($_POST['numbers']) && !empty($_POST['name'])){
	$pricing = $_POST['pricing_model'];
	mysql_query("insert into ox_inventory(name,detail,$pricing,cost) values('".$_POST['name']."','".$_POST['detail']."','".$_POST['numbers']."','".$_POST['cost']."')") or die(mysql_error());

 	$translation = new OX_Translation ();
        $translated_message = $translation->translate("Your Product has been Added successfully",array(htmlspecialchars($title)));
        OA_Admin_UI::queueMessage($translated_message, 'local', 'confirm', 0);
			
        OX_Admin_Redirect::redirect("oxm_inventory_products.php");

}
else{
echo '<font size="2" color="#FF0000"><b>Fill all the basic Information.<b/></font></br/></br>';
}

}

if(isset($_GET['mode'])&&$_GET['mode']=='add')
{
?>
<table border='0' width='100%' cellpadding='0' cellspacing='0'>
<tr><td height='25' colspan='3'><b>Add Products</b></td></tr>
<tr height='1'><td colspan='3' bgcolor='#888888'><img src='images/break.gif' height='1' width='100%'></td></tr>
<tr><td height='10' colspan='3'>&nbsp;</td></tr>
<tr><td height='10' colspan='3'>
<table width="100%">
			
							<tr>
								<td>
									<form action="" method="post" name="prodform">
									<table width="100%" bgcolor="#CCCCCC" cellpadding="5">
										<tr>
											<td width="60%">
												<table width="100%">
													<tr>
														<td style="font-size:12px;"><b>Name:</b></td>
														<td><input name="name" type="text" size="52" />
														</td>
														
													</tr>
													<tr>
														<td valign="top" style="font-size:12px;"><b>Description:</b>														</td>
														<td>
														<textarea name="detail" cols="50" rows="3"></textarea>
														</td>
													</tr>
												
											  </table>
											</td>
											<td valign="top">
												<table width="100%">
											
													<tr>
														<input type="hidden" name="client_id" id="client_id" value="<?=$clientid?>"/>
														<input type="hidden" name="inventory_id" id="inventory_id" value="<?=$getinvrow['inventory_id']?>"/>
														<input type="hidden" name="cost" id="cost" value="<?=$getinvrow['cost']?>"/>
														<input type="hidden" name="cost_<?=$getinvrow['inventory_id']?>" id="cost_<?=$getinvrow['inventory_id']?>" value="<?=$getinvrow['cost']?>"/>
														<td align="left" style="font-size:12px;" width="100"><b>Cost:</b>
														</td>
														<td>
														<input name="cost" type="text" />
														</td>
													</tr>
														<tr>
														<td style="font-size:12px;"><b>Pricing model:</b>
														</td>


														<td>
									<select name="pricing_model" id="pricing_model" class="small"  >
									  	<option value="impressions" >Impressions</option>
										<option value="clicks">Clicks</option>
										<option value="conversion">Conversions</option>
									</select>
														</td>
													</tr>
													<tr>
														<td style="font-size:12px;"><b>Numbers:</b></td>
														<td><input name="numbers" type="text" />
														</td>
													</tr>
													<tr>
														<td style="font-size:12px;">&nbsp;</td>
														<td><input name="subadd" type="submit" value="Add"/>&nbsp;&nbsp;&nbsp;<input name="" type="submit" value="Cancel"/>
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


?>

<table border='0' width='100%' cellpadding='0' cellspacing='0'>
<tr><td height='25' colspan='3'><b>View Products</b>&nbsp;&nbsp;&nbsp;<a href="oxm_inventory_products.php?mode=add">Add Product</a></td></tr>
<tr height='1'><td colspan='3' bgcolor='#888888'><img src='images/break.gif' height='1' width='100%'></td></tr>
<tr><td height='10' colspan='3'>&nbsp;</td></tr>
<tr><td height='10' colspan='3'>
<table width="100%">
				<?php


					$getinvres = mysql_query("select * from ox_inventory") or die(mysql_error());
					if(mysql_num_rows($getinvres)>0)
					{
						while($getinvrow = mysql_fetch_array($getinvres))
						{


						?>
							<tr>
								<td>
									<form action="oxm_paypal.php" method="post" name="prodform">
									<table width="100%" bgcolor="#CCCCCC" cellpadding="5">
										<tr>
											<td width="60%">
												<table width="100%">
													<tr>
														<td style="font-size:12px;"><b><?=$getinvrow['name']?> </b>
														</td>
													</tr>
													<tr>
														<td style="font-size:12px;"><b>Description:</b>
														</td>
													</tr>
													<tr>
														<td style="font-size:12px;"><p><?=$getinvrow['detail']?></p>
														</td>
													
												</table>
											</td>
											<td valign="top">
												<table width="100%">
													<tr>
														<input type="hidden" name="client_id" id="client_id" value="<?=$clientid?>"/>
														<input type="hidden" name="inventory_id" id="inventory_id" value="<?=$getinvrow['inventory_id']?>"/>
														<input type="hidden" name="cost" id="cost" value="<?=$getinvrow['cost']?>"/>
														<input type="hidden" name="cost_<?=$getinvrow['inventory_id']?>" id="cost_<?=$getinvrow['inventory_id']?>" value="<?=$getinvrow['cost']?>"/>
														<td align="left" style="font-size:12px;"><b>Cost:</b> <?=$getinvrow['cost']?> 
														</td>
													</tr>
													<tr>
														<td style="font-size:12px;"><b>Impressions:</b> <?=$getinvrow['impressions']?> 
														</td>
													</tr>
													<tr>
														<td style="font-size:12px;"><b>Clicks:</b> <?=$getinvrow['clicks']?> 
														</td>
													</tr>
													<tr>
														<td style="font-size:12px;"><b>Conversions:</b> <?=$getinvrow['conversion']?> 
														</td>
													</tr>
											  </table>
											</td>
										  <td valign="top">
												<table width="100%">
													<tr>
														<td style="font-size:12px;"><a href="oxm_inventory_products.php?inventoryid=<?=$getinvrow['inventory_id']?>" onclick="return confirm('Are you sure you want to delele this Inventory?')"><input type="button" value="Delete" /></a>
														</td>
													</tr>
													<tr>
														<td style="font-size:12px;"><a href="oxm_inventoryedit.php?inventoryid=<?=$getinvrow['inventory_id']?>" ><input type="button" value="Edit" /></a>
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
							
						<?
						}
					}
					
				?>
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

phpAds_PageFooter();

?>
