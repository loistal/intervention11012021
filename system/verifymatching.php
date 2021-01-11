<?php

set_time_limit (60*5);

echo '<b>Vérification Lettrage</b><br><br>';

$query = 'select clientid,sum(invoiceprice) as value from invoicehistory where matchingid>0 and confirmed=1 and cancelledid=0 and isreturn=1 group by clientid';
$query_prm = array();
require('inc/doquery.php');
for ($i=0;$i<$num_results;$i++)
{
  $returnsA[$query_result[$i]['clientid']] = $query_result[$i]['value'];
}

$query = 'select clientid,sum(invoiceprice) as value from invoicehistory where matchingid>0 and confirmed=1 and cancelledid=0 and isreturn=0 group by clientid';
$query_prm = array();
require('inc/doquery.php');
for ($i=0;$i<$num_results;$i++)
{
  $invoicesA[$query_result[$i]['clientid']] = $query_result[$i]['value'];
}

$query = 'select clientid,sum(value) as value from payment where matchingid>0 and reimbursement=1 group by clientid';
$query_prm = array();
require('inc/doquery.php');
for ($i=0;$i<$num_results;$i++)
{
  $reimbursementsA[$query_result[$i]['clientid']] = $query_result[$i]['value'];
}

$query = 'select clientid,sum(value) as value from payment where matchingid>0 and reimbursement=0 group by clientid';
$query_prm = array();
require('inc/doquery.php');
for ($i=0;$i<$num_results;$i++)
{
  $paymentsA[$query_result[$i]['clientid']] = $query_result[$i]['value'];
}

$query = 'select referenceid as clientid,sum(value) as value from adjustment where nomatch=0 and matchingid>0 and debit=1 and accountingnumberid=1 group by clientid';
$query_prm = array();
require('inc/doquery.php');
for ($i=0;$i<$num_results;$i++)
{
  $debitA[$query_result[$i]['clientid']] = $query_result[$i]['value'];
}

$query = 'select referenceid as clientid,sum(value) as value from adjustment where nomatch=0 and matchingid>0 and debit=0 and accountingnumberid=1 group by clientid';
$query_prm = array();
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
  $value = 0;
  #$value = $invoicesA[$i] + $reimbursementsA[$i] + $debitA[$i] - $paymentsA[$i] - $returnsA[$i] - $creditA[$i];
  if (isset($invoicesA[$i])) { $value += $invoicesA[$i]; }
  if (isset($reimbursementsA[$i])) { $value += $reimbursementsA[$i]; }
  if (isset($debitA[$i])) { $value += $debitA[$i]; }
  if (isset($paymentsA[$i])) { $value -= $paymentsA[$i]; }
  if (isset($returnsA[$i])) { $value -= $returnsA[$i]; }
  if (isset($creditA[$i])) { $value -= $creditA[$i]; }
  if ($value != 0)
  {
    echo '<br><br>Problème lettrage client ' . $i;
    echo '<br>Factures : '; if (isset($invoicesA[$i])) { echo $invoicesA[$i]; }
    echo '<br>Remb : '; if (isset($reimbursementsA[$i])) { echo $reimbursementsA[$i]; }
    echo '<br>Debit : '; if (isset($debitA[$i])) { echo $debitA[$i]; }
    echo '<br>Paym : '; if (isset($paymentsA[$i])) { echo $paymentsA[$i]; }
    echo '<br>Avoir : '; if (isset($returnsA[$i])) { echo $returnsA[$i]; }
    echo '<br>Credit : '; if (isset($creditA[$i])) { echo $creditA[$i]; }
  }
}

?>