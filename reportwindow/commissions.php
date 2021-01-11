<?php

# 2016 11 03 took off group by, query seems to run better

require('preload/employee.php');
require('preload/employeecategory.php');
require('preload/commissionrate.php'); $num_commissionrate = $num_results;
require('preload/clientcategory.php');
require('preload/town.php');

$postemployeeid = (int) $_POST['employeeid'];
$employeecategoryid = (int) $_POST['employeecategoryid'];
$excludesupplier = (int) $_POST['excludesupplier'];
$supplierid = (int) $_POST['supplierid'];
$datename = 'startdate'; require('inc/datepickerresult.php');
$datename = 'stopdate'; require('inc/datepickerresult.php');

$title = 'Commissions ' . datefix2($startdate) . ' à ' . datefix2($stopdate);
showtitle($title);
echo '<h2>' . $title . '</h2>';
if ($postemployeeid == 0) { echo '<p>Employé: &lt;Vide&gt;</p>'; }
if ($postemployeeid > 0) { echo '<p>Employé: ' . d_output($employeeA[$postemployeeid]) . '</p>'; }
if ($employeecategoryid == 0) { echo '<p>Catégorie employé: &lt;Vide&gt;</p>'; }
if ($employeecategoryid > 0) { echo '<p>Catégorie employé: ' . d_output($employeecategoryA[$employeecategoryid]) . '</p>'; }
if ($supplierid > 0)
{
  echo '<p>Fournisseur: ' . $supplierid;
  if ($excludesupplier) { echo ' exclu'; }
  echo '</p>';
}

$query = 'select invoiceitemhistory.lineprice,commissionrateid,invoicehistory.employeeid,invoicehistory.clientid,clientname,townid,clientcategoryid,isreturn
from product,invoiceitemhistory,invoicehistory,client';
if ($employeecategoryid> -1) { $query .= ',employee'; }
$query .= ' where invoiceitemhistory.productid=product.productid and invoiceitemhistory.invoiceid=invoicehistory.invoiceid and invoicehistory.clientid=client.clientid
and cancelledid=0 and confirmed=1 and accountingdate>=? and accountingdate<=?';
$query_prm = array($startdate,$stopdate);
if ($supplierid > 0)
{
  if ($excludesupplier) { $query = $query . ' and supplierid<>?'; array_push($query_prm,$supplierid); }
  else { $query = $query . ' and supplierid=?'; array_push($query_prm,$supplierid); }
}
if ($postemployeeid> -1) { $query .= ' and invoicehistory.employeeid=?'; array_push($query_prm,$postemployeeid); }
if ($employeecategoryid> -1) { $query .= ' and invoicehistory.employeeid=employee.employeeid and employee.employeecategoryid=?'; array_push($query_prm,$employeecategoryid); }
$query .=' order by client.clientcategoryid,clientname,isreturn,commissionrateid';
require('inc/doquery.php');
for ($i=0;$i < $num_results; $i++)
{
  $row = $query_result[$i];
  $clientid[$i] = $row['clientid'];
  $isreturn[$i] = $row['isreturn']+0;
  $clientname[$i] = d_decode($row['clientname']);
  $clientcategoryid[$i] = $row['clientcategoryid'];
  $townid[$i] = $row['townid'];
  $lineprice_temp = $row['lineprice']+0; if ($row['isreturn'] == 1) { $lineprice_temp = 0 - $lineprice_temp; }
  $lineprice[$i] = $lineprice_temp;
  $clienttotal[$clientid[$i]][$row['commissionrateid']] += $lineprice_temp;
  $grandtotal = $grandtotal + $lineprice_temp;
}
echo '<table class="report"><thead><tr><th>Compte</th><th>Client</th><th>Commune</th>';
foreach ($commissionrateA as $commissionrateid => $commissionrate)
{
  echo '<th align=right>' . $commissionrate . '%</th>';
  $commissiontotalA[$commissionrateid] = 0;
  $commissionsubtotalA[$commissionrateid] = 0;
}
echo '<th>Commission</th></tr></thead><tbody>';

$commissiontotal = 0; $commissionsubtotal = 0; $lastclientid = -1;
for ($i=0;$i < $num_results; $i++)
{
  if ($clientcategoryid[$i] != $lastclientcategoryid && $i != 0)
  {
    echo '<tr><td colspan=3><b>' . $clientcategoryA[$lastclientcategoryid];
    foreach ($commissionrateA as $commissionrateid => $commissionrate)
    {
      echo '<td align=right><b>' . myfix($commissionsubtotalA[$commissionrateid]) . '</td>';
      $commissionsubtotalA[$commissionrateid] = 0;
    }
    echo '<td align=right><b>' . myfix($commissionsubtotal) . '</td></tr>';
    $commissionsubtotal = 0;
  }
  if ($clientid[$i] != $lastclientid)
  {
    echo '<tr><td align=right>' . $clientid[$i] . '</td><td>' . d_output($clientname[$i]) . '</td><td>' . $townA[$townid[$i]] . '</td>';
    $linetotal = 0;
    foreach ($commissionrateA as $commissionrateid => $commissionrate)
    {
      echo '<td align=right>' . myfix($clienttotal[$clientid[$i]][$commissionrateid]) . '</td>';
      $kladd = $clienttotal[$clientid[$i]][$commissionrateid];
      $commissiontotalA[$commissionrateid] += $kladd;
      $commissionsubtotalA[$commissionrateid] += $kladd;
      $linetotal += $kladd * ($commissionrate/100);
    }
    echo '<td align=right>' . myfix($linetotal) . '</td></tr>';
    $commissiontotal += $linetotal;
    $commissionsubtotal += $linetotal;
  }
  $lastclientid = $clientid[$i];
  $lastclientcategoryid = $clientcategoryid[$i];
}

###copy subtotal from above
      echo '<tr><td colspan=3><b>' . $clientcategoryA[$lastclientcategoryid];
    foreach ($commissionrateA as $commissionrateid => $commissionrate)
    {
      echo '<td align=right><b>' . myfix($commissionsubtotalA[$commissionrateid]) . '</td>';
      $commissionsubtotalA[$commissionrateid] = 0;
    }
    echo '<td align=right><b>' . myfix($commissionsubtotal) . '</td></tr>';
    $commissionsubtotal = 0;
###

echo '</tbody><tfoot><tr><td><b>Total</b><td colspan=2>&nbsp;</td>';
$totaloverzero = 0;
foreach ($commissionrateA as $commissionrateid => $commissionrate)
{
  if ($commissionrate > 0) { $totaloverzero += $commissiontotalA[$commissionrateid]; }
  echo '<td align=right><b>' . myfix($commissiontotalA[$commissionrateid]) . '</td>';
}
echo '<td align=right><b>' . myfix($commissiontotal) . '</b></td></tr>';
echo '<tr><td colspan=7>CA hors 0%</td><td align=right>' . myfix($totaloverzero) . '</td></tr>';
echo '</tfoot></table>';
?>