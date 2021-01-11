<?php

require('preload/employee.php');

$curdate = $_SESSION['ds_curdate'];
echo '<title>Retards des paiements ' . datefix($curdate) . '</title>';
echo '</HEAD><BODY text=#000000 vLink=#003399 aLink=#ff9933 link=#003399 bgColor=#ffffff>';
echo '<h2>Retards des paiements ' . datefix($curdate) . '</h2>';
$employeeid = (int) $_POST['employee1id'];
$employeeid2 = (int) $_POST['employee2id'];
$islandid = (int) $_POST['islandid'];

$query = 'select client.employeeid,employeeid2,paybydate,invoiceid,accountingdate,invoiceprice as totalprice,
client.clientid as clientid,clientname,to_days(curdate())-to_days(paybydate) as dayspast
from invoicehistory,client';
if ($islandid > 0) { $query .= ',town'; }
$query .= ' where invoicehistory.clientid=client.clientid
and matchingid=0 and curdate()>=paybydate and isnotice=0 and cancelledid=0 and confirmed=1 and isreturn=0';
if ($employeeid > 0) { $query .= ' and client.employeeid="'.$employeeid.'"'; }
if ($employeeid2 > 0) { $query .= ' and client.employeeid2="'.$employeeid2.'"'; }
if ($islandid > 0) { $query .= ' and client.townid=town.townid and islandid="'.$islandid.'"'; }
$query = $query . ' order by clientname,clientid,dayspast desc';

$subtotal = 0; $grandtotal = 0; $lastclientid = -1;
$query_prm = array();
require('inc/doquery.php');
echo '<table class="report"><tr><td><b>Client</b></td><td><b>'.$_SESSION['ds_term_clientemployee1'].'</b></td><td><b>'.$_SESSION['ds_term_clientemployee2'].'</b></td><td><b>N<superscript>o<superscript> Facture</b></td><td><b>Date</b></td><td><b>Montant</b></td><td><b>Date paiement</b></td><td><b>Jours depassés</b></td></tr>';
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  if ($row['clientid'] != $lastclientid && $i != 0)
  {
    echo '<tr><td colspan=4><b>Total client</b></td><td align=right><b>' . myfix($subtotal) . '</td><td colspan=2>&nbsp;</td></tr>';
    $grandtotal = $grandtotal + $subtotal;
    $subtotal = 0;
  }
  $clientname = '<b>' . $row['clientid'] . ': ' . d_decode($row['clientname']) . '</b>';
  if (isset($employeeA[$row['employeeid']])) { $employeename = $employeeA[$row['employeeid']]; } else { $employeename = ''; }
  if (isset($employeeA[$row['employeeid2']])) { $employeename2 = $employeeA[$row['employeeid2']]; } else { $employeename2 = ''; }
  if ($row['clientid'] == $lastclientid)
  {
    $clientname = $employeename = $employeename2 = "&nbsp;";
  }
  echo '<tr><td>' . $clientname . '</td><td>' . $employeename . '</td><td>' . $employeename2 . '</td><td align=right>' . $row['invoiceid'] . '</td><td>' . datefix($row['accountingdate']) . '</td><td align=right>' . myfix($row['totalprice']) . '</td><td align=right>' . datefix($row['paybydate']) . '</td><td align=right>' . $row['dayspast'] . '</td></tr>';
  $subtotal = $subtotal + $row['totalprice'];
  $lastclientid = $row['clientid'];
}
echo '<tr><td colspan=5><b>Total ' . $clientname . '</b></td><td align=right><b>' . myfix($subtotal) . '</td><td colspan=2>&nbsp;</td></tr>';
echo '<tr><td colspan=8>&nbsp;</td></tr>';
$grandtotal = $grandtotal + $subtotal;
echo '<tr><td colspan=5><b>Total Encaissements dépassées</b></td><td align=right><b>' . myfix($grandtotal) . '</td><td colspan=2>&nbsp;</td></tr>';
echo '</table>';
?>