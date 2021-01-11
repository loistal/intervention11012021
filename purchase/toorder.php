<h2>Produits à commander:</h2>
<form method="post" action="reportwindow.php" target="_blank"><table>
<?php
$dp_description = 'Département'; $dp_itemname = 'productdepartment'; $dp_allowall = 1; $dp_noblank = 1; $dp_selectedid = -1;
require('inc/selectitem.php');
?>
<tr><td>Classe de produit:</td>
<td><select name="productfamilygroupid"><?php
echo '<option value="0">Tous</option>';
$query = 'select productfamilygroupid,productfamilygroupname,productdepartmentname from productdepartment,productfamilygroup where productfamilygroup.productdepartmentid=productdepartment.productdepartmentid order by departmentrank,familygrouprank';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  echo '<option value="' . $row['productfamilygroupid'] . '">' . $row['productdepartmentname'] . '/' . $row['productfamilygroupname'] . '</option>';
}
?></select></td></tr>
<tr><td>Numéro de fournisseur:</td><td><input type="text" STYLE="text-align:right" name="supplierid" size=5>
&nbsp; Exclure: <input type=checkbox name="exclude_supplier" value=1>
<tr><td colspan=2>Exclure produits avec arrivages: <input type="checkbox" name="exarr" value="1"></td></tr>
<tr><td colspan=2>Alerte Ventes Exceptionnelles (mois > 150% moyenne): <input type="checkbox" name="vexcpt" value="1"></td></tr>
<?php
$dp_description = 'Temperature'; $dp_itemname = 'temperature'; $dp_allowall = 1; $dp_selectedid = -1;
require('inc/selectitem.php');
?>
<tr><td>Calcul coefficient:</td><td><select name="whichavg"><option value="1">Utiliser moyenne spécifié</option><option value="2">Utiliser moyenne calculé</option></td></tr>
<tr><td colspan="2" align="center"><span class="alert">Utiliser le rapport Cadence Stock pour mettre à jour la moyenne calculé</span>
<tr><td colspan="2" align="center"><input type=hidden name="report" value="toorder"><input type="submit" value="Valider"></td></tr>
</table></form>