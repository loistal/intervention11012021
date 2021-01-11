<?php
echo '<h2>' . d_trad('products:') . '</h2>';
?>

<form method="post" action="reportwindow.php" target=_blank><table>
<tr><td><?php require('inc/selectproduct.php');?></td></tr>
<tr><td><?php echo d_trad('eancode:');?></td><td><input type="text" name="eancode" size=20></td></tr>
<tr><td><?php $dp_description = d_trad('supplier'); require('inc/selectclient.php');?></td></tr>
<tr><td><?php echo d_trad('origincountry:'); $dp_itemname = "country";$dp_allowall = 1;$dp_noblank = 1; require('inc/selectitem.php'); ?></td></tr>

<?php
$dp_itemname='productdepartment';$dp_description = d_trad('department'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
$dp_description = d_trad('family'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamilygroup.php');
$dp_description = d_trad('subfamily'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamily.php');
?>
  
<?php
$dp_description = d_trad('taxcode'); $dp_itemname = 'taxcode'; $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
$dp_description = d_trad('unittype'); $dp_itemname = 'unittype'; $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
?>
<tr>
  <td><?php echo $_SESSION['ds_term_discontinued'];?></td>
  <td><select name="displaydiscontinued">
      <option value='0'><?php echo d_trad('displayall');?></option>
      <option value='1' selected><?php echo d_trad('dontdisplay');?></option>
      <option value='2'><?php echo d_trad('display');?></option></select></td>
</tr>
<tr>
  <td><?php echo d_trad('notforsale:');?></td>
  <td><select name="displaynotforsale">
      <option value='0'><?php echo d_trad('displayall');?></option>
      <option value='1' selected><?php echo d_trad('dontdisplay');?></option>
      <option value='2'><?php echo d_trad('display');?></option></select></td>
</tr>
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
<tr><td colspan=2>&nbsp;</td></tr>

<tr><td align=right><input type="checkbox" name="updatestock" value="1"></td><td><b><?php echo '&nbsp;' . d_trad('updatestock');?><b></td></tr>
<tr><td align=right><input type="checkbox" name="showreport_all_columns" value="1"></td><td><b>Afficher TOUTES les colonnes<b></td></tr>
<tr><td align=right><input type="checkbox" name="negative_stock" value="1"></td><td><b>Produits ayant un stock négatif<b></td></tr>

<tr><td colspan =2>&nbsp;</td></tr>
<tr><td colspan="2" align="center"><input type=hidden name="report" value="productreport"><input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
</table></form>

<?php

require('reportwindow/productreport_cf.php');
$dp_menu = 'productreport'; require('inc/configreport.php');

?>