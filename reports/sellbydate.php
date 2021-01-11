<h2><?php echo d_trad('sellbydate:');?></h2>
<form method="post" action="reportwindow.php" target="_blank">
<table>
<tr>
  <td><?php echo d_trad('numberofdays:');?></td>
  <td><input type="text" STYLE="text-align:right" name="days" value=180 size=10></td>
</tr>
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
?></td></tr>
<tr><td align=right><input type="checkbox" name="hidenodate" value="1" checked></td><td>Masquer les lots sans DLV</td></tr>
<tr><td colspan="2" align="center"><input type=hidden name="report" value="sellbydatereport"><input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
</table>
</form>
