<?php

$shipmentid = (int) $_POST['shipmentid'];

$query = 'select promotext,suppliercode,eancode,product.productid,productname,brand,numberperunit,netweightlabel,unittypename,displaymultiplier,sih,productcomment,salesprice,islandregulatedprice,retailprice,currentstock,taxcode,producttypename from product,unittype,taxcode,producttype,purchasebatch where product.productid=purchasebatch.productid and product.unittypeid=unittype.unittypeid and product.taxcodeid=taxcode.taxcodeid and product.producttypeid=producttype.producttypeid and discontinued=0 and shipmentid="' . $shipmentid . '" order by purchaseid,productname';
$query_prm = array();
require('inc/doquery.php');
echo '<h2>Prix et produits dossier ' . $shipmentid . '</h2>';
echo '<table class="report"><tr><td class="breakme"><b>Numéro produit</b></td><td><b>Description</b></td><td><b>Marque</b></td><td class="breakme"><b>Unité de vente</b></td><td class="breakme"><b>Conditionnement</b></td><td class="breakme"><b>Prix G HT</b></td><td class="breakme"><b>Prix G TTC</b></td><td class="breakme"><b>Prix GU HT</b></td><td class="breakme"><b>Prix GU TTC</b></td><td class="breakme"><b>Prix GI HT</b></td><td class="breakme"><b>Prix GI TTC</b></td><td class="breakme"><b>Prix GIU HT</b></td><td class="breakme"><b>Prix GIU TTC</b></td><td class="breakme"><b>Prix DU HT</b></td><td class="breakme"><b>Prix DU TTC</b></td>';
echo '<td><b>Promo</b></td><td><b>Commentaire</b></td><td><b>EAN</b></td><td>&nbsp;</td></tr>';
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  $specialchar = "";
  #if ($row['producttypename'] == "PPN") { $specialchar = "+"; }
  if ($row['producttypename'] == "PGL") { $explainpgl = 1; }
  #if ($row['producttypename'] == "PGC") { $specialchar = "#"; }
  $dmp = $row['displaymultiplier'];
  echo '<tr><td align=right>' . $row['productid'] . '</td><td class="breakme">' . d_decode($row['productname']) . '</td><td class="breakme">' . $row['brand'] . '</td><td>' . $row['unittypename'] . '</td><td class="breakme" algin=right>' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '</td>
  <td align=right>' . myfix($row['salesprice']*$dmp) . '&nbsp;</td><td align=right>' . myfix($row['salesprice']*$dmp + ($row['salesprice']*$dmp * $row['taxcode']/100)) . '&nbsp;</td>';
  echo '<td align=right>' . myfix($row['salesprice']*$dmp/$row['numberperunit']) . '&nbsp;</td><td align=right>' . myfix(($row['salesprice']*$dmp + ($row['salesprice']*$dmp * $row['taxcode']/100))/$row['numberperunit']) . '&nbsp;</td>';
echo '<td align=right>' . myfix($row['islandregulatedprice']*$dmp) . '&nbsp;</td><td align=right>' . myfix($row['islandregulatedprice']*$dmp + ($row['islandregulatedprice']*$dmp * $row['taxcode']/100)) . '&nbsp;</td>';
echo '<td align=right>' . myfix($row['islandregulatedprice']*$dmp/$row['numberperunit']) . '&nbsp;</td><td align=right>' . myfix(($row['islandregulatedprice']*$dmp + ($row['islandregulatedprice']*$dmp * $row['taxcode']/100))/$row['numberperunit']) . '&nbsp;</td>';
  echo '<td align=right>' . myfix($row['retailprice']*$dmp/$row['numberperunit']) . '&nbsp;</td><td align=right>' . myfix(($row['retailprice']*$dmp + ($row['retailprice']*$dmp * $row['taxcode']/100))/$row['numberperunit']) . '&nbsp;</td>';
  echo '<td class="breakme">' . $row['promotext'] . '</td><td class="breakme">' . $row['productcomment'] . '</td><td>' . $row['eancode'] . '</td><td class="breakme">' . $row['producttypename'] . '</td></tr>';
}
if ($num_results == 0) { echo '<tr><td colspan=7>Pas de produit trouvé.</td></tr>'; }
?></table><?php
if ($explainpgl)
{
  echo '<p>PGL libre sur les îsles de: ';
  $query = 'select islandname from island where outerisland=0 order by islandid';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0;$i<$num_results;$i++)
  {
    if ($i != 0) { echo ', '; }
    echo $query_result[$i]['islandname'];
  }
  echo '</p>';
}

?>