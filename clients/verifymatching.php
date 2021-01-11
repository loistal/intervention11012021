<?php

set_time_limit (60*5);

$year = (int) $_POST['year'];
$query = 'select min(matchingid) as minid from matching
where deleted=0 and year(date)>=?';
$query_prm = array($year);
require('inc/doquery.php');
$minid = $query_result[0]['minid'];

echo '<h2>Vérification Lettrage</h2>';

$query = 'select clientid,sum(invoiceprice) as value from invoicehistory
where matchingid>=? and confirmed=1 and cancelledid=0 and isreturn=1 group by clientid';
$query_prm = array($minid);
require('inc/doquery.php');
for ($i=0;$i<$num_results;$i++)
{
  $returnsA[$query_result[$i]['clientid']] = $query_result[$i]['value'];
}

$query = 'select clientid,sum(invoiceprice) as value from invoicehistory
where matchingid>=? and confirmed=1 and cancelledid=0 and isreturn=0 group by clientid';
$query_prm = array($minid);
require('inc/doquery.php');
for ($i=0;$i<$num_results;$i++)
{
  $invoicesA[$query_result[$i]['clientid']] = $query_result[$i]['value'];
}

$query = 'select clientid,sum(value) as value from payment
where matchingid>=? and reimbursement=1 group by clientid';
$query_prm = array($minid);
require('inc/doquery.php');
for ($i=0;$i<$num_results;$i++)
{
  $reimbursementsA[$query_result[$i]['clientid']] = $query_result[$i]['value'];
}

$query = 'select clientid,sum(value) as value from payment
where matchingid>=? and reimbursement=0 group by clientid';
$query_prm = array($minid);
require('inc/doquery.php');
for ($i=0;$i<$num_results;$i++)
{
  $paymentsA[$query_result[$i]['clientid']] = $query_result[$i]['value'];
}

$query = 'select referenceid as clientid,sum(value) as value from adjustment,adjustmentgroup
where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
and matchingid>=? and debit=1 and nomatch=0 and closing=0 and accountingnumberid=1 group by referenceid';
$query_prm = array($minid);
require('inc/doquery.php');
for ($i=0;$i<$num_results;$i++)
{
  $debitA[$query_result[$i]['clientid']] = $query_result[$i]['value'];
}

$query = 'select referenceid as clientid,sum(value) as value from adjustment,adjustmentgroup
where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
and matchingid>=? and debit=0 and nomatch=0 and closing=0 and accountingnumberid=1 group by referenceid';
$query_prm = array($minid);
require('inc/doquery.php');
for ($i=0;$i<$num_results;$i++)
{
  $creditA[$query_result[$i]['clientid']] = $query_result[$i]['value'];
}

$query = 'select max(clientid) as maxid from client';
$query_prm = array();
require('inc/doquery.php');
$maxid = $query_result[0]['maxid'];
$ok = 1;

for ($i=1;$i<=$maxid;$i++)
{
  #$value = $invoicesA[$i] + $reimbursementsA[$i] + $debitA[$i] - $paymentsA[$i] - $returnsA[$i] - $creditA[$i];
  $value = 0;
  if (isset($invoicesA[$i])) { $value += $invoicesA[$i]; }
  if (isset($reimbursementsA[$i])) { $value += $reimbursementsA[$i]; }
  if (isset($debitA[$i])) { $value += $debitA[$i]; }
  if (isset($paymentsA[$i])) { $value -= $paymentsA[$i]; }
  if (isset($returnsA[$i])) { $value -= $returnsA[$i]; }
  if (isset($creditA[$i])) { $value -= $creditA[$i]; }
  if ($value != 0)
  {
    $ok = 0;
    echo '<br><br><b>Problème lettrage client ' . $i . '</b> &nbsp; <a href="clients.php?clientsmenu=verifymatching2&clientid='.$i.'">Détails</a>';
    /*echo '<br>Factures : ',$invoicesA[$i]+0;
    echo '<br>Remb : ',$reimbursementsA[$i]+0;
    echo '<br>Debit : ',$debitA[$i]+0;
    echo '<br>';
    echo '<br>Paym : ',$paymentsA[$i]+0;
    echo '<br>Avoir : ',$returnsA[$i]+0;
    echo '<br>Credit : ',$creditA[$i]+0;*/
  }
}

if ($ok) { echo '<br><p>Aucun problème n\'a été détecté.</p>'; }

?>