<?php

/*
+---------------------------------------------------------------------------+
| OpenX v2.8                                             |
| ==========                            |
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
$Id: advertiser-edit.php 34688 2009-04-01 16:18:28Z andrew.hill $
*/

// Require the initialisation file
require_once '../../init.php';

// Required files
require_once MAX_PATH . '/lib/OA/Dal.php';
require_once MAX_PATH . '/lib/max/Admin/Languages.php';
require_once MAX_PATH . '/lib/OA/Admin/Menu.php';
require_once MAX_PATH . '/www/admin/config.php';
require_once MAX_PATH . '/www/admin/lib-statistics.inc.php';
require_once MAX_PATH . '/lib/max/other/html.php';
require_once MAX_PATH .'/lib/OA/Admin/UI/component/Form.php';
require_once MAX_PATH . '/lib/OA/Admin/Template.php';
require_once MAX_PATH . '/lib/OA/Admin/UI/model/InventoryPageHeaderModelBuilder.php';



// Register input variables
phpAds_registerGlobalUnslashed(
     'errormessage'
    ,'clientname'
    ,'contact'
    ,'comments'
    ,'email'
    ,'reportlastdate'
    ,'advertiser_limitation'
    ,'reportprevious'
    ,'reportdeactivate'
    ,'report'
    ,'reportinterval'
    ,'submit'
);


// Security check
OA_Permission::enforceAccount(OA_ACCOUNT_MANAGER, OA_ACCOUNT_ADVERTISER);
OA_Permission::enforceAccessToObject('clients', $clientid, true);
if(OA_Permission::isAccount(OA_ACCOUNT_ADVERTISER) ){
$query1=mysql_num_rows(mysql_query("select * from oxm_offer where clientid=".$clientid));
if($query1>0)
{
$res=mysql_fetch_array(mysql_query("select * from oxm_offer where clientid=".$clientid));

if($res['status']==0)
{
OX_Admin_Redirect::redirect("adv_offer.php?clientid=".$clientid);
}
}

}

phpAds_PageHeader('add_inventory', $oHeaderModel);

	$con = mysql_connect($GLOBALS['_MAX']['CONF']['database']['host'],$GLOBALS['_MAX']['CONF']['database']['username'],$GLOBALS['_MAX']['CONF']['database']['password']);
	mysql_select_db($GLOBALS['_MAX']['CONF']['database']['name'], $con)or die("culnot select:".mysql_error());
	$table_prefix = $GLOBALS['_MAX']['CONF']['table']['prefix'];

/*-------------------------------------------------------*/
/* Main code                                             */
/*-------------------------------------------------------*/
$selmonth = date('n',mktime(0,0,0,date('m'),0,date('Y')));
$selyear = date('Y',mktime(0,0,0,date('m'),0,date('Y')));
if(isset($_POST['selmonth']))
{
	$selmonth = $_POST['selmonth'];
}
if(isset($_POST['selyear']))
{
	$selyear = $_POST['selyear'];
}
if(isset($_GET['bill_period']))
{
	$billperiodarr = explode('-',$_GET['bill_period']);
	$selyear = $billperiodarr[0];
	$selmonth = $billperiodarr[1];
}
$billing_period = date('Y-m',mktime(0,0,0,$selmonth,1,$selyear));
$billing_period_display = date('F Y',mktime(0,0,0,$selmonth,1,$selyear));
?>
<br />

<script type="text/javascript">
	function pricing(d)
	{
		var a = document.getElementById('campaignid-'+d).value;
		var b = document.getElementById('pricing_model-'+d).value;

if(a!='')
{
var c=a.split('_');
}
var model;
	if(b==1)
	 model = 'CPM';
	if(b==2)
	 model = 'CPC';
	if(b==3)
	 model = 'CPA';
			if(c[1] == b){
			return true;
			}else{
			alert("This product is benefitable for "+ model + " pricing model only.");
			return false;
			}
	}
</script>
<table border='0' width='100%' cellpadding='0' cellspacing='0'>
	
	<?php
		if(isset($_GET['ordermsg'])&&$_GET['ordermsg']=='success')
		{
		?>
		<tr>
			<td height='25' align="left" style="color:RED;">Your order has been successfully executed.</td>
		</tr>
		<?
		}

		if(isset($_GET['ordermsg'])&&$_GET['ordermsg']=='cancel')
		{
		?>
		<tr>
			<td height='25' align="left" style="color:RED;">You canceled your last order.</td>
		</tr>
		<?
		}
		?>
	<tr>
		<td height='25' align="left"><b>Buy Inventory: </b></td>
	</tr>
	<tr height='1'>
		<td bgcolor='#888888'><img src='images/break.gif' height='1' width='100%'></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table width="100%">
				<?php
					$getinvres = mysql_query("select * from ox_inventory") or die(mysql_error());
					if(mysql_num_rows($getinvres)>0)
					{

			$i = 1 ;
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
													</tr>
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

								<?php 
						$pricing_model = '' ;
					$pricing_model = ($getinvrow['impressions'] > 0) ? '1' : $pricing_model;
					$pricing_model = ($getinvrow['clicks'] > 0) ? '2' : $pricing_model;
					$pricing_model = ($getinvrow['conversion'] > 0) ? '3' : $pricing_model;


								?> 
													<tr>
														<td style="font-size:12px;">
					<input type="hidden" name="pricing_model-<?php echo $i; ?>" id="pricing_model-<?php echo $i; ?>" value="<?=$pricing_model?>" />
														</td>
													</tr>

												</table>
											</td>
											<td valign="top">
												<table width="100%">
													<!--<tr>
														<td align="left" style="font-size:12px;"><b>Validity:</b> <?=$getinvrow['validaty']?> days 
														</td>
													</tr>-->
													<tr>
														<input type="hidden" name="client_id" id="client_id" value="<?=$clientid?>"/>
														<input type="hidden" name="inventory_id" id="inventory_id" value="<?=$getinvrow['inventory_id']?>"/>
														<input type="hidden" name="cost" id="cost" value="<?=$getinvrow['cost']?>"/>
														<td align="left" style="font-size:12px;"><b>Cost:</b> <?=$getinvrow['cost']?>														</td>
													</tr>
								<!--					<tr>
														<td align="left" style="font-size:12px;"><B>Quantity: </B><input type="text" value="1" size="3" style="text-align:right" name="quant_<?=$getinvrow['inventory_id']?>" id="quant_<?=$getinvrow['inventory_id']?>" onkeyup="calculatecost(<?=$getinvrow['inventory_id']?>)" onkeydown="calculatecost(<?=$getinvrow['inventory_id']?>)"  />													  </td>
													</tr>
													-->
													<tr>
														<td align="left" style="font-size:12px;"><B>Quantity: </B>
					<select name="quantity" id="quantity">											
					<option value="1" selected>1</option>
					<option value="2">2</option>
				     <option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
				     <option value="10">10</option>
					<option value="15">15</option>
					<option value="20">20</option>
				       <option value="30">30</option>
					<option value="50">50</option>
						<option value="100">100</option>
</select>																																
														  </td>
													</tr>
													<!--<tr>
													  <td align="left" style="font-size:12px;"><B>Campaign ID: </B>
												      	<input type="text" value="1" size="3" style="text-align:right" name="campaignid_<?=$getinvrow['inventory_id']?>" id="campaignid_<?=$getinvrow['inventory_id']?>" />
													  </td>
													</tr>-->
													<tr>
													  <td align="left" style="font-size:12px;"><B>Campaign Name: </B>
				<select style="text-align:right" name="campaignid" id="campaignid-<?php echo $i; ?>" />
				<?php

			$getcampres = mysql_query("Select * from ".$table_prefix."campaigns WHERE clientid=".$clientid." " ) or die("No banners");
		      if(mysql_num_rows($getcampres)>0)
		      {

			while($getcamprow = mysql_fetch_array($getcampres)) { 

				$_price = $getcamprow['campaignid'].'_'.$getcamprow['revenue_type'];
?>

				<option value="<?=$_price?>"><?=$getcamprow['campaignname']?></option>
			<?php
			}
																	
		    } ?>
			   </select>
													  </td>

													</tr>


													<tr>
														<td align="left" style="font-size:12px;">
															<div id="camp_pricing<?=$getinvrow['inventory_id']?>">$ <?=$getinvrow['cost']?></div>													  </td>
													</tr>



											  </table>
											</td>
											<td>
												<table width="100%">
													<tr>
							<td align="center"><input type="submit" value="Buy" size="3" name="Buy" id = "<?php echo $i; ?>" onclick ="return pricing(this.id);" />
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
							$i++;
						}
					}
					
				?>
				<tr>
					<td>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	
</table>
<br /><br />

<?

/*-------------------------------------------------------*/
/* HTML framework                                        */
/*-------------------------------------------------------*/

phpAds_PageFooter();

?>


