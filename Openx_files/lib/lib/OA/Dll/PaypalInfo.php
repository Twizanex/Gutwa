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
$Id:$
*/

/**
 * @package    OpenXDll
 * @author     Ivan Klishch <iklishch@lohika.com>
 *
 * This files describes the ZoneInfo class.
 *
 */

// Include base info class.
require_once MAX_PATH . '/lib/OA/Info.php';

/**
 *  The ZoneInfo class extends the base Info class and contains information about the zone.
 *
 */

class OA_Dll_PaypalInfo extends OA_Info
{


     /**
     * The zoneId variable is the unique ID for the zone.
     *
     * @var string $name
     */
   



    /**
     * The publisherID is the ID of the publisher associated with the zone.
     *
     * @var text $detail
     */
   

    /**
     * The zoneName is the name of the zone.
     *
     * @var float $cost
     */
   

    /**
     * The type variable type of zone, one of the following: banner, interstitial, popup, text, email.
     *
     * @var integer $inventory_id
     */
    var $paypalid;
    var $notifyid;
    var $id;




   
  
  
    function setDefaultForAdd() {
        if (is_null($this->id)) {
            $this->id = 0;
        }

        if (is_null($this->paypalid)) {
            $this->paypalid = 0;
        }

        if (is_null($this->notifyid)) {
            $this->notifyid = 0;
        }
        
    }

    /**
     * This method returns an array of fields with their corresponding types.
     *
     * @access public
     *
     * @return array
     */
    function getFieldsTypes()
    {
        return array(
'id' =>'integer',
'paypalid'=>'string',
'notifyid'=>'string',

			
	               );
    }
}

?>
