<?php

require('preload/warehouse.php');
require('preload/island.php');
require('preload/town.php');
require('preload/unittype.php');

$PA['warehouseid'] = 'int';
$PA['temperatureid'] = 'int';
$PA['invoicegroupids'] = 'in_list_int';
$PA['split_extraname'] = 'uint';
require('inc/readpost.php');

$last_islandid = $last_townid = -1; $showclientA = array();

$preparationtext = "";
$ourtitle = "Bon pour Entrepôt";
if ($warehouseid > 0) { $ourtitle .= ' ' . $warehouseA[$warehouseid]; }
$query = 'select preparationtext from invoicegroup where invoicegroupid in ' . $invoicegroupids;
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $preparationtext .= ' ' . $query_result[$i]['preparationtext'];
}
$title = datefix($_SESSION['ds_curdate']) . ' - ' . $ourtitle . ' ' . $invoicegroupids . ' - ' . $preparationtext;
showtitle_new($title);

# create list of all products
$p_listA = array();
$query = 'select distinct invoiceitemhistory.productid,productname,unittypeid,numberperunit,suppliercode
from invoiceitemhistory,invoicehistory,product
where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and invoiceitemhistory.productid=product.productid
and excludefromdelivery=0 and invoicegroupid in ' . $invoicegroupids;
$query_prm = array();
if ($warehouseid > 0) { $query .= ' and product.warehouseid=?'; array_push($query_prm, $warehouseid); }
if ($temperatureid >= 0) { $query .= ' and product.temperatureid=?'; array_push($query_prm, $temperatureid); }
if ($temperatureid == -2) { $query .= ' and product.temperatureid>0'; }
$query .= ' order by temperatureid desc,productname';
require('inc/doquery.php');
$num_products = $num_results;
for ($i=0; $i < $num_results; $i++)
{
  $p_listA[$i] = $query_result[$i]['suppliercode'].' '.d_decode($query_result[$i]['productname']);
  $pid_listA[$i] = $query_result[$i]['productid'];
  $p_unittypeidA[$query_result[$i]['productid']] = $query_result[$i]['unittypeid'];
  $p_npuA[$query_result[$i]['productid']] = $query_result[$i]['numberperunit'];
  $totalA[$query_result[$i]['productid']] = 0;
}

# read in all products and quantities
# populate productquantity by client array
# stupid stupid Wing Chong cannot manage to create separate client accounts, so... concat id+extraname
$c_oA = array();
if ($split_extraname) { $query = 'select productid,quantity,concat(clientid,extraname) as clientid'; }
else { $query = 'select productid,quantity,clientid'; }
$query .= ' from invoiceitemhistory,invoicehistory
where invoiceitemhistory.invoiceid=invoicehistory.invoiceid
and invoicegroupid in ' . $invoicegroupids;
$query_prm = array();
$query .= ' order by clientid';
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if (!isset($c_oA[$query_result[$i]['clientid']][$query_result[$i]['productid']]))
  { $c_oA[$query_result[$i]['clientid']][$query_result[$i]['productid']] = 0; }
  $toadd = $query_result[$i]['quantity'];
  if (isset($p_unittypeidA[$query_result[$i]['productid']])
  && $unittype_dmpA[$p_unittypeidA[$query_result[$i]['productid']]] != 1)
  {
    $toadd = $toadd / $unittype_dmpA[$p_unittypeidA[$query_result[$i]['productid']]];
    if (!isset($carton_instanceA[$query_result[$i]['clientid']][$query_result[$i]['productid']]))
    { $carton_instanceA[$query_result[$i]['clientid']][$query_result[$i]['productid']] = 0; }
    $carton_instanceA[$query_result[$i]['clientid']][$query_result[$i]['productid']]++;
  }
  $c_oA[$query_result[$i]['clientid']][$query_result[$i]['productid']] += $toadd;
}

echo d_table('report');
echo '<thead><th colspan=2 align=left valign=top><br>Chauffeur :<br><br>N<sup>o</sup> véhicule :';
foreach ($p_listA as $productid => $display)
{
  echo '<th class="breakme"><font size=-2>',$display,'</font>';
}
echo '</thead>';
if ($split_extraname) { $query = 'select distinct concat(client.clientid,extraname) as clientid,clientname,client.townid,islandid'; }
else { $query = 'select distinct client.clientid,clientname,client.townid,islandid'; }
$query .= ' from client,invoicehistory,town
where invoicehistory.clientid=client.clientid and client.townid=town.townid
and invoicegroupid in ' . $invoicegroupids .' order by islandid,townname,clientname'; # could use island and town ranks
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{
  $showclientA[$main_result[$i]['clientid']] = 0;
  for ($y=0; $y < $num_products; $y++)
  {
    if (isset($c_oA[$main_result[$i]['clientid']][$pid_listA[$y]])
    && $c_oA[$main_result[$i]['clientid']][$pid_listA[$y]] > 0) { $showclientA[$main_result[$i]['clientid']] = 1; }
  }
}
for ($i=0; $i < $num_results_main; $i++)
{
  if ($showclientA[$main_result[$i]['clientid']])
  {
    if ($main_result[$i]['islandid'] != $last_islandid)
    {
      echo d_tr(1),d_td($islandA[$main_result[$i]['islandid']],'emphasis center',2),d_td('','',1000);
    }
    echo d_tr();
    echo d_td($townA[$main_result[$i]['townid']],'emphasis');
    echo d_td(d_decode($main_result[$i]['clientname'].' ('.$main_result[$i]['clientid'].')'),"breakme");
    $last_islandid = $main_result[$i]['islandid'];
    $last_townid = $main_result[$i]['townid'];
    for ($y=0; $y < $num_products; $y++)
    {
      $productid = $pid_listA[$y];
      if (isset($carton_instanceA[$main_result[$i]['clientid']][$productid]))
      {
        echo d_td($c_oA[$main_result[$i]['clientid']][$productid].'&nbsp;('
        .$carton_instanceA[$main_result[$i]['clientid']][$productid].')','right');
        $totalA[$productid] += $carton_instanceA[$main_result[$i]['clientid']][$productid];
      }
      elseif (isset($c_oA[$main_result[$i]['clientid']][$productid]))
      {
        if ($p_npuA[$productid] > 1)
        {
          $temp = floor($c_oA[$main_result[$i]['clientid']][$productid] / $p_npuA[$productid]);
          if ($c_oA[$main_result[$i]['clientid']][$productid] % $p_npuA[$productid] > 0)
          {
            $temp .= ' <font size=-1>'.$c_oA[$main_result[$i]['clientid']][$productid] % $p_npuA[$productid].'/<font>';
          }
          if ($_SESSION['ds_customname'] == 'Wing Chong' && $p_unittypeidA[$productid] == 9) { $temp .= ' u'; }
          echo d_td_unfiltered($temp,'right');
        }
        else
        {
          if ($_SESSION['ds_customname'] == 'Wing Chong' && $p_unittypeidA[$productid] == 9)
          { echo d_td_unfiltered($c_oA[$main_result[$i]['clientid']][$productid].' u','right'); }
          else { echo d_td($c_oA[$main_result[$i]['clientid']][$productid],'decimal'); }
        }
        $totalA[$productid] += $c_oA[$main_result[$i]['clientid']][$productid];
      }
      else { echo d_td(); }
    }
  }
}
echo d_tr(1),d_td('TOTAUX','emphasis center',2);
for ($y=0; $y < $num_products; $y++)
{
  $productid = $pid_listA[$y];
  if ($p_npuA[$productid] > 1)
  {
    $temp = floor($totalA[$productid] / $p_npuA[$productid]);
    if ($totalA[$productid] % $p_npuA[$productid] > 0)
    {
      $temp .= ' <font size=-1>'.$totalA[$productid] % $p_npuA[$productid].'/<font>';
    }
    if ($_SESSION['ds_customname'] == 'Wing Chong' && $p_unittypeidA[$productid] == 9) { $temp .= ' u'; }
    echo d_td_unfiltered($temp,'right');
  }
  else
  {
    if ($_SESSION['ds_customname'] == 'Wing Chong' && $p_unittypeidA[$productid] == 9)
    { echo d_td($totalA[$productid].' u','right'); }
    else { echo d_td($totalA[$productid],'decimal'); }
  }
}
?>