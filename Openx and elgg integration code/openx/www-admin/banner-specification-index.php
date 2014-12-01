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
$Id: advertiser-index.php 81772 2012-09-11 00:07:29Z chris.nutting $
*/

// Require the initialisation file
require_once '../../init.php';

// Required files
require_once MAX_PATH . '/www/admin/lib-maintenance-priority.inc.php';
require_once MAX_PATH . '/lib/OA/Dal.php';
require_once MAX_PATH . '/lib/OA/Dll.php';
require_once MAX_PATH . '/lib/OX/Util/Utils.php';
require_once MAX_PATH . '/www/admin/config.php';
require_once MAX_PATH . '/www/admin/lib-statistics.inc.php';
require_once MAX_PATH . '/lib/OX/Admin/UI/ViewHooks.php';



// Register input variables
phpAds_registerGlobal('hideinactive', 'listorder', 'orderdirection');

// Security check
OA_Permission::enforceAccount(OA_ACCOUNT_MANAGER);


/*-------------------------------------------------------*/
/* HTML framework                                        */
/*-------------------------------------------------------*/

phpAds_PageHeader('banner-properties');



/*-------------------------------------------------------*/
/* Main code                                             */
/*-------------------------------------------------------*/

require_once MAX_PATH . '/lib/OA/Admin/Template.php';

$oTpl = new OA_Admin_Template('specification-index.html');

// Get clients & campaigns and build the tree
// XXX: Now that the two are next to each other, some silliness
//      is quite visible -- retrieving all items /then/ retrieving a count.
// TODO: This looks like a perfect candidate for object "polymorphism"
$doBanner_spec = OA_Dal::factoryDO('banner_spec');
$doBanner_spec->find();
$aCount = array(
    'banners'        => 0,
    'banners_hidden' => 0,
);

while ($doBanner_spec->fetch() && $row = $doBanner_spec->toArray()) {

 $banners_spec[$row['spec_id']] = $row;

 $aCount['banners']++;
}


$oTpl->assign('banners_spec', $banners_spec);
$oTpl->assign('aCount', $aCount);




$oTpl->display();
phpAds_PageFooter();


function buildHeaderModel()
{
    $builder = new OA_Admin_UI_Model_InventoryPageHeaderModelBuilder();
    return $builder->buildEntityHeader(array(), 'banner-properties', 'list');
}

?>
