<style>
table {
  white-space: normal
}
</style>

<?php

$shipmentid = $_POST['shipmentid'];

$query = 'select vesselname,arrivaldate,shipmentcomment,nocom from shipment,vessel
where shipment.vesselid=vessel.vesselid and shipmentid="' . $shipmentid . '"';
$query_prm = array();
require('inc/doquery.php');
$row = $query_result[0];
$arrivaldate = $row['arrivaldate'];
$vesselname = $row['vesselname'];
$container = $row['shipmentcomment'];
$nocom = $row['nocom'];

echo '<TITLE>Packing List Dossier No ' . $shipmentid . '</TITLE>';

echo '<table><tr width=100%><td width=300>';
$ourlogofile = './custom/' . mb_strtolower($_SESSION['ds_customname']) . '.jpg';
  if (file_exists($ourlogofile)) { echo '<IMG alt="' . $_SESSION['ds_customname'] . '" src="' . $ourlogofile . '" border=0>'; }
  else { echo '<b>' . $_SESSION['ds_customname'] . '</b>'; } 
echo '<br>Navire : ' . $vesselname . '<br>Arrivé le : ' . datefix2($arrivaldate) . '<br>N<sup>o</sup> Commande: '.d_output($nocom);
echo '<td valign=top><b><u><font size=+3>Packing List Dossier No ' . $shipmentid . '</font></td>';
echo '<td width=300 align=right>'.$_SESSION['ds_packinglisttop'].'</td></tr></table>';
echo '<td width=300 align=right>&nbsp;</td></tr></table>';

echo '<table class="report"><tr width=100%><td rowspan=2><b>Conteneur</td><td rowspan=2><b>Scellé</td>
<td rowspan=2><b>Batch</td><td rowspan=2><b>Pallet ID</td><td rowspan=2><b>Code</td>
<td rowspan=2><b>Code F.</td><td rowspan=2><b>Produit</td><td rowspan=2><b>Cond.</td><td colspan=2><b>Commandé</td>
<td rowspan=2><b>TIxHI</td><td rowspan=2><b>Reçu&nbsp;bon&nbsp;état</td><td rowspan=2><b>Reçu avariés</td><td rowspan=2><b>Non reçu</td>
<td rowspan=2><b>DLUO / DLC / DDM</b><br>JJ/MM/AAAA</td></tr>';
echo '<tr><td><b>Q1</td><td><b>Q2</td></tr>';
$query = 'select pallet_list,suppliercode,ti,hi,batchname,purchase.productid,productname,numberperunit,netweightlabel,amount,amountcartons,useby,supplier_pallet_barcode,supplierbatchname
from purchase,product
where purchase.productid=product.productid and shipmentid=?
order by purchaseid';
$query_prm = array($shipmentid);
require('inc/doquery.php');
$totalw = 0; $totalf = 0;
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  echo '<tr><td>';
  #if ($i == 1) { echo $container; }
  #else { echo '&nbsp;'; }
  echo $row['batchname'];
  $amountw = ($row['amount']/$row['numberperunit'])+0;
  $amountf = $row['amountcartons']+0;
  $totalw = $totalw + $amountw;
  $totalf = $totalf + $amountf;
  if ($amountf == 0) { $amountf = '&nbsp;'; }
  echo '</td><td>&nbsp;</td><td>' . $row['supplierbatchname'];
  echo '<td>' . $row['supplier_pallet_barcode'] . ' ' . $row['pallet_list'];
  echo '<td align=right>' . $row['productid'] . '</td><td>' . $row['suppliercode'] . '</td><td>' . $row['productname'] . '</td><td>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td><td align=right>' . $amountw . '</td><td align=right>' . $amountf . '</td><td>';
  if ($row['ti'] || $row['hi']) { echo $row['ti'] . ' x ' . $row['hi']; }
  else { echo '&nbsp;'; }
  echo '</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>' . datefix2($row['useby']) .'</td></tr>';
}
if ($totalf == 0) { $totalf = '&nbsp;'; }
else { $totalf = myfix($totalf); }
if (is_int($totalw)) { $kladd = myfix($totalw); }
else { $kladd = myfix($totalw,2); }
echo '<tr><td colspan=8 align=right><b>TOTAL</td><td align=right><b>' . $kladd . '</td><td align=right><b>' . $totalf . '</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
echo '</table><br>';

echo $_SESSION['ds_packinglistbottom'];

/*
<br><b>Avis au pointeur :</b><br>
Contrôlez le numéro du conteneur, la quantité, le nom et le conditionnement de chaque produit.<br>
Complétez le numéro de scellé et la DLUO / DLC.<br>
Plastifiez les palettes et coller les affiches DLV dûment complétées sur chaque palette.<br>
Les échantillons et les articles publicitaires sont à remettre à la direction.<br>
En cas d'avarie / manquant, contactez immédiatement le 543546 ou 764000.
*/
?>