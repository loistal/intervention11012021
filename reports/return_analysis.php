<?php

echo '<h2>Analyse des Avoirs</h2>';

echo '<form method="post" action="reportwindow.php" target=_blank><table>
<tr><td>Analyse par :<td><select name="by">
<option value=0>Raison d\'avoir</option>
<option value=1>Produit</option>
</select>';

echo '<tr><td>De :<td>';
$datename = 'startdate';
require('inc/datepicker.php');
echo '<tr><td>À :<td>';
$datename = 'stopdate';
require('inc/datepicker.php');

echo '<tr><td>'; require('inc/selectproduct.php');
$dp_itemname='productdepartment';$dp_description = d_trad('department'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
$dp_description = d_trad('family'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamilygroup.php');
$dp_description = d_trad('subfamily'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamily.php');

?><tr><td><?php $dp_description = d_trad('supplier').':'; $dp_supplier = 1; require('inc/selectclient.php');?> &nbsp;<input type="checkbox" name="excludesupplier" value="1"><?php echo d_trad('exclude');?>

<?php

# filter by client, clientcategory?

echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="return_analysis"><input type="submit" value="' . d_trad('validate') .'"></td></tr></table></form>';

?>