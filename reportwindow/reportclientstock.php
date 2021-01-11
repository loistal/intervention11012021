<?php

set_time_limit(3600);

$PA['productid'] = 'int';
$PA['from_clientid'] = 'int';
$PA['to_clientid'] = 'int';
require('inc/readpost.php');

if (isset($_POST['productiddirect']) && $_POST['productiddirect'] != "") { $productid = $_POST['productiddirect']; }
$query = 'select productname,suppliercode,product.productid,stockdate,currentstock,currentstockrest,margin,numberperunit,netweightlabel
,product.unittypeid as unittypeid,unittypename,productname,weight
from product,unittype where product.unittypeid=unittype.unittypeid';
if ($_SESSION['ds_useproductcode'] == 1 && $_POST['productiddirect'] != "")
{ $query .= ' and suppliercode like "%' . $productid . '%" order by suppliercode limit 1'; }
else { $query = $query . ' and productid=?'; }
$query_prm = array($productid);
require('inc/doquery.php');
if ($num_results == 0) { echo 'Produit inexistant.'; exit; }
$row = $query_result[0];
$productid = $row['productid'];
$productname = $row['productname'] . ' ';
$numberperunit = $row['numberperunit'];
if ($_SESSION['ds_useunits'] && $numberperunit > 1) { $productname = $productname . $numberperunit . ' x '; }
$productname = $productname . $row['netweightlabel'];
$unittypename = $row['unittypename'];

echo '<h2>Stock clients ' . datefix2($_SESSION['ds_curdate']) . ' pour produit ' . $productid . ': ' . $productname . '</h2>';
echo '<table class="report" border=1 cellpadding=1 cellspacing=1><tr><td><b>Client</td><td><b>Stock</td></tr>';
$total = 0;

### optimise
$query = 'select stockmod,clientid,csmdate
from clientstockmod where stockmod>0 and productid=? and iscount=1 and csmdate>"1990-01-01"';
if ($from_clientid >= 0 && $to_clientid > 0) { $query .= ' and clientid>='.$from_clientid.' and clientid<='.$to_clientid; }
$query_prm = array($productid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $countedstockA[$query_result[$i]['clientid']] = $query_result[$i]['stockmod'];
  $counteddateA[$query_result[$i]['clientid']] = $query_result[$i]['csmdate'];
}

$query = 'select sum(quantity) as sales,clientid
from invoicehistory,invoiceitemhistory
where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and productid=?
 and isnotice=0 and cancelledid=0 and proforma=0 and isreturn=0 and confirmed=1
 group by clientid';
$query_prm = array($productid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $salesA[$query_result[$i]['clientid']] = $query_result[$i]['sales'];
}

$query = 'select sum(quantity) as returncount,clientid
from invoicehistory,invoiceitemhistory
where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and productid=?
 and isnotice=0 and cancelledid=0 and proforma=0 and isreturn=1 and confirmed=1 and returntostock=1
 group by clientid';
$query_prm = array($productid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $returnsA[$query_result[$i]['clientid']] = $query_result[$i]['returncount'];
}

$query = 'select sum(stockmod) as adjust,clientid
from clientstockmod where productid=?';
$query_prm = array($productid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $adjustA[$query_result[$i]['clientid']] = $query_result[$i]['adjust'];
}
###

$queryX = 'select clientid,clientname from client';
if ($_POST['filtertype'] == 1)
{
  $queryX = $queryX . ' where clientcategoryid="' . $_POST['clientcategoryid'] . '"';
  $query = 'select clientcategoryname from clientcategory where clientcategoryid=?';
  $query_prm = array($_POST['clientcategoryid']);
  require('inc/doquery.php');
  echo '<p>Catégorie client: ' . $query_result[0]['clientcategoryname'] . '</p>';
}
if ($_POST['filtertype'] == 2)
{
  $queryX = $queryX . ',town where client.townid=town.townid and islandid="' . $_POST['islandid'] . '"';
  $query = 'select islandname from island where islandid=?';
  $query_prm = array($_POST['islandid']);
  require('inc/doquery.php');
  echo '<p>Île: ' . $query_result[0]['islandname'] . '</p>';
}
if ($_POST['nodeleted'] == 1) { $queryX = $queryX . ' and client.deleted=0'; }
if ($_POST['nodeleted'] == 2) { $queryX = $queryX . ' and client.deleted=1'; }
if ($from_clientid >= 0 && $to_clientid > 0)
{
  $queryX .= ' and client.clientid>='.$from_clientid.' and client.clientid<='.$to_clientid;
}
$queryX = $queryX . ' order by clientname,clientid';
$query = $queryX;
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{
  $rowX = $main_result[$i];
  $clientid = $rowX['clientid'];
  $clientname = $rowX['clientid'] . ': ' . $rowX['clientname'];

  if (isset($counteddateA[$clientid]))
  {
    $counteddate = $counteddateA[$clientid];

    $query = 'select sum(quantity) as sales from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and clientid="' . $clientid . '" and productid="' . $productid . '" and isnotice=0 and cancelledid=0 and proforma=0 and isreturn=0 and accountingdate>"' . $counteddate . '"';
    $query_prm = array();
    require('inc/doquery.php');
    $sales = $query_result[0]['sales'];

    $query = 'select sum(quantity) as returncount from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and clientid="' . $clientid . '" and productid="' . $productid . '" and isnotice=0 and cancelledid=0 and proforma=0 and isreturn=1 and returntostock=1 and accountingdate>"' . $counteddate . '"';
    $query_prm = array();
    require('inc/doquery.php');
    $returns = $query_result[0]['returncount'];

    $query = 'select sum(stockmod) as adjust from clientstockmod where clientid="' . $clientid . '" and productid="' . $productid . '" and csmdate>"' . $counteddate . '"';
    $query_prm = array();
    require('inc/doquery.php');
    $adjust = $query_result[0]['adjust'];

    $currentstock = $countedstockA[$clientid] + $sales - $returns + $adjust;
    
  }
  else
  {
    # use optimise tables
    # $currentstock = $countedstock + $sales - $returns + $adjust;
    $currentstock = 0;
    if (isset($salesA[$clientid])) { $currentstock = $salesA[$clientid]; }
    if (isset($returnsA[$clientid])) { $currentstock -= $returnsA[$clientid]; }
    if (isset($adjustA[$clientid])) { $currentstock += $adjustA[$clientid]; }    
  }
  
  if ($currentstock > 0)
  {
    $currentstock = floor(abs($currentstock) / $numberperunit);
    echo '<tr><td>' . d_output(d_decode($clientname)) . '</td><td align=right>' . $currentstock . '</td></tr>';
    $total += $currentstock;
  }
}
echo '<tr><td><b>Total</td><td align=right><b>' . $total . '</td></tr>';
echo '</table>';
  
?>