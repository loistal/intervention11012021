<?php
require('preload/user.php');
$datename = 'invgroupstart'; require('inc/datepickerresult.php'); $date = $invgroupstart;
$datename = 'invgroupstop'; require('inc/datepickerresult.php'); $datestop = $invgroupstop;

$findinvoiceid = (int) $_POST['invoiceid'];
require('inc/findclient.php');

$ourtitle = 'Relivraisons de ' . datefix2($date) . ' à ' . datefix2($datestop);
showtitle($ourtitle);
echo '<h2>' .$ourtitle. '</h2>';
if ($clientid > 0) { echo '<p>Client '.$clientname.' ('.$clientid.')</p>'; }

if ($findinvoiceid > 0)
{
  echo '<p><b>Facture:</b> '.$findinvoiceid . '</p>';
}


$query = 'select * from redeliverlog';
if ($clientid > 0) { $query .= ',invoicehistory'; }
$query = $query . ' where redeliverdate>=? and redeliverdate<=?';
$query_prm = array($date,$datestop);
if ($clientid > 0) { $query = $query . ' and redeliverlog.invoiceid=invoicehistory.invoiceid and clientid=?'; array_push($query_prm,$clientid); }
if ($findinvoiceid > 0) { $query = $query . ' and invoiceid=?'; array_push($query_prm,$findinvoiceid); }
$query = $query . ' order by redeliverdate,redelivertime';
require('inc/doquery.php');
echo '<table class=report><tr><td><b>Facture</td><td><b>Date</td><td><b>Heure</td><td><b>Relivré par</td><td><b>Infos</td><td><b>Nouvelle date livraison</td></tr>';
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0;$i<$num_results_main;$i++)
{
  echo '<tr><td align=right>'.$main_result[$i]['invoiceid'].'</td><td align=right>'.datefix2($main_result[$i]['redeliverdate']).'</td><td align=right >'.$main_result[$i]['redelivertime'].'</td>';
  $userid = $main_result[$i]['userid'];
  echo '<td>'.$userA[$userid].'</td><td>'.d_output($main_result[$i]['redelivercomment']).'</td><td align=right>'.datefix2($main_result[$i]['deliverydate']).'</td></tr>';
}
echo '</table>';
?>