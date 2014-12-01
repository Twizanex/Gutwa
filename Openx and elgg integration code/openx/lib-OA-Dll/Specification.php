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
$Id: Zone.php 81772 2012-09-11 00:07:29Z chris.nutting $
*/

/**
 * @package    OpenXDll
 * @author     Ivan Klishch <iklishch@lohika.com>
 *
 */

// Require the following classes:
require_once MAX_PATH . '/lib/OA/Dll.php';
require_once MAX_PATH . '/lib/OA/Dll/SpecificationInfo.php';
//require_once MAX_PATH . '/lib/OA/Dal/Statistics/Zone.php';

// Legacy Admin_DA
require_once MAX_PATH . '/lib/max/Admin_DA.php';

/**
 * The OA_Dll_Zone class extends the base OA_Dll class.
 *
 */

class OA_Dll_Specification extends OA_Dll
{
    /**
     * This method sets ZoneInfo from a data array.
     *
     * @access private
     *
     * @param OA_Dll_ZoneInfo &$oZone
     * @param array $zoneData
     *
     * @return boolean
     */


	 function _setSpecificationDataFromArray(&$oAgency, $agencyData)
    {
        $agencyData['spec_id']     = $agencyData['spec_id'];
        $agencyData['width']   = $agencyData['width'];
        $agencyData['height']  = $agencyData['height'];
        $agencyData['spec_name'] = $agencyData['spec_name'];
        

        // Do not return the password from the Dll.
      //  unset($agencyData['password']);

        $oAgency->readDataFromArray($agencyData);
        return  true;
    }

   
	function getSpecificationList(&$aAgencyList)
    {
        if (!$this->checkPermissions(OA_ACCOUNT_ADMIN)) {
            return false;
        }

        $aAgencyList = array();

        $doInventory = OA_Dal::factoryDO('banner_spec');
        $doInventory->find();

        while ($doInventory->fetch()) {
            $agencyData = $doInventory->toArray();

            $oAgency = new OA_Dll_SpecificationInfo;
            $this->_setSpecificationDataFromArray($oAgency, $agencyData);

            $aAgencyList[] = $oAgency;
        }
        return true;
    }

}

?>
