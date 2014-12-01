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
$Id: ZoneXmlRpcService.php 81772 2012-09-11 00:07:29Z chris.nutting $
*/

/**
 * @package    OpenX
 * @author     Ivan Klishch <iklishch@lohika.com>
 *
 * The zone XML-RPC service enables XML-RPC communication with the zone object.
 *
 */

// Require the initialisation file.
require_once '../../../../init.php';

// Require the XML-RPC classes.
require_once MAX_PATH . '/lib/pear/XML/RPC/Server.php';

// Require the base class, BaseZoneService.
require_once MAX_PATH . '/www/api/v2/common/BaseInventoryService.php';

// Require the XML-RPC utilities.
require_once MAX_PATH . '/www/api/v2/common/XmlRpcUtils.php';

// Require the ZoneInfo helper class.
require_once MAX_PATH . '/lib/OA/Dll/Inventory.php';

/**
 * The ZoneXmlRpcService class the BaseZoneService class.
 *
 */
class InventoryXmlRpcService extends BaseInventoryService
{
    /**
     * The ZoneXmlRpcService constructor calls the base service constructor
     * to initialise the service.
     *
     */
    function InventoryXmlRpcService()
    {
        $this->BaseInventoryService();
    }

    /**
     * The addZone method adds details for a new zone to the zone
     * object and returns either the zone ID or an error message.
     *
     * @access public
     *
     * @param XML_RPC_Message &$oParams
     *
     * @return generated result (data or error) */
    

	 function getInventoryList(&$oParams) {
        $oResponseWithError = null;
        if (!XmlRpcUtils::getScalarValues(
                array(&$sessionId),
                array(true), $oParams, $oResponseWithError)) {
           return $oResponseWithError;
        }

        $aInventoryList = null;
        if ($this->_oInventoryServiceImp->getInventoryList($sessionId, $aInventoryList)) {

            return XmlRpcUtils::getArrayOfEntityResponse($aInventoryList);
        } else {

            return XmlRpcUtils::generateError($this->_oInventoryServiceImp->getLastError());
        }
    }

}

?>
