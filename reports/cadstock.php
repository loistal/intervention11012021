<h2><?php echo d_trad('stockcadencebyyear:');?></h2>
<form method="post" action="reportwindow.php" target="_blank">
<table>
<?php $year = substr($_SESSION['ds_curdate'],0,4);?>
<tr>
  <td><?php echo d_trad('year:');?></td>
  <td colspan=2><select name="year"><?php
      for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
      {
        if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
        else { echo '<option value="' . $i . '">' . $i . '</option>'; }
      }
      ?></select>
  </td>
</tr>
<tr><td><?php require('inc/selectproduct.php');?></td></tr>
<?php
$dp_itemname='productdepartment';$dp_description = d_trad('department'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
$dp_description = d_trad('family'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamilygroup.php');
$dp_description = d_trad('subfamily'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamily.php');
?>
<tr><td><?php $dp_description = d_trad('supplier'); require('inc/selectclient.php');?> &nbsp;<?php echo d_trad('exclude');?><input type="checkbox" name="excludesupplier" value="1"></td></tr>
<tr><td><?php echo d_trad('temperature:');
$dp_itemname = 'temperature'; $dp_allowall = 1; require('inc/selectitem.php'); ?>
<tr><td>Unité de vente:
<?php $dp_itemname = 'unittype'; $dp_allowall = 1; $dp_noblank = 1; require('inc/selectitem.php'); ?>
<tr><td colspan=2><?php echo d_trad('displaystockvalues:');?><input type="checkbox" name="showvalue" value="1"></td></tr>
<tr><td colspan=2><?php echo d_trad('updatestockbymonth:');?><input type="checkbox" name="updatemonthlystock" value="1"></td></tr>
<tr><td colspan=2 class=alert><?php echo d_trad('calcaveragebymonth:');?><input type="checkbox" name="calcavg" value="1"></td></tr>
<?php
if ($_SESSION['ds_userid'] == 1)
{
  echo '<tr><td colspan=2 class=alert>Update endofyearstock for following year: <input type="checkbox" name="update_endofyearstock" value="1"></td></tr>';
}
?>
<tr><td colspan="2" align="center"><input type=hidden name="report" value="cadstockreport"><input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
</table>
</form>