<?php
#reportwindow\warehouselist

$total=0;

require('preload/warehouse.php');

#$orderby = $_POST['orderby']; 

$product = $_POST['product']; 
$productid=$product;

$query = 'select
          product.productid,productname,numberperunit,netweightlabel,suppliercode,
          pallet.productid, pallet.palletid,pallet.barcode,pallet.arrivalid,pallet.quantity,pallet.expiredate,pallet.placementid,
          placement.placementid,placement.placementname,placement.placementrank,placement.warehouseid,placement.creationzone,placement.pickingzone,placement.transportzone,placement.deletionzone
          from product, pallet ,placement
          where product.productid=pallet.productid
          and pallet.deleted=0
          and pallet.placementid=placement.placementid
          and product.productid=?
          order by pallet.expiredate and placement.placementname and placement.placementrank';
    $query_prm = array($productid);
    require('inc/doquery.php');

require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

if ($num_results_main ==0) {echo '<h2>Auncun Emplacement trouvé pour ce produit : ' .$product .'</h2>';}

if ($num_results == 0) {$showproductname=''; $quantity= '' ; $dlv=''; }
    else
    {  
      $showproductname = d_output(d_decode($query_result[0]['productname']));
      if ($_SESSION['ds_useproductcode']) { $showproductname .= ' ('.d_output($query_result[0]['suppliercode']).') '; }
      else { $showproductname .= ' ('.$query_result[0]['productid'].') '; }
      if ($query_result[0]['netweightlabel'] != '')
        {
          if ($query_result[0]['numberperunit'] > 1) { $showproductname .= $query_result[0]['numberperunit'] . ' x '; }
          $showproductname .= d_output($query_result[0]['netweightlabel']);
        }
    echo '<h3>Produit : ' .$showproductname .'</h3>';  
    }
 
    
    
echo '<table class=report>
<p><b><tr><td colspan="5"><td colspan="4" align="center">TYPE de ZONE
<thead><th>DLV<th>Quantité<th>Entrepôt<th>Emplacement<th>Rank<th>Création<th>Picking<th>Transport<th>Destruction</thead>';

for ($i=0; $i < $num_results; $i++)
  {
  echo '<tr>'.d_td_old() .datefix2($query_result[$i]['expiredate']);
  
  echo d_td_old($query_result[$i]['quantity'],1);
  $total+=$query_result[$i]['quantity'];
  
  $warehouseid=$query_result[$i]['warehouseid'];
  echo d_td_old($warehouseA[$warehouseid]);
  
  echo d_td_old($query_result[$i]['placementname']);
  
  echo d_td_old($query_result[$i]['placementrank'],1);
 
  $row = $query_result[$i];
  if ($row['creationzone']) { echo '<td align=center>&radic;</td>'; }
  else { echo '<td>&nbsp;</td>'; } 
  if ($row['pickingzone']) { echo '<td align=center>&radic;</td>'; }
  else { echo '<td>&nbsp;</td>'; } 
  if ($row['transportzone']) { echo '<td align=center>&radic;</td>'; }
  else { echo '<td>&nbsp;</td>'; } 
  if ($row['deletionzone']) { echo '<td align=center>&radic;</td>'; }
  else { echo '<td>&nbsp;</td>'; } 
  }

echo '<tr><td align=right><b>TOTAL' .d_td_old($total,1) .'<td colspan="7">';
echo '</table>';

?>