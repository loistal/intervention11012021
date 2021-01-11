<?php

echo '<h2>Produits sans ventes</h2>';

echo '<form method="post" action="reportwindow.php" target=_blank><table>';

echo '<tr><td>' . d_trad('startdate:') . '</td><td>';
$datename = 'startdate';
require('inc/datepicker.php');
echo '</td></tr>';
echo '<tr><td>' . d_trad('stopdate:') . '</td><td>';
$datename = 'stopdate';
require('inc/datepicker.php');

$dp_itemname='productdepartment';$dp_description = d_trad('department'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
$dp_description = d_trad('family'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamilygroup.php');
$dp_description = d_trad('subfamily'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamily.php');

?><tr><td><?php $dp_description = d_trad('supplier').':'; require('inc/selectclient.php');?> &nbsp;<?php echo d_trad('exclude');?><input type="checkbox" name="excludesupplier" value="1"></td></tr><?php

echo '<tr><td align=right><input type=checkbox name="in_stock" value=1><td>Limiter aux produits en stock';

echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="nosales"><input type="submit" value="' . d_trad('validate') .'"></td></tr></table></form>';

?><br><p>Produits discontinués ou non à vendre exclus</p>