<?php
require_once '../../init.php';
// Required files
require_once MAX_PATH . '/lib/OA/Admin/Option.php';
require_once MAX_PATH . '/lib/OA/Admin/Settings.php';


require_once MAX_PATH . '/lib/max/Plugin/Translation.php';
require_once MAX_PATH . '/www/admin/config.php';
require_once LIB_PATH . '/Plugin/Component.php';
 $translation = new OX_Translation();
// Security check
OA_Permission::enforceAccount(OA_ACCOUNT_ADMIN);
?>


<style type="text/css">


data-grid table {
    border: 1px solid #CDCDCD;
    border-collapse: collapse;
    table-layout: fixed;
    width: 100%;
}

.table {
    margin: 1em 0 0.5em;
    padding: 0;
    width: 100%;
}

table {
    border-collapse: collapse;
}

body, table, td, select, textarea, input, button {
    font-family: Arial,Helvetica,sans-serif;
    font-size: 11px;
}

table {
    border-collapse: separate;
    border-spacing: 0;
}

img, table, caption, tbody, tfoot, thead, tr, th, td {
    border: 0 none;
    margin: 0;
    outline: 0 none;
    padding: 0;
}


body, table, td, select, textarea, input, button {
    font-family: Arial,Helvetica,sans-serif;
    font-size: 11px;
}

caption, th, td {
    font-weight: normal;
    text-align: left;
}


table {
    border-collapse: collapse;
}

body, table, td, select, textarea, input, button {
    font-family: Arial,Helvetica,sans-serif;
    font-size: 11px;
}

table {
    border-collapse: separate;
    border-spacing: 0;
}

body, table, td, select, textarea, input, button {
    font-family: Arial,Helvetica,sans-serif;
    font-size: 11px;
}

body {
    color: #444444;
}

body {
    color: black;
    line-height: 1;
}
</style>

<?php

$bannerid = $_GET['bannerid'];
if(isset($_POST['denial']) ) {
	$query = "Update ox_banners set denial_message = '".$_POST['denial']."',status =3 where bannerid=".$bannerid;
	$banInfo=mysql_query($query) or die("Error:".mysql_error());
	// $translated_message = $translation->translate('check',
          //  array (
            //    MAX::constructURL ( MAX_URL_ADMIN, 'denial_message.php' )));
            //OA_Admin_UI::queueMessage($translated_message, 'local', 'confirm', 0);
            //$nextPage = "denial_message.php?bannerid=".$bannerid."";
	
	
}

$query_select = "Select denial_message from ox_banners where bannerid=".$bannerid;
	$ban_Info=mysql_query($query_select) or die("Error:".mysql_error());


	if(mysql_num_rows($ban_Info)>0)
	{
		$banInfoRow = mysql_fetch_array($ban_Info);
	?>
	<form action="denial_message.php?bannerid=<?php echo $bannerid;?>" method="post">
	<table width="50%">
		<tr>
			<td>Denail Message:&nbsp;&nbsp;<textarea rows="5" cols="25" name="denial"> <?php echo $banInfoRow['denial_message']; ?></textarea></td>

		</tr>
		<tr>
		<td><input type="submit" name="Submit" value="Submit"></td>
		
		</tr>
	</table>
	</form>
<?
	} // End If
?>
