<?php
require('preload/vessel.php');
require('preload/supplier.php');

$mycat = (int) $_POST['mycat'];
$status = (int) $_POST['status'];
$showproducts = (int) $_POST['showproducts'];
$datename = 'start'; require('inc/datepickerresult.php');
$datename = 'stop'; require('inc/datepickerresult.php');

# also in monthlyreport, TODO generalise  
echo '<style>
    tr.temp_linecolor1 td {
      background-color: #ffb6c1;
    }
    tr.temp_linecolor2 td {
      background-color: #ff9900;
    }
    tr.temp_linecolor3 td {
      background-color: #add8e6;
    }
    </style>';
    
if ($status == 0) { echo '<h2>Liste des Commandes non finalized</h2>'; }
else { echo '<h2>Liste des Commandes finalized</h2>'; }
echo '<table class="report"><tr><td><b>Commande</b></td><td><b>Bateau</b></td><td><b>Arrivage</b></td><td><b>Commentaire</b></td><td><b>No Fact</b></td><td><b>No Prof</b></td><td><b>No Comm</b></td><td><b>Taux T/T</b></td>';
if ($showproducts) { echo '<td><b>Produit</b></td><td><b>No Fournisseur</b></td></tr>'; }

$query = 'select supplierid,purchase.productid,productname,noinv,nopro,nocom,tauxtt,shipment.shipmentid,shipmentstatus,shipmentcomment2,vesselid,arrivaldate,numberofcontainers20cold,numberofcontainers40cold
from shipment,purchase,product';
$query .= ' where purchase.shipmentid=shipment.shipmentid and purchase.productid=product.productid';
$query .= ' and arrivaldate>=? and arrivaldate<=?';
if ($status == 0) { $query .= ' and shipmentstatus<>"Fini"'; }
else { $query .= ' and shipmentstatus="Fini"'; }
### union shipments without products
$query .= ' union ';
$query .= ' select "" as supplierid, "" as productid,"" as productname,noinv,nopro,nocom,tauxtt,shipment.shipmentid,shipmentstatus,shipmentcomment2,vesselid,arrivaldate,numberofcontainers20cold,numberofcontainers40cold
from shipment left outer join purchase on purchase.shipmentid=shipment.shipmentid where purchase.shipmentid is null';
$query .= ' and arrivaldate>=? and arrivaldate<=?';
if ($status == 0) { $query .= ' and shipmentstatus<>"Fini"'; }
else { $query .= ' and shipmentstatus="Fini"'; }
###
if ($mycat == 0) { $query = $query . ' order by shipmentid'; }
if ($mycat == 1) { $query = $query . ' order by vesselid,shipmentid'; }
if ($mycat == 2) { $query = $query . ' order by arrivaldate,shipmentid'; }
if ($mycat == 3) { $query = $query . ' order by supplierid,arrivaldate,shipmentid'; }
$query_prm = array($start,$stop,$start,$stop);
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
$lastshipmentid = -1;
for ($i=0;$i<$num_results_main;$i++)
{
  $row = $main_result[$i];
  
  $temp_linecolor = 3;
  if ($row['numberofcontainers20cold'] > 0 || $row['numberofcontainers40cold'] > 0)
  { 
    $temp_linecolor = 1;
  }
  ### new check for supplerid, hardcode for WC atm
  if ($row['supplierid'] == 4126)
  { 
    $temp_linecolor = 2;
  }
  ###
  
  $showline = 0;
  if ($showproducts || $row['shipmentid'] != $lastshipmentid) { $showline = 1; }
  
  if ($showline)
  {
    if ($row['shipmentid'] != $lastshipmentid)
    {
      echo '<tr class="temp_linecolor'.$temp_linecolor.'"><td align=right>' . $row['shipmentid'] . '</td><td>' . $vesselA[$row['vesselid']] . '</td><td>' . datefix2($row['arrivaldate']) . '</td><td>' . $row['shipmentcomment2'] . '</td><td>' . $row['noinv'] . '</td><td>' . $row['nopro'] . '</td><td>' . $row['nocom'] . '</td><td>' . $row['tauxtt'] . '</td>';
    }
    else
    {
      echo '<tr class="temp_linecolor'.$temp_linecolor.'"><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
    }
    if ($showproducts)
    {
      if ($row['productid'] > 0) { echo '<td>' . $row['productid'] . ': ' . d_output(d_decode($row['productname'])) . '</td>'; }
      else { echo '<td></td>'; }
      if ($row['supplierid'] > 0) { echo '<td align=right>' . $row['supplierid'] . ': ' . d_output($supplierA[$row['supplierid']]) . '</td></tr>'; }
      else { echo '<td></td>'; }
    }
    else { echo '</tr>'; }
  }
  
  $lastshipmentid = $row['shipmentid'];
}
echo '</table>';
?>