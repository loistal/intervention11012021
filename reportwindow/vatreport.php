<?php

$datename = 'startdate'; require('inc/datepickerresult.php');
$datename = 'stopdate'; require('inc/datepickerresult.php');
$orderby = (int) $_POST['orderby'];
$invoicetagid = (int) $_POST['invoicetagid'];
$exclude_invoicetag = (int) $_POST['exclude_invoicetag'];
$showlines = 1; if (isset($_POST['onlytotal']) && $_POST['onlytotal'] = 1) { $showlines = 0; }

require('preload/taxcode.php');
foreach ($taxcodeA as $taxcodeid => $taxcode)
{
  $showtaxcode[$taxcodeid] = 0;
}

$scrap = d_output($_SESSION['ds_customname']) . ' TVA collectée (sur factures)';
showtitle($scrap);
echo '<h2>' . $scrap . '</h2>';
echo '<h2>' . datefix($startdate) . ' à ' . datefix($stopdate) . '</h2>';
echo '<p>Executé le ' . datefix2($_SESSION['ds_curdate']) . '</p>';

$query = 'select invoiceprice,clientname,client.clientid,invoiceitemid,accountingdate,invoicehistory.invoiceid,linevat,lineprice,taxcodeid,linetaxcodeid
from invoicehistory,invoiceitemhistory,product,client
where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and invoiceitemhistory.productid=product.productid and invoicehistory.clientid=client.clientid
and exludefromvatreport=0 and isnotice=0 and isreturn=0 and cancelledid=0 and confirmed=1 and accountingdate>=? and accountingdate<=?';
$query_prm = array($startdate, $stopdate);
if ($invoicetagid > -1)
{
  if ($exclude_invoicetag == 1)
  {
    $query .= ' and invoicetagid<>?';
  }
  else
  {
    $query .= ' and invoicetagid=?';
  }
  array_push($query_prm, $invoicetagid);
}
if ($orderby == 1) { $query .= ' order by invoiceid,invoiceitemid'; }
else { $query .= ' order by accountingdate,invoiceid,invoiceitemid'; }
require('inc/doquery.php');
$main_result = $query_result; unset($query_result); $num_results_main = $num_results;

for ($i=0;$i<$num_results_main;$i++)
{
  $testid = $main_result[$i]['linetaxcodeid'];
  if ($testid == 0) { $testid = $main_result[$i]['taxcodeid']; }
  if ($testid == 59999) { $testid = 1; }
  $usetaxcodeid[$i] = $testid;
  $showtaxcode[$testid] = 1;
}

echo '<table class="report"><thead><tr><th>Facture</th><th>Date</th><th>Client</th>';
foreach ($taxcodeA as $taxcodeid => $taxcode)
{
  if ($showtaxcode[$taxcodeid])
  {
    echo '<th>HT&nbsp;' . $taxcode . '%</th>';
    if ($taxcode > 0) { echo '<th>TVA&nbsp;' . $taxcode . '%</th>'; }
  }
}
echo '<th>Facture&nbsp;TTC</th></tr></thead>';

$lastinvoiceid = -1; unset($subt_base,$subt_vat,$total_base,$total_vat,$total);
for ($i=0;$i<=$num_results_main;$i++)
{
  if ($main_result[$i]['invoiceid'] != $lastinvoiceid && $i != 0)
  {
    if ($showlines)
    {
      echo '<tr><td align=right>' . $main_result[$i-1]['invoiceid'] . '</td><td>' . datefix2($main_result[$i-1]['accountingdate']) . '</td><td>' . d_output(d_decode($main_result[$i-1]['clientname'])) . '</td>';
      foreach ($taxcodeA as $taxcodeid => $taxcode)
      {
        if ($showtaxcode[$taxcodeid])
        {
          echo '<td align=right>' . myfix($subt_base[$taxcodeid]) . '</td>';
          if ($taxcode > 0) { echo '<td align=right>' . myfix($subt_vat[$taxcodeid]) . '</td>'; }
        }
      }
      echo '<td align=right>' . myfix($main_result[$i-1]['invoiceprice']) . '</td></tr>';
    }
    $total += $main_result[$i-1]['invoiceprice'];
    unset($subt_base,$subt_vat);
  }
  if ($i != $num_results_main)
  {
    $subt_base[$main_result[$i]['linetaxcodeid']] += $main_result[$i]['lineprice'];
    $total_base[$main_result[$i]['linetaxcodeid']] += $main_result[$i]['lineprice'];
    $subt_vat[$main_result[$i]['linetaxcodeid']] += $main_result[$i]['linevat'];
    $total_vat[$main_result[$i]['linetaxcodeid']] += $main_result[$i]['linevat'];
    $lastinvoiceid = $main_result[$i]['invoiceid'];
  }
}
echo '<tfoot><tr><td colspan=3>Total</td>';
foreach ($taxcodeA as $taxcodeid => $taxcode)
{
  if ($showtaxcode[$taxcodeid])
  {
    echo '<td align=right>' . myfix($total_base[$taxcodeid]) . '</td>';
    if ($taxcode > 0) { echo '<td align=right>' . myfix($total_vat[$taxcodeid]) . '</td>'; }
  }
}
echo '<td align=right>' . myfix($total) . '</td></tr></tfoot>';
echo '</table>';

#returns, copy/edit from above
$query = 'select invoiceprice,clientname,client.clientid,invoiceitemid,accountingdate,invoicehistory.invoiceid,linevat,lineprice,taxcodeid,linetaxcodeid
from invoicehistory,invoiceitemhistory,product,client
where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and invoiceitemhistory.productid=product.productid and invoicehistory.clientid=client.clientid
and exludefromvatreport=0 and isnotice=0 and proforma=0 and isreturn=1 and cancelledid=0 and confirmed=1 and accountingdate>=? and accountingdate<=?';
$query_prm = array($startdate, $stopdate);
if ($invoicetagid > -1)
{
  if ($exclude_invoicetag == 1)
  {
    $query .= ' and invoicetagid<>?';
  }
  else
  {
    $query .= ' and invoicetagid=?';
  }
  array_push($query_prm, $invoicetagid);
}
if ($orderby == 1) { $query .= ' order by invoiceid,invoiceitemid'; }
else { $query .= ' order by accountingdate,invoiceid,invoiceitemid'; }
require('inc/doquery.php');
$main_result = $query_result; unset($query_result); $num_results_main = $num_results;

for ($i=0;$i<$num_results_main;$i++)
{
  $testid = $main_result[$i]['linetaxcodeid'];
  if ($testid == 0) { $testid = $main_result[$i]['taxcodeid']; }
  if ($testid == 59999) { $testid = 1; }
  $usetaxcodeid[$i] = $testid;
  $showtaxcode[$testid] = 1;
}

echo '<br><table class="report"><thead><tr><th>Avoir</th><th>Date</th><th>Client</th>';
foreach ($taxcodeA as $taxcodeid => $taxcode)
{
  if ($showtaxcode[$taxcodeid])
  {
    echo '<th>HT&nbsp;' . $taxcode . '%</th>';
    if ($taxcode > 0) { echo '<th>TVA&nbsp;' . $taxcode . '%</th>'; }
  }
}
echo '<th>Avoir&nbsp;TTC</th></tr></thead>';

$lastinvoiceid = -1; unset($subt_base,$subt_vat,$totalR_base,$totalR_vat,$totalR);
for ($i=0;$i<=$num_results_main;$i++)
{
  if ($main_result[$i]['invoiceid'] != $lastinvoiceid && $i != 0)
  {
    if ($showlines)
    {
      echo '<tr><td align=right>' . $main_result[$i-1]['invoiceid'] . '</td><td>' . datefix2($main_result[$i-1]['accountingdate']) . '</td><td>' . d_output(d_decode($main_result[$i-1]['clientname'])) . '</td>';
      foreach ($taxcodeA as $taxcodeid => $taxcode)
      {
        if ($showtaxcode[$taxcodeid])
        {
          echo '<td align=right>' . myfix($subt_base[$taxcodeid]) . '</td>';
          if ($taxcode > 0) { echo '<td align=right>' . myfix($subt_vat[$taxcodeid]) . '</td>'; }
        }
      }
      echo '<td align=right>' . myfix($main_result[$i-1]['invoiceprice']) . '</td>';
    }
    $totalR += $main_result[$i-1]['invoiceprice'];
    unset($subt_base,$subt_vat);
  }
  if ($i != $num_results_main)
  {
    $subt_base[$main_result[$i]['linetaxcodeid']] += $main_result[$i]['lineprice'];
    $totalR_base[$main_result[$i]['linetaxcodeid']] += $main_result[$i]['lineprice'];
    $subt_vat[$main_result[$i]['linetaxcodeid']] += $main_result[$i]['linevat'];
    $totalR_vat[$main_result[$i]['linetaxcodeid']] += $main_result[$i]['linevat'];
    $lastinvoiceid = $main_result[$i]['invoiceid'];
  }
}
echo '<tfoot><tr><td colspan=3>Total</td>';
foreach ($taxcodeA as $taxcodeid => $taxcode)
{
  if ($showtaxcode[$taxcodeid])
  {
    echo '<td align=right>' . myfix($totalR_base[$taxcodeid]) . '</td>';
    if ($taxcode > 0) { echo '<td align=right>' . myfix($totalR_vat[$taxcodeid]) . '</td>'; }
  }
}
echo '<td align=right>' . myfix($totalR) . '</td></tr></tfoot>';
echo '</table>';

$topay = 0;
echo '<br><table class="report"><thead><tr><td colspan=2>TVA à payer</td></tr></thead>';
foreach ($taxcodeA as $taxcodeid => $taxcode)
{
  if ($showtaxcode[$taxcodeid])
  {
    echo '<tr><td>HT&nbsp;' . $taxcode . '%</td><td align=right>' . myfix($total_base[$taxcodeid] - $totalR_base[$taxcodeid]) . '</td></tr>';
    if ($taxcode > 0)
    {
      echo '<tr><td>TVA&nbsp;' . $taxcode . '%</td><td align=right>' . myfix($total_vat[$taxcodeid] - $totalR_vat[$taxcodeid]) . '</td>';
      $topay += $total_vat[$taxcodeid] - $totalR_vat[$taxcodeid];
    }
  }
}
echo '<tfoot><tr><td colspan=2 align=right>' . myfix($topay) . '</td></tr></tfoot></table>';

?>