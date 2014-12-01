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
$Id: ZoneServiceImpl.php 81772 2012-09-11 00:07:29Z chris.nutting $
*/

/**
 * @package    OpenX
 * @author     Ivan Klishch <iklishch@lohika.com>
 *
 */

// Base class BaseLogonService
require_once MAX_PATH . '/www/api/v2/common/BaseServiceImpl.php';

// Zone Dll class
require_once MAX_PATH . '/lib/OA/Dll/Paypal.php';

/**
 * The ZoneServiceImpl class extends the BaseServiceImpl class to enable
 * you to add, modify, delete and search the zone object.
 *
 */
class PaypalServiceImpl extends BaseServiceImpl
{
    /**
     *
     * @var OA_Dll_Zone $_dllZone
     */
    var $_dllInventory;


 function PaypalServiceImpl()
    {
        $this->BaseServiceImpl();
        $this->_dllInventory = new OA_Dll_Paypal();
    }



 function _validateResult($result)
    {
        if ($result) {
            return true;
        } else {
            $this->raiseError($this->_dllInventory->getLastError());
            return false;
        }
    }



  function getAdminPaypal($sessionId, &$aAgencyList)
    {
        if ($this->verifySession($sessionId)) {

            return $this->_validateResult(
                $this->_dllInventory->getAdminPaypal($aAgencyList));
        } else {

            return false;
        }
    }






}


?>
