<?php

echo '<h2>Catalogue Produits</h2>';

echo '<form method="post" action="reportwindow.php" target=_blank><table>';

$dp_itemname='productdepartment';$dp_description = d_trad('department'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
$dp_description = d_trad('family'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamilygroup.php');
$dp_description = d_trad('subfamily'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem_productfamily.php');

?><tr><td><?php $dp_description = d_trad('supplier').':'; $dp_supplier = 1; require('inc/selectclient.php');?> &nbsp;<?php echo d_trad('exclude');?><input type="checkbox" name="excludesupplier" value="1">

<tr><td>&nbsp;
<tr><td>Afficher images :<td><input type=radio name="showimages" value=1 checked>Un
<tr><td><td><input type=radio name="showimages" value=2>Tous
<tr><td><td><input type=radio name="showimages" value=0>Aucun
<tr><td>Hauteur :<td><input type=text name="height" value="100" size=6>
<tr><td>Largeur :<td><input type=text name="width" value="" size=6>

<tr><td>&nbsp;
<tr><td>Champs :<td><input type=checkbox name="show_unittype" value=1>Unité de vente
<tr><td><td><input type=checkbox name="show_salesprice" value=1 checked>Prix
<tr><td><td><input type=checkbox name="show_detailsalesprice" value=1>Prix Alternatif
<tr><td><td><input type=checkbox name="show_islandregulatedprice" value=1>Prix PGL
<tr><td><td><input type=checkbox name="show_retailprice" value=1>Prix réglementé
<tr><td><td><input type=checkbox name="show_stock" value=1 checked>Stock
<tr><td><td><input type=checkbox name="showweight" value=1>Poids Brut / carton
<tr><td><td><input type=checkbox name="showeancode" value=1>Code EAN unité
<tr><td><td><input type=checkbox name="showpromotext" value=1>Infos promo

<?php
$ok = 0;
if ($_SESSION['ds_customname'] == 'Wing Chong'
&& ($_SESSION['ds_userid'] == 13 || $_SESSION['ds_userid'] == 63 || $_SESSION['ds_userid'] == 65))
{
  $ok = 0;
}
else
{
  $ok = 1;
}
if ($ok)
{
  echo '<tr><td><td><input type=checkbox name="show_commissions" value=1>Commissions';
}
?>

<?php
echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="productcatalogue">
<input type="submit" value="' . d_trad('validate') .'"></table></form>';
?>