<?php
require('preload/unittype.php');
require('preload/warehouse.php');
require('preload/client.php');
require('preload/unittype.php');
require('preload/temperature.php'); $temperatureA[0] = '';

$PA['warehouseid'] = 'int';
$PA['temperatureid'] = 'int';
$PA['invoicegroupids'] = 'in_list_int';
require('inc/readpost.php');

$last_temperatureid = -1;
$preparationtext = "";
$ourtitle = "Bon pour Entrepôt";
if ($warehouseid > 0) { $ourtitle = $ourtitle . ' ' . $warehouseA[$warehouseid]; }
$query = 'select preparationtext,curdate() as curdate from invoicegroup where invoicegroupid in ' . $invoicegroupids;
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  if ($i > 1 && $row['preparationtext'] != "") { $preparationtext = $preparationtext . ' '; }
  $preparationtext = $preparationtext . $row['preparationtext'];
  $curdate = $row['curdate'];
}
$title = datefix($curdate) . ' - ' . $ourtitle . $invoicegroupids . ' - ' . $preparationtext;
showtitle($title);
echo '<font size=+1><b>' . datefix($curdate) . ' - ' . $ourtitle . $invoicegroupids . ' - ' . $preparationtext . '</b></font>';

$total = 0; $total_units = 0; $cqA = array(); $cquA = array();
$query_prm = array();
$query = 'select product.productid,productname,temperatureid,warehouseid,invoicehistory.clientid,clientname,quantity,numberperunit,unittypeid
from client,invoicehistory,invoiceitemhistory,product
where invoicehistory.clientid=client.clientid and product.productid=invoiceitemhistory.productid
and invoiceitemhistory.invoiceid=invoicehistory.invoiceid';
$query = $query . ' and countstock=1 and invoicegroupid in ' . $invoicegroupids;
if ($warehouseid>0) { $query = $query . ' and product.warehouseid=?'; array_push($query_prm, $warehouseid); }
if ($temperatureid >= 0) { $query = $query . ' and product.temperatureid=?'; array_push($query_prm, $temperatureid); }
if ($temperatureid == -2) { $query = $query . ' and product.temperatureid>0'; }
#$query .= ' order by productname,clientid';
$query .= ' order by product.temperatureid desc,productname,clientid';
require('inc/doquery.php');
echo d_table('report');
echo '<thead><th>Produit<th>Total<th>Par client</thead>';
for ($i=0; $i < $num_results; $i++)
{
  $quantity_units = 0;
  $quantity = ($query_result[$i]['quantity'] / $query_result[$i]['numberperunit']) / $unittype_dmpA[$query_result[$i]['unittypeid']];
  if ($unittype_dmpA[$query_result[$i]['unittypeid']] == 1)
  {
    $quantity = floor($quantity);
    $quantity_units = $query_result[$i]['quantity'] % $query_result[$i]['numberperunit'];
    $total_units += $query_result[$i]['quantity'] % $query_result[$i]['numberperunit'];
  }
  $total += $quantity;
  if (!isset($cqA[$query_result[$i]['clientid']]))
  {
    $cqA[$query_result[$i]['clientid']] = 0;
    $cquA[$query_result[$i]['clientid']] = 0;
  }
  $cqA[$query_result[$i]['clientid']] += $quantity;
  $cquA[$query_result[$i]['clientid']] += $quantity_units;
  if (!isset($query_result[($i+1)]['productid']) || $query_result[$i]['productid'] != $query_result[($i+1)]['productid'])
  {
    $clientstring = '';
    foreach ($cqA as $clientid => $quantity)
    {
      $clientstring .= $quantity;
      if ($cquA[$clientid]) { $clientstring .=' +'.$cquA[$clientid].'u'; }
      $clientstring .= ' ' . $clientA[$clientid] . '<br>';
    }
    $clientstring = rtrim($clientstring, '<br>');
    if ($last_temperatureid != $query_result[$i]['temperatureid'])
    {
      echo d_tr(1), d_td($temperatureA[$query_result[$i]['temperatureid']],'bold',10);
      $last_temperatureid = $query_result[$i]['temperatureid'];
    }
    echo d_tr();
    echo d_td($query_result[$i]['productname'].' ('.$query_result[$i]['productid'].')');
    if ($total_units == 0) { echo d_td($total,'decimal'); }
    else { echo d_td($total.' +'.$total_units.'u','right'); }
    echo d_td_unfiltered($clientstring);
    $total = 0; $total_units = 0; $cqA = array(); $cquA = array();
  }
}
echo d_table_end();

?>