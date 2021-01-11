<?php

require('preload/employee.php');
require('preload/island.php');
require('preload/town.php');
require('preload/clientcategory.php');
require('preload/clientcategory2.php');
require('preload/clientterm.php');

$datename = 'ourdate'; require('inc/datepickerresult.php');
$townid = $_POST['townid']+0;
$islandid = $_POST['islandid']+0;
$employeeid = $_POST['employeeid']+0;
$employeeid2 = $_POST['employee2id']+0;
$clientcategoryid = $_POST['clientcategoryid']+0;
$clientcategory2id = $_POST['clientcategory2id']+0;
$clienttermid = $_POST['clienttermid']+0;
$islandsort = (int) $_POST['islandsort'];

$PA['months24'] = 'uint';
$PA['datetype'] = 'uint';
require('inc/readpost.php');
if ($datetype == 1) { $datename = 'accountingdate'; }
else { $datename = 'paybydate'; }
if ($months24 == 1) { $months = 24; }
elseif ($months24 == 2) { $months = 48; }
else { $months = 0; }

$num_clients = 0; $totalm0 = 0; $totalm1 = 0; $totalm2 = 0; $totalm3 = 0; $totalm4 = 0; $grandtotal = 0; $tpaid = 0;

echo '<TITLE>Balance Âgée au ' . datefix2($ourdate) . '</TITLE>';
echo '<h2>Balance Âgée au ' . datefix2($ourdate) . '</h2>';

$matchingid = 0;
$query = 'select max(matchingid) as mid from matching where date<=?';
$query_prm = array($ourdate);
require('inc/doquery.php');
if ($num_results) { $matchingid = $query_result[0]['mid']; }

$query = 'select clientid,clientname,client.employeeid,islandname,customorder';
if ($islandsort == 0) { $query .= ',employeename'; }
$query .= ' from client
join town on client.townid=town.townid
join island on town.islandid=island.islandid';
if ($islandsort == 0) { $query .= ' left join employee on client.employeeid=employee.employeeid'; }
$query .= ' where client.deleted=0';
$query_prm = array();
if ($clientcategoryid > 0) { $query = $query . ' and client.clientcategoryid='.$clientcategoryid; echo '<p>Catégorie client: ' . $clientcategoryA[$clientcategoryid] . '</p>'; }
if ($clientcategory2id > 0) { $query = $query . ' and client.clientcategory2id='.$clientcategory2id; echo '<p>Catégorie client 2: ' . $clientcategory2A[$clientcategory2id] . '</p>'; }
if ($employeeid > 0) { $query = $query . ' and client.employeeid='.$employeeid; echo '<p>'.$_SESSION['ds_term_clientemployee1'].': ' . $employeeA[$employeeid] . '</p>'; }
if ($employeeid2 > 0) { $query = $query . ' and client.employeeid2='.$employeeid2; echo '<p>'.$_SESSION['ds_term_clientemployee2'].': ' . $employeeA[$employeeid2] . '</p>'; }
if ($_POST['employeeid'] == 0) { $query = $query . ' and client.employeeid=0'; echo '<p>Employé: &lt;Aucun&gt;</p>'; }
if ($clienttermid > 0) { $query = $query . ' and client.clienttermid=?'; array_push($query_prm, $clienttermid); echo '<p>Délai de paiement: '.d_output($clienttermA[$clienttermid]).'</p>'; }
if ($townid > 0) { $query = $query . ' and client.townid='.$townid; $islandid = $town_islandidA[$townid]; echo '<p>Ville: ' . $townA[$townid] . '</p>'; }
if ($islandid > 0) { $query = $query . ' and town.islandid='.$islandid; echo '<p>Île: ' . $islandA[$islandid] . '</p>'; }
if ($islandsort == 0) { $query = $query . ' order by employeename,clientname'; }
if ($islandsort == 1) { $query = $query . ' order by customorder,islandname,clientname'; }
if ($islandsort == 2) { $query = $query . ' order by clientname'; }
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
$clientlist = '(';
for ($i=0; $i < $num_results_main; $i++)
{
  $clientlist .= $main_result[$i]['clientid'] . ',';
}
$clientlist = rtrim($clientlist,',') . ')';
if ($clientlist == '()') { $clientlist = '(-1)'; }

if ($months > 0)
{
$monthA = $totalA = array(); $grandtotal = $t_24 = $t_p = 0;
echo d_table('report');
echo '<tr><td colspan=2><b>Client</b><td><b>Employé</b><td><b>Solde</b>';
for ($month=1;$month<=$months;$month++)
{
  $totalA[$month] = 0;
  echo '<td><b>'.$month.' Mois</b>';
  
  $query = 'select clientid,sum(invoiceprice) as total
  from invoicehistory
  where '.$datename.'>=DATE_SUB(?,INTERVAL '.$month.' MONTH) and '.$datename.'<DATE_SUB(?,INTERVAL '.($month-1).' MONTH)
  and (matchingid=0 or matchingid>?) and cancelledid=0 and confirmed=1 and isreturn=0 and invoiceprice>0
  and clientid in '.$clientlist.'
  group by clientid order by clientid ';
  $query_prm = array($ourdate, $ourdate, $matchingid);
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    if (!isset($monthA[$month][$query_result[$i]['clientid']])) { $monthA[$month][$query_result[$i]['clientid']] = 0; }
    $monthA[$month][$query_result[$i]['clientid']] += $query_result[$i]['total'];
    $totalA[$month] += $query_result[$i]['total'];
  }
  $query = 'select clientid,sum(invoiceprice) as total
  from invoicehistory
  where '.$datename.'>=DATE_SUB(?,INTERVAL '.$month.' MONTH) and '.$datename.'<DATE_SUB(?,INTERVAL '.($month-1).' MONTH)
  and (matchingid=0 or matchingid>?) and cancelledid=0 and confirmed=1 and isreturn=1 and invoiceprice>0
  and clientid in '.$clientlist.'
  group by clientid order by clientid ';
  $query_prm = array($ourdate, $ourdate, $matchingid);
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    if (!isset($monthA[$month][$query_result[$i]['clientid']])) { $monthA[$month][$query_result[$i]['clientid']] = 0; }
    $monthA[$month][$query_result[$i]['clientid']] -= $query_result[$i]['total'];
    $totalA[$month] -= $query_result[$i]['total'];
  }
}
echo '<td><b>Plus de '.$months.' mois</b>';
$more24A = array();
$query = 'select clientid,sum(invoiceprice) as total
from invoicehistory
where '.$datename.'<DATE_SUB(?,INTERVAL '.$months.' MONTH) and (matchingid=0 or matchingid>?) and cancelledid=0 and confirmed=1 and isreturn=0 and invoiceprice>0 and clientid in '.$clientlist.'
group by clientid order by clientid ';
$query_prm = array($ourdate, $matchingid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if (!isset($more24A[$query_result[$i]['clientid']])) { $more24A[$query_result[$i]['clientid']] = 0; }
  $more24A[$query_result[$i]['clientid']] += $query_result[$i]['total'];
  $t_24 += $query_result[$i]['total'];
}
$query = 'select clientid,sum(invoiceprice) as total
from invoicehistory
where '.$datename.'<DATE_SUB(?,INTERVAL '.$months.' MONTH) and (matchingid=0 or matchingid>?) and cancelledid=0 and confirmed=1 and isreturn=1 and invoiceprice>0 and clientid in '.$clientlist.'
group by clientid order by clientid ';
$query_prm = array($ourdate, $matchingid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if (!isset($more24A[$query_result[$i]['clientid']])) { $more24A[$query_result[$i]['clientid']] = 0; }
  $more24A[$query_result[$i]['clientid']] -= $query_result[$i]['total'];
  $t_24 -= $query_result[$i]['total'];
}
echo '<td><b>Paiements en cours</b>';
$paidA = array();
$query = 'select clientid,sum(value) as total
from payment
where paymentdate<=? and (matchingid=0 or matchingid>?) and reimbursement=0 and clientid in '.$clientlist.'
group by clientid order by clientid ';
$query_prm = array($ourdate, $matchingid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if (!isset($paidA[$query_result[$i]['clientid']])) { $paidA[$query_result[$i]['clientid']] = 0; }
  $paidA[$query_result[$i]['clientid']] += $query_result[$i]['total'];
}
$query = 'select clientid,sum(value) as total
from payment
where paymentdate<=? and (matchingid=0 or matchingid>?) and reimbursement=1 and clientid in '.$clientlist.'
group by clientid order by clientid ';
$query_prm = array($ourdate, $matchingid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if (!isset($paidA[$query_result[$i]['clientid']])) { $paidA[$query_result[$i]['clientid']] = 0; }
  $paidA[$query_result[$i]['clientid']] -= $query_result[$i]['total'];
}
for ($i=0; $i < $num_results_main; $i++)
{
  $total = 0;
  for ($month=1;$month<=$months;$month++)
  {
    if (isset($monthA[$month][$main_result[$i]['clientid']]))
    { $total += $monthA[$month][$main_result[$i]['clientid']]; }
  }
  if (isset($more24A[$main_result[$i]['clientid']]))
  { $total += $more24A[$main_result[$i]['clientid']]; }
  if ($total > 0)
  {
    $grandtotal += $total;
    $employeename = '';
    if ($main_result[$i]['employeeid'] > 0) { $employeename = $employeeA[$main_result[$i]['employeeid']]; }
    echo d_tr();
    $clientname = d_decode($main_result[$i]['clientname']) . ' [' . $main_result[$i]['clientid'] . ']';
    $link = '<a href="reportwindow.php?report=showclient&client='.$main_result[$i]['clientid'].'" target=_blank>'.$clientname.'</a>';
    if ($islandsort > 0)
    {
      echo d_td_unfiltered($link);
      echo d_td($main_result[$i]['islandname']);
      echo d_td($employeename);
    }
    else
    {
      echo d_td_unfiltered($link,'',2);
      echo d_td($employeename);
    }
    echo d_td($total, 'decimal');
    for ($month=1;$month<=$months;$month++)
    {
      if (isset($monthA[$month][$main_result[$i]['clientid']]))
      { echo d_td($monthA[$month][$main_result[$i]['clientid']], 'decimal'); }
      else { echo d_td(); }
    }
    if (isset($more24A[$main_result[$i]['clientid']])) { echo d_td($more24A[$main_result[$i]['clientid']], 'decimal'); }
    else { echo d_td(); }
    if (isset($paidA[$main_result[$i]['clientid']]))
    {
      echo d_td($paidA[$main_result[$i]['clientid']], 'decimal');
      $t_p += $paidA[$main_result[$i]['clientid']];
    }
    else { echo d_td(); }
  }
}
echo d_tr(1);
echo d_td('Totaux','',3);
echo d_td($grandtotal, 'decimal');
for ($month=1;$month<=$months;$month++)
{
  echo d_td($totalA[$month], 'decimal');
}
echo d_td($t_24, 'decimal');
echo d_td($t_p, 'decimal');
echo d_table_end();
}
else{

echo d_table('report');
echo '<tr><td colspan=2><b>Client</b><td><b>Employé</b>';
echo '<td><b>Solde</b><td><b>Mois</b><td><b>30 jours</b><td><b>60 jours</b><td><b>90 jours</b>';
echo '<td><b>Plus de 90 jours</b>';
echo '<td><b>Paiements en cours</b>';

$monthA = array();
$query = 'select clientid,sum(invoiceprice) as total
from invoicehistory
where '.$datename.'>=DATE_SUB(?,INTERVAL 30 DAY) and '.$datename.'<? and (matchingid=0 or matchingid>?) and cancelledid=0 and confirmed=1 and isreturn=0 and invoiceprice>0 and clientid in '.$clientlist.'
group by clientid order by clientid ';
$query_prm = array($ourdate, $ourdate, $matchingid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if (!isset($monthA[$query_result[$i]['clientid']])) { $monthA[$query_result[$i]['clientid']] = 0; }
  $monthA[$query_result[$i]['clientid']] += $query_result[$i]['total'];
}
$query = 'select clientid,sum(invoiceprice) as total
from invoicehistory
where '.$datename.'>=DATE_SUB(?,INTERVAL 30 DAY) and '.$datename.'<? and (matchingid=0 or matchingid>?) and cancelledid=0 and confirmed=1 and isreturn=1 and invoiceprice>0 and clientid in '.$clientlist.'
group by clientid order by clientid ';
$query_prm = array($ourdate, $ourdate, $matchingid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if (!isset($monthA[$query_result[$i]['clientid']])) { $monthA[$query_result[$i]['clientid']] = 0; }
  $monthA[$query_result[$i]['clientid']] -= $query_result[$i]['total'];
}

$days30A = array();
$query = 'select clientid,sum(invoiceprice) as total
from invoicehistory
where '.$datename.'>=DATE_SUB(?,INTERVAL 60 DAY) and '.$datename.'<DATE_SUB(?,INTERVAL 30 DAY) and (matchingid=0 or matchingid>?) and cancelledid=0 and confirmed=1 and isreturn=0 and invoiceprice>0 and clientid in '.$clientlist.'
group by clientid order by clientid ';
$query_prm = array($ourdate, $ourdate, $matchingid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if (!isset($days30A[$query_result[$i]['clientid']])) { $days30A[$query_result[$i]['clientid']] = 0; }
  $days30A[$query_result[$i]['clientid']] += $query_result[$i]['total'];
}
$query = 'select clientid,sum(invoiceprice) as total
from invoicehistory
where '.$datename.'>=DATE_SUB(?,INTERVAL 60 DAY) and '.$datename.'<DATE_SUB(?,INTERVAL 30 DAY) and (matchingid=0 or matchingid>?) and cancelledid=0 and confirmed=1 and isreturn=1 and invoiceprice>0 and clientid in '.$clientlist.'
group by clientid order by clientid ';
$query_prm = array($ourdate, $ourdate, $matchingid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if (!isset($days30A[$query_result[$i]['clientid']])) { $days30A[$query_result[$i]['clientid']] = 0; }
  $days30A[$query_result[$i]['clientid']] -= $query_result[$i]['total'];
}

$days60A = array();
$query = 'select clientid,sum(invoiceprice) as total
from invoicehistory
where '.$datename.'>=DATE_SUB(?,INTERVAL 90 DAY) and '.$datename.'<DATE_SUB(?,INTERVAL 60 DAY) and (matchingid=0 or matchingid>?) and cancelledid=0 and confirmed=1 and isreturn=0 and invoiceprice>0 and clientid in '.$clientlist.'
group by clientid order by clientid ';
$query_prm = array($ourdate, $ourdate, $matchingid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if (!isset($days60A[$query_result[$i]['clientid']])) { $days60A[$query_result[$i]['clientid']] = 0; }
  $days60A[$query_result[$i]['clientid']] += $query_result[$i]['total'];
}
$query = 'select clientid,sum(invoiceprice) as total
from invoicehistory
where '.$datename.'>=DATE_SUB(?,INTERVAL 90 DAY) and '.$datename.'<DATE_SUB(?,INTERVAL 60 DAY) and (matchingid=0 or matchingid>?) and cancelledid=0 and confirmed=1 and isreturn=1 and invoiceprice>0 and clientid in '.$clientlist.'
group by clientid order by clientid ';
$query_prm = array($ourdate, $ourdate, $matchingid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if (!isset($days60A[$query_result[$i]['clientid']])) { $days60A[$query_result[$i]['clientid']] = 0; }
  $days60A[$query_result[$i]['clientid']] -= $query_result[$i]['total'];
}

$days90A = array();
$query = 'select clientid,sum(invoiceprice) as total
from invoicehistory
where '.$datename.'>=DATE_SUB(?,INTERVAL 120 DAY) and '.$datename.'<DATE_SUB(?,INTERVAL 90 DAY) and (matchingid=0 or matchingid>?) and cancelledid=0 and confirmed=1 and isreturn=0 and invoiceprice>0 and clientid in '.$clientlist.'
group by clientid order by clientid ';
$query_prm = array($ourdate, $ourdate, $matchingid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if (!isset($days90A[$query_result[$i]['clientid']])) { $days90A[$query_result[$i]['clientid']] = 0; }
  $days90A[$query_result[$i]['clientid']] += $query_result[$i]['total'];
}
$query = 'select clientid,sum(invoiceprice) as total
from invoicehistory
where '.$datename.'>=DATE_SUB(?,INTERVAL 120 DAY) and '.$datename.'<DATE_SUB(?,INTERVAL 90 DAY) and (matchingid=0 or matchingid>?) and cancelledid=0 and confirmed=1 and isreturn=1 and invoiceprice>0 and clientid in '.$clientlist.'
group by clientid order by clientid ';
$query_prm = array($ourdate, $ourdate, $matchingid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if (!isset($days90A[$query_result[$i]['clientid']])) { $days90A[$query_result[$i]['clientid']] = 0; }
  $days90A[$query_result[$i]['clientid']] -= $query_result[$i]['total'];
}

$more90A = array();
$query = 'select clientid,sum(invoiceprice) as total
from invoicehistory
where '.$datename.'<DATE_SUB(?,INTERVAL 120 DAY) and (matchingid=0 or matchingid>?) and cancelledid=0 and confirmed=1 and isreturn=0 and invoiceprice>0 and clientid in '.$clientlist.'
group by clientid order by clientid ';
$query_prm = array($ourdate, $matchingid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if (!isset($more90A[$query_result[$i]['clientid']])) { $more90A[$query_result[$i]['clientid']] = 0; }
  $more90A[$query_result[$i]['clientid']] += $query_result[$i]['total'];
}
$query = 'select clientid,sum(invoiceprice) as total
from invoicehistory
where '.$datename.'<DATE_SUB(?,INTERVAL 120 DAY) and (matchingid=0 or matchingid>?) and cancelledid=0 and confirmed=1 and isreturn=1 and invoiceprice>0 and clientid in '.$clientlist.'
group by clientid order by clientid ';
$query_prm = array($ourdate, $matchingid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if (!isset($more90A[$query_result[$i]['clientid']])) { $more90A[$query_result[$i]['clientid']] = 0; }
  $more90A[$query_result[$i]['clientid']] -= $query_result[$i]['total'];
}

$paidA = array();
$query = 'select clientid,sum(value) as total
from payment
where paymentdate<=? and (matchingid=0 or matchingid>?) and reimbursement=0 and clientid in '.$clientlist.'
group by clientid order by clientid ';
$query_prm = array($ourdate, $matchingid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if (!isset($paidA[$query_result[$i]['clientid']])) { $paidA[$query_result[$i]['clientid']] = 0; }
  $paidA[$query_result[$i]['clientid']] += $query_result[$i]['total'];
}
$query = 'select clientid,sum(value) as total
from payment
where paymentdate<=? and (matchingid=0 or matchingid>?) and reimbursement=1 and clientid in '.$clientlist.'
group by clientid order by clientid ';
$query_prm = array($ourdate, $matchingid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if (!isset($paidA[$query_result[$i]['clientid']])) { $paidA[$query_result[$i]['clientid']] = 0; }
  $paidA[$query_result[$i]['clientid']] -= $query_result[$i]['total'];
}

$total1 = $total2 = $total3 = $total4 = $total5 = $total6 = $total7 = 0;
for ($i=0; $i < $num_results_main; $i++)
{
  $total = 0;
  if (isset($monthA[$main_result[$i]['clientid']])) { $total += $monthA[$main_result[$i]['clientid']]; }
  if (isset($days30A[$main_result[$i]['clientid']])) { $total += $days30A[$main_result[$i]['clientid']]; }
  if (isset($days60A[$main_result[$i]['clientid']])) { $total += $days60A[$main_result[$i]['clientid']]; }
  if (isset($days90A[$main_result[$i]['clientid']])) { $total += $days90A[$main_result[$i]['clientid']]; }
  if (isset($more90A[$main_result[$i]['clientid']])) { $total += $more90A[$main_result[$i]['clientid']]; }
  if (isset($paidA[$main_result[$i]['clientid']])) { $total -= $paidA[$main_result[$i]['clientid']]; }
  if ($total != 0)
  {
    $total1 += $total;
    if (isset($monthA[$main_result[$i]['clientid']])) { $total2 += $monthA[$main_result[$i]['clientid']]; }
    if (isset($days30A[$main_result[$i]['clientid']])) { $total3 += $days30A[$main_result[$i]['clientid']]; }
    if (isset($days60A[$main_result[$i]['clientid']])) { $total4 += $days60A[$main_result[$i]['clientid']]; }
    if (isset($days90A[$main_result[$i]['clientid']])) { $total5 += $days90A[$main_result[$i]['clientid']]; }
    if (isset($more90A[$main_result[$i]['clientid']])) { $total6 += $more90A[$main_result[$i]['clientid']]; }
    if (isset($paidA[$main_result[$i]['clientid']])) { $total7 += $paidA[$main_result[$i]['clientid']]; }
    $employeename = '';
    if ($main_result[$i]['employeeid'] > 0) { $employeename = $employeeA[$main_result[$i]['employeeid']]; }
    echo d_tr();
    $clientname = d_decode($main_result[$i]['clientname']) . ' [' . $main_result[$i]['clientid'] . ']';
    $link = '<a href="reportwindow.php?report=showclient&client='.$main_result[$i]['clientid'].'" target=_blank>'.$clientname.'</a>';
    if ($islandsort > 0)
    {
      echo d_td_unfiltered($link);
      echo d_td($main_result[$i]['islandname']);
      echo d_td($employeename);
    }
    else
    {
      echo d_td_unfiltered($link,'',2);
      echo d_td($employeename);
    }
    echo d_td($total, 'decimal');
    if (isset($monthA[$main_result[$i]['clientid']])) { echo d_td($monthA[$main_result[$i]['clientid']], 'decimal'); }
    else { echo d_td(); }
    if (isset($days30A[$main_result[$i]['clientid']])) { echo d_td($days30A[$main_result[$i]['clientid']], 'decimal'); }
    else { echo d_td(); }
    if (isset($days60A[$main_result[$i]['clientid']])) { echo d_td($days60A[$main_result[$i]['clientid']], 'decimal'); }
    else { echo d_td(); }
    if (isset($days90A[$main_result[$i]['clientid']])) { echo d_td($days90A[$main_result[$i]['clientid']], 'decimal'); }
    else { echo d_td(); }
    if (isset($more90A[$main_result[$i]['clientid']])) { echo d_td($more90A[$main_result[$i]['clientid']], 'decimal'); }
    else { echo d_td(); }
    if (isset($paidA[$main_result[$i]['clientid']])) { echo d_td($paidA[$main_result[$i]['clientid']], 'decimal'); }
    else { echo d_td(); }
  }
}

echo d_tr(1);
echo d_td('Totaux','',3);
echo d_td($total1, 'decimal');
echo d_td($total2, 'decimal');
echo d_td($total3, 'decimal');
echo d_td($total4, 'decimal');
echo d_td($total5, 'decimal');
echo d_td($total6, 'decimal');
echo d_td($total7, 'decimal');
echo d_table_end();
}#endif
?>