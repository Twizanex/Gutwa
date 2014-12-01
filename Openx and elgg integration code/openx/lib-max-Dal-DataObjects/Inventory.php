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
$Id: Zones.php 81772 2012-09-11 00:07:29Z chris.nutting $
*/

/**
 * Table Definition for zones
 */
require_once 'DB_DataObjectCommon.php';

class DataObjects_Inventory extends DB_DataObjectCommon
{
   
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'inventory';                           // table name






    

public $detail;                          // MEDIUMINT(9) => openads_mediumint => 129 
    public $clicks;                     // MEDIUMINT(9) => openads_mediumint => 1 
    public $conversion;                        // VARCHAR(245) => openads_varchar => 130 
    public $impressions;                     // VARCHAR(255) => openads_varchar => 130 
    public $cost;                        // SMALLINT(6) => openads_smallint => 129 
    public $name;                        // SMALLINT(6) => openads_smallint => 129 
    //public $category;                        // TEXT() => openads_text => 162 
    public $inventory_id;                           // SMALLINT(6) => openads_smallint => 129 
    

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Inventory',$k,$v); }

   
    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    /**
     * ON DELETE CASCADE is handled by parent class but we have
     * to also make sure here that we are handling here:
     * - zone chaining
     * - zone appending
     *
     * @param boolean $useWhere
     * @param boolean $cascadeDelete
     * @return boolean
     * @see DB_DataObjectCommon::delete()
     */
    

    function update()
    {
        return parent::update();
    }

    function insert()
    {
        return parent::insert();
    }

    

}

?>
