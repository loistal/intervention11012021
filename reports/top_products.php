<h2>Meilleurs Produits</h2>
<form method="post" action="reportwindow.php" target="_blank"><table>
<tr><td>Top:<td><input type=text name="choice" value=10 size=8 style="text-align: right">
<?php $dp_itemname = 'employee'; $dp_issales = 1; $dp_allowall = 1;$dp_description = 'Employé(e)'; require('inc/selectitem.php');
$dp_itemname='productdepartment';$dp_description = d_trad('department'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
$dp_description = d_trad('family'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamilygroup.php');
$dp_description = d_trad('subfamily'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamily.php');
?>
<tr><td>Marque :<td><input type=text name="brand" size=40>
<?php
$dp_itemname='supplier'; $dp_description = 'Fournisseur'; $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
$dp_itemname='user'; $dp_description = 'Utilisateur'; $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
?>
<tr><td><?php echo d_trad('startdate:'); ?>
<td><?php $datename = 'startdate';$selecteddate = '';$dp_setempty = 1;require('inc/datepicker.php');?>
<tr><td><?php echo d_trad('stopdate:'); ?>
<td><?php $datename = 'stopdate';$selecteddate = '';$dp_setempty = 1;require('inc/datepicker.php');?>
<tr><td><?php echo d_trad('orderby:'); ?>
<td><select name="orderby">
<?php
echo '<option value=1>' .  d_trad('sales') . '</option>';
#echo '<option value=2>Nom du produit</option>';
?>
</select>
<tr><td colspan=2>&nbsp;
<tr><td>Comparer avec:
<td><?php $datename = 'startdate2';$selecteddate = '';$dp_setempty = 1;require('inc/datepicker.php');?>
<tr><td>
<td><?php $datename = 'stopdate2';$selecteddate = '';$dp_setempty = 1;require('inc/datepicker.php');?>
<tr><td colspan=2>&nbsp;
<tr><td colspan=2 align=center>
<input type=hidden name="report" value="top_products">                      
<input type="submit" value="<?php echo d_trad('validate');?>">
</table></form>