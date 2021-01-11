<?php
require('preload/placement.php');
require('preload/product.php');

$total_product = 0;

# TODO this report filters deleted=0 and quantity>0, these should be options

echo '<h2>Rapport Stockage</h2>';

$product = $_POST['product'];
require('inc/findproduct.php');
if($num_products != 1)
{
  echo '<p span class="alert">Aucun Produit trouvé :  ' . d_output($product) .'</span></p><br>'; 
}

$query = 'select p.productid, p.palletid,p.pallet_barcodeid,pb.barcode,p.arrivalid,p.quantity,p.expiredate,p.placementid,numberperunit
from pallet p, pallet_barcode pb, placement pl, product
where p.productid=product.productid and p.pallet_barcodeid=pb.pallet_barcodeid and pl.placementid=p.placementid
and p.deleted=0 and quantity>0';
$query_prm = array();
$query .= ' and p.productid=?';
$query_prm = array($productid);

$query .= ' order by p.expiredate,p.pallet_barcodeid,pl.placementname,pl.placementrank ';
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

showtitle('Rapport par Produit');

echo '<table class=report>';
echo '<thead><th>DLV<th>Code-Barre Palette<th>Quantité<th>Emplacement<th>Rank<th>Zone de Création<th>Zone de Picking<th>Zone de Transport<th>Zone de Destruction</thead>';
if ($productid > 0 )
{ 
  echo '<p>' .$productid. ': '.$productname;
}

for ($i=0; $i < $num_results_main; $i++)
{
  $row = $main_result[$i];
  echo d_tr();
  echo d_td_old(datefix2($row['expiredate']),1);
  echo d_td_old($row['barcode'],1);
  #echo d_td_old(myfix($row['quantity']),1); $total_product += ($row['quantity']);
  $kladd = floor($row['quantity'] / $row['numberperunit']); $total_product += $kladd; # TODO subunits
  echo d_td_old(myfix($kladd),1);
  $placementid = $main_result[$i]['placementid'];
  echo '<td>' . $placementA[$placementid] . d_td_old($placement_placementrankA[$placementid],1) ;
  if ($placement_creationzoneA[$placementid] == 1) { echo '<td align=center>&radic;</td>'; }
  else { echo '<td>&nbsp;</td>'; } 
  if ($placement_pickingzoneA[$placementid] == 1) { echo '<td align=center>&radic;</td>'; }
  else { echo '<td>&nbsp;</td>'; } 
  if ($placement_transportzoneA[$placementid] == 1) { echo '<td align=center>&radic;</td>'; }
  else { echo '<td>&nbsp;</td>'; } 
  if ($placement_deletionzoneA[$placementid] == 1) { echo '<td align=center>&radic;</td>'; }
  else { echo '<td>&nbsp;</td>'; } 
}
echo '<tr><td align=left><b>TOTAL:<td>' .d_td_old(myfix($total_product),1) .'<td colspan="6" align=center>' .'Sur ' .myfix($i) .' Emplacement(s)'   ;
echo '</table>';
?>