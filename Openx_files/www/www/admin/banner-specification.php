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
$Id: advertiser-edit.php 81439 2012-05-07 23:59:14Z chris.nutting $
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
require_once MAX_PATH . '/lib/OA/Dal/Delivery/mysql.php';
require_once MAX_PATH .'/lib/OA/Admin/UI/component/Form.php';
require_once MAX_PATH . '/lib/OA/Admin/Template.php';
require_once MAX_PATH . '/lib/OA/Admin/UI/model/InventoryPageHeaderModelBuilder.php';



// Security check
OA_Permission::enforceAccount(OA_ACCOUNT_MANAGER);
OA_Permission::enforceAccessToObject('spec_id', $spec_id, true);



/*-------------------------------------------------------*/
/* Initialise data                                    */
/*-------------------------------------------------------*/
if(!empty($_POST['spec_id']) ) {
$doBanner_spec = OA_Dal::factoryDO('banner_spec');
$aBanner_spec['spec_name'] = $_POST['spec_name'];
$aBanner_spec['width'] = $_POST['width'];
$aBanner_spec['height'] = $_POST['height'];
$aBanner_spec['spec_id'] = $_POST['spec_id'];

       OA_Dal_Delivery_query("update ox_banner_spec set spec_name = '".$aBanner_spec['spec_name']."',width=".$aBanner_spec['width'].",height=".$aBanner_spec['height']." Where spec_id = ".$aBanner_spec['spec_id']." ") or die(mysql_error());
		
		 // Queue confirmation message
        $translation = new OX_Translation ();
        $translated_message = $translation->translate ( 'Banner specification has been updated successfully', array(
            MAX::constructURL(MAX_URL_ADMIN, 'banner-specification.php?spec_id=' .  $aBanner_spec['spec_id']),
            htmlspecialchars($aBanner_spec['spec_name'])
            //MAX::constructURL(MAX_URL_ADMIN, 'campaign-edit.php?clientid=' .  $aAdvertiser['clientid']),
        ));
        OA_Admin_UI::queueMessage($translated_message, 'local', 'confirm', 0);

        // Go to next page
        OX_Admin_Redirect::redirect("banner-specification-index.php");
		
}

if ($_GET['spec_id'] != "") {
$spec_id = $_GET['spec_id'];


    if (!isset($aBanner_spec)) {
        $doBanner_spec = OA_Dal::factoryDO('banner_spec');
        if ($doBanner_spec->get("spec_id",$spec_id)) {
            $aBanner_spec = $doBanner_spec->toArray();
        }
    }
}


/*-------------------------------------------------------*/
/* MAIN REQUEST PROCESSING                               */
/*-------------------------------------------------------*/
//build advertiser form

$BannerForm = buildBannerForm($aBanner_spec);

if ($BannerForm->validate()) {
    //process submitted values
    processForm($aBanner_spec, $BannerForm,$_GET['spec_id']);
}
else { //either validation failed or form was not submitted, display the form
    displayPage($aBanner_spec, $BannerForm);
}

/*-------------------------------------------------------*/
/* Build form                                            */
/*-------------------------------------------------------*/
function buildBannerForm($aBanner_spec)
{

    $form = new OA_Admin_UI_Component_Form("bannerform", "POST", $_SERVER['SCRIPT_NAME']);
    $form->forceClientValidation(true);


    //$form->addElement('hidden', 'clientid', $aAdvertiser['clientid']);
    $form->addElement('header', 'header_basic', 'Banner Specification');

     $form->addElement('hidden', 'spec_id', $aBanner_spec['spec_id']);
    $form->addElement('text', 'spec_name', 'Specification Name');
    $form->addElement('text', 'width', 'Width');
	$form->addElement('text', 'height', 'Height');

    //we want submit to be the last element in its own separate section
    $form->addElement('controls', 'form-controls');
    $form->addElement('submit', 'submit', $GLOBALS['strSaveChanges']);

    //Form validation rules
    $translation = new OX_Translation();
    if (OA_Permission::isAccount(OA_ACCOUNT_MANAGER)) {
        $nameRequiredMsg = $translation->translate($GLOBALS['strXRequiredField'], array($GLOBALS['strName']));
        $form->addRule('spec_name', $nameRequiredMsg, 'required');
    }


    $form->addRule('width', $GLOBALS['strNumericField'], 'numeric');
    $form->addRule('height', $GLOBALS['strNumericField'], 'numeric');


    //set form  values
    $form->setDefaults($aBanner_spec);
    return $form;
}


/*-------------------------------------------------------*/
/* Process submitted form                                */
/*-------------------------------------------------------*/
function processForm($aBanner_spec, $form,$spec_id)
{


    $aFields = $form->exportValues();

    // Name
    if (OA_Permission::isAccount(OA_ACCOUNT_MANAGER) ) {
        $aBanner_spec['spec_name'] = $aFields['spec_name'];
    }
    // Default fields
    $aBanner_spec['width']  = $aFields['width'];
    $aBanner_spec['height']    = $aFields['height'];

   if (empty($aBanner_spec['spec_id'])) {
      

        $doBanner_spec = OA_Dal::factoryDO('banner_spec');
        $doBanner_spec->setFrom($aBanner_spec);
        

        // Insert
        $aBanner_spec['spec_id'] = $doBanner_spec->insert();

        // Queue confirmation message
        $translation = new OX_Translation ();
        $translated_message = $translation->translate ( 'Banner specification has been added successfully', array(
            MAX::constructURL(MAX_URL_ADMIN, 'banner-specification.php?clientid=' .  $aBanner_spec['spec_id']),
            htmlspecialchars($aBanner_spec['spec_name'])
            //MAX::constructURL(MAX_URL_ADMIN, 'campaign-edit.php?clientid=' .  $aAdvertiser['clientid']),
        ));
        OA_Admin_UI::queueMessage($translated_message, 'local', 'confirm', 0);

        // Go to next page
        OX_Admin_Redirect::redirect("banner-specification-index.php");
    }

   
}

/*-------------------------------------------------------*/
/* Display page                                          */
/*-------------------------------------------------------*/
function displayPage($aBanner_spec, $form)
{
    //header and breadcrumbs
    //$oHeaderModel = buildAdvertiserHeaderModel($aAdvertiser);
    if ($aBanner_spec['spec_id'] != "") {


            phpAds_PageHeader('banner-properties');
    
    }
    else { //
        phpAds_PageHeader('banner-spec_new');
    }

    //get template and display form
    $oTpl = new OA_Admin_Template('bannerspec-edit.html');

    $oTpl->assign('spec_id',  $aBanner_spec['spec_id']);
    $oTpl->assign('form', $form->serialize());
    $oTpl->display();

    //footer
    phpAds_PageFooter();
}

?>
