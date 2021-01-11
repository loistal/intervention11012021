<?php

echo '<h2>Marges Brutes des Produits</h2>';

echo '<form method="post" action="reportwindow.php" target=_blank><table>';

echo '<tr><td>' . d_trad('startdate:') . '<td>';
$datename = 'startdate'; $selecteddate = d_builddate(0,0,(substr($_SESSION['ds_curdate'],0,4)-1));
require('inc/datepicker.php');
echo '</td></tr>';
echo '<tr><td>' . d_trad('stopdate:') . '<td>';
$datename = 'stopdate';
require('inc/datepicker.php');

echo '<tr><td>'; require('inc/selectproduct.php');
$dp_itemname='productdepartment';$dp_description = d_trad('department'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
$dp_description = d_trad('family'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamilygroup.php');
$dp_description = d_trad('subfamily'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamily.php');

?><tr><td><?php $dp_description = d_trad('supplier').':'; $dp_supplier = 1; require('inc/selectclient.php');?> &nbsp;<?php echo d_trad('exclude');?><input type="checkbox" name="excludesupplier" value="1">

<tr><td>&nbsp;
<tr><td>Prix de revient:<td><input type=radio name="costprice" value=0 checked>Dernier
<tr><td><td><input type=radio name="costprice" value=1>Calculé sur la periode

<tr><td>&nbsp;
<tr><td><td><input type=radio name="type" value=0 checked>Ventes et avoirs
<tr><td><td><input type=radio name="type" value=1>Ventes, avoirs et ajustements

<?php

echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="productmargins"><input type="submit" value="' . d_trad('validate') .'"></td></tr></table></form>';

?>