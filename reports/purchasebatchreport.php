<h2>Lots de Stock</h2>
<form method="post" action="reportwindow.php" target="_blank">
<table>
<?php
echo '<tr><td>
<select name="datetype"><option value=0>Arrivage</option><option value=1>DLV</option></select>
entre:<td>';$datename = 'startdate'; require('inc/datepicker.php');
echo '<tr><td><td>';$datename = 'stopdate'; require('inc/datepicker.php');
?>
<tr><td><?php require('inc/selectproduct.php');?></td></tr>
<?php
$dp_itemname='productdepartment';$dp_description = d_trad('department'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
$dp_description = d_trad('family'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamilygroup.php');
$dp_description = d_trad('subfamily'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamily.php');
?>
<tr><td><?php $dp_description = d_trad('supplier'); require('inc/selectclient.php');?> &nbsp;<?php echo d_trad('exclude');?><input type="checkbox" name="excludesupplier" value="1"></td></tr>
<tr><td><?php echo d_trad('temperature:');
$dp_itemname = 'temperature'; $dp_allowall = 1;
require('inc/selectitem.php');
?>
<tr><td>&nbsp;
<tr><td>Champs:<td><input type=checkbox name="show_arrivaldate" value=1 checked>Arrivage
<tr><td><td><input type=checkbox name="show_amount" value=1 checked>Taille
<tr><td><td><input type=checkbox name="show_useby" value=1 checked>DLV
<tr><td><td><input type=checkbox name="show_prev" value=1 checked>Prix Revient
<tr><td><td><input type=checkbox name="show_value" value=1 checked>Montant
<tr><td colspan="2" align="center"><input type=hidden name="report" value="purchasebatchreport"><input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
</table>
</form>
				