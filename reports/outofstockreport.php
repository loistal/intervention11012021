<?php

echo '<h2>' . d_trad('outofstock:') . '</h2>';
?>

<form method="post" action="reportwindow.php" target=_blank><table>
<tr>
    <td><?php echo d_trad('startdate:'); ?></td>
    <td><?php $datename = 'startdate'; require('inc/datepicker.php');?></td>
</tr>
<tr>
  <td><?php echo d_trad('stopdate:'); ?></td>
  <td><?php $datename = 'stopdate'; require('inc/datepicker.php');?></td>
</tr>
<tr><td><?php require('inc/selectproduct.php');?></td></tr>
<?php 
$dp_itemname='productdepartment';$dp_description = d_trad('department'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
$dp_description = d_trad('family'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamilygroup.php');
$dp_description = d_trad('subfamily'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamily.php');
?>

<tr><td colspan =2>&nbsp;</td></tr>
<tr><td align=right><input type="checkbox" name="updatestock" value="1"></td><td><b><?php echo '&nbsp;' . d_trad('updatestock');?><b></td></tr>
<tr><td colspan =2>&nbsp;</td></tr>
<tr>
  <td><?php echo d_trad('orderby:');?></td>
  <td><select name="orderby">
        <?php
        $productidlabel = d_trad('number');
        if ($_SESSION['ds_useproductcode'] == 1) { $productidlabel = d_trad('code'); }
        ?>
        <option value="1"><?php echo $productidlabel;?></option>
        <option value="2"><?php echo d_trad('name');?></option>
        <option value="3"><?php echo d_trad('family');?></option>
      </select>
  </td>
</tr>
<tr><td colspan =2>&nbsp;</td></tr>
<tr><td colspan="2" align="center"><input type=hidden name="report" value="outofstockreport"><input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
</table></form>