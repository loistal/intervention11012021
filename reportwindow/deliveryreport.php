<?php

require('preload/companytransport.php');
require('preload/employee.php');

$linenr = 0; $numinvoices = 0; $lastig = -1;
$linepart1 = ''; $linepart2 = ''; $tableA = array();
$employee_invoicesA = array(); $employee_listedA = array();

$PA['findinvoiceid'] = 'int';
$PA['userid'] = 'int';
$PA['companytransportid'] = 'int';
$PA['bynumber'] = 'int';
$PA['startid'] = 'int';
$PA['stopid'] = 'int';
$PA['invgroupstart'] = 'date';
$PA['invgroupstop'] = 'date';
require('inc/readpost.php');
$date = $invgroupstart;
$datestop = $invgroupstop;

$client = $_POST['client'];
require('inc/findclient.php');

if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1)
{
  $ourtitle = 'Réception de ';
}
else
{
  $ourtitle = 'Livraisons de ';
}
if ($bynumber) { $ourtitle .=  $startid  . ' à ' . $stopid; }
else { $ourtitle .=  datefix2($date) . ' à ' . datefix2($datestop); }
showtitle($ourtitle);
echo '<h2>' .$ourtitle. '</h2>';

if ($num_clients == 1)
{
  echo '<p><b>Client:</b> '.$clientname.' ('.$clientid.')</p>';
}
if ($findinvoiceid > 0)
{
  echo '<p><b>Facture:</b> '.$findinvoiceid . '</p>';
}


$query = 'select islandname,townname,clientname,invoicehistory.clientid,totalweight,companytransportid,invoicegroupdate
,invoicegroup.invoicegroupid,initials,invoiceid,preparationtext,invoicegroup.employeeid
from invoicegroup,usertable,invoicehistory,client,town,island
where invoicehistory.invoicegroupid=invoicegroup.invoicegroupid and invoicegroup.userid=usertable.userid and invoicehistory.clientid=client.clientid
and client.townid=town.townid and town.islandid=island.islandid';
if ($bynumber) { $query .= ' and invoicegroup.invoicegroupid>="' . $startid . '" and invoicegroup.invoicegroupid<="' . $stopid . '"'; }
else { $query = $query . ' and invoicegroupdate>="' . $date . '" and invoicegroupdate<="' . $datestop . '"'; }
if ($num_clients == 1) { $query = $query . ' and invoicehistory.clientid="'.$clientid.'"'; }
if ($findinvoiceid > 0) { $query = $query . ' and invoicehistory.invoiceid="'.$findinvoiceid.'"'; }
if ($userid > 0) { $query .= ' and invoicegroup.userid="'.$userid.'"'; }
if ($companytransportid >= 0) { $query .= ' and invoicegroup.companytransportid="'.$companytransportid.'"'; }
if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1) { $query .= ' and isreturn=1'; }
if ($_SESSION['ds_deliveryaccessinvoices'] == 1 && $_SESSION['ds_deliveryaccessreturns'] == 0) { $query .= ' and isreturn=0'; }
$query .= ' order by invoicegroupid,townname,clientname,invoiceid'; # 2014 07 01 as asked in email by Cindy 2017 05 23 added townname as per request
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i <= $num_results; $i++) # should be <, but that bugs it... so when too much time on your hands: refactor
{
  $row = $query_result[$i];
  if ($i != 0 && $row['invoicegroupid'] != $lastig)
  {
    $tableA[$linenr] = $linepart1 . $numinvoices . '<td class="breakme">' . $linepart2;
    $linepart1 = ''; $linepart2 = '';
    $linenr++; $numinvoices = 0;
  }
  if ($i == 1 || $row['invoicegroupid'] != $lastig)
  {
    $pt = $row['preparationtext']; if ($pt == "") { $pt = '&nbsp;'; }
    $linepart1 = '<tr><td align=right><a href="reportwindow.php?report=deliverylist&invoicegroupid=' . $row['invoicegroupid']
    . '" target=_blank>' . $row['invoicegroupid'] . '</a></td>
    <td>' . d_output($row['islandname']) . '</td><td>' . d_output($row['townname']) . '</td>
    <td>' . datefix2($row['invoicegroupdate']) . '</td><td>' . $row['initials'] . '</td>
    <td>';
    if (isset($companytransportA[$row['companytransportid']])) { $linepart1 .= $companytransportA[$row['companytransportid']]; }
    $linepart1 .= '<td>';
    if (isset($employeeA[$row['employeeid']])) { $linepart1 .= $employeeA[$row['employeeid']]; }
    $linepart1 .= '</td><td align=right>' . myfix($row['totalweight']/1000) . '</td><td>' . $pt . '</td><td align=right>';
  }
  $linepart2 .= '<a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid=' . $row['invoiceid'] . '" target=_blank>'
  .$row['invoiceid'] . '</a> ';
  $lastig = $row['invoicegroupid'];
  if ($row['employeeid'] > 0)
  {
    $employee_listedA[] = $row['employeeid'];
    $employee_invoicesA[$row['employeeid']][] = $row['invoiceid'];
  }
  $numinvoices++;
}

echo '<table class="report"><thead><tr><td>Livraison</td><td>Île</td><td>Commune</td>';
echo '<td>Date</td><td>Crée par</td><td>Transport<td>Livreur<td>KG</td><td>Info</td><td><b>#<td>Factures</td></tr></thead>';
for ($i=0; $i < $linenr; $i++)
{
  echo $tableA[$i];
}
echo '</table><br>';

$employee_listedA = array_unique($employee_listedA);

echo 'Afficher factures pour :';
foreach ($employee_listedA as $employeeid)
{
  echo ' &nbsp;
  <a href="reportwindow.php?report=showinvoices&invoice_list=';
  $kladd = '';
  foreach ($employee_invoicesA[$employeeid] as $invoiceid)
  {
    $kladd .= '|'.$invoiceid;
  }
  echo ltrim($kladd,'|');
  echo '" target=_blank>'.$employeeA[$employeeid].'</a>';
}

#var_dump($employee_invoicesA[122]);

?>