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
$Id: Banners.php 81772 2012-09-11 00:07:29Z chris.nutting $
*/

/**
 * Table Definition for banner_spec
 */
require_once 'DB_DataObjectCommon.php';


class DataObjects_Banner_Spec extends DB_DataObjectCommon
{
    var $onDeleteCascade = true;
    var $refreshUpdatedFieldIfExists = true;
    
  
    
     
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'banner_spec';                         // table name
    public $spec_id;                        // MEDIUMINT(9) => openads_mediumint => 129
    public $spec_name;                      // MEDIUMINT(9) => openads_mediumint => 129
    public $width;                           // SMALLINT(6) => openads_smallint => 129
    public $height;                          // SMALLINT(6) => openads_smallint => 129
   

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Banner_spec',$k,$v); }

   

}

?>