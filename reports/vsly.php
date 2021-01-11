<h2>Vs année passée:</h2>

<form method="post" action="reportwindow.php" target="_blank">
<table>

<?php
$year = mb_substr($_SESSION['ds_curdate'],0,4);
?><tr><td>Année:</td><td><select name="year"><?php
for ($i=$_SESSION['ds_startyear']; $i <= $year; $i++)
{
  if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
  else { echo '<option value="' . $i . '">' . $i . '</option>'; }
}
?></select></td></tr>

<?php
$dp_itemname = 'employee'; $dp_description = 'Employé'; $dp_allowall = 1; $dp_issales = 1; require('inc/selectitem.php');
?>
<tr><td><?php require('inc/selectclient.php'); ?></td></tr>
<?php
$dp_description = $_SESSION['ds_term_clientcategory'];
$dp_allowall = 1; $dp_itemname = 'clientcategory'; require('inc/selectitem.php');
$dp_description = $_SESSION['ds_term_clientcategory2'];
$dp_allowall = 1; $dp_itemname = 'clientcategory2'; require('inc/selectitem.php');
$dp_description = $_SESSION['ds_term_clientcategory3'];
$dp_allowall = 1; $dp_itemname = 'clientcategory3'; require('inc/selectitem.php');

$dp_description = 'Île';
$dp_allowall = 1; $dp_itemname = 'island'; require('inc/selectitem.php');
?>
<tr><td>Numéro fournisseur:</td><td><input type="text" STYLE="text-align:right" name="supplierid" size=10> <input type="checkbox" name="exsupplierid" value="1">Exclure</td></tr>
<tr><td>Numéro produit:</td><td><input type="text" STYLE="text-align:right" name="productid" size=10>
<?php
$dp_itemname='productdepartment';$dp_description = d_trad('department'); $dp_allowall = 1; $dp_noblank=1; require('inc/selectitem.php');
?>
<tr><td align=right>Afficher:</td><td><select name="unit">
<option value=1>Valeur HT</option>
<option value=2>Poids Brut (KG)</option>
<option value=3>Cartons</option>
<option value=4>Volume</option>
</select></td></tr>
<tr><td align=right>Trier par:</td><td><select name="orderby">
<option value=0>Départment</option>
<option value=1>Famille</option>
<option value=2>Type</option>
<option value=3>Produit</option>
</select></td></tr>
<tr><td align=right><input type=checkbox name="showlast" value=1 checked></td><td>Afficher l'année passée</td></tr>
<tr><td align=right><input type=checkbox name="showthis" value=1 checked></td><td>Afficher l'année</td></tr>
<tr><td align=right><input type=checkbox name="showpercent" value=1 checked></td><td>Afficher progression en pourcentage</td></tr>
<tr><td align=right><input type=checkbox name="comrateid2" value=1></td><td>Exclure produits hors commission</td></tr>
<tr><td align=right><input type=checkbox name="showreturns" value=1></td><td>Avoirs sur ligne separée</td></tr>
<?php
#<tr><td align=right><input type=checkbox name="graph" value=1></td><td>Afficher graphique</td></tr>
?>
<tr><td colspan=2><input type=hidden name="report" value="vsly"><input type="submit" value="Valider"></td></tr></table></form>