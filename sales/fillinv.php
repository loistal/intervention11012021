<h2>Recommander</h2>
<form method="post" action="sales.php">
<table>
<tr><td><?php
require('inc/selectclient.php');
?>
<tr><td>Depuis:<td><?php
$datename = 'reorderdate';
$date = date_create($_SESSION['ds_curdate']);
date_sub($date, date_interval_create_from_date_string('90 days'));
$selecteddate = date_format($date, 'Y-m-d');;
require('inc/datepicker.php');
$dp_itemname='productdepartment';$dp_description = d_trad('department'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
$dp_description = d_trad('family'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamilygroup.php');
?>
<tr><td colspan=2><input name="modify" type="submit" value="Recommander"></td></tr>
<input type=hidden name="salesmenu" value="invoicing"><input type=hidden name="reorder" value="1">
</table></form>