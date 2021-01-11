<?php

set_time_limit (60*5);

echo '<h2>Gest. Co.</h2>';

$query = 'select matchingid from matching order by matchingid';
$query_prm = array();
require ('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{
  $matchingid = $main_result[$i]['matchingid'];
  $mval = 0;
  echo '<br>Checking matchingid : ',$matchingid;
  $query = 'select sum(invoiceprice) as value from invoicehistory where matchingid=? and isreturn=0';
  $query_prm = array($matchingid);
  require('inc/doquery.php');
  $mval += $query_result[0]['value'];
  $query = 'select sum(invoiceprice) as value from invoicehistory where matchingid=? and isreturn=1';
  $query_prm = array($matchingid);
  require('inc/doquery.php');
  $mval -= $query_result[0]['value'];
  $query = 'select sum(value) as value from payment where matchingid=? and reimbursement=0';
  $query_prm = array($matchingid);
  require('inc/doquery.php');
  $mval -= $query_result[0]['value'];
  $query = 'select sum(value) as value from payment where matchingid=? and reimbursement=1';
  $query_prm = array($matchingid);
  require('inc/doquery.php');
  $mval += $query_result[0]['value'];
  
  if ($mval != 0)
  {
    echo ' !!! Delettrage matchingid ' . $matchingid;
    $query = 'update invoicehistory set matchingid=0 where matchingid=?';
    $query_prm = array($matchingid);
    require('inc/doquery.php');
    $query = 'update payment set matchingid=0 where matchingid=?';
    $query_prm = array($matchingid);
    require('inc/doquery.php');
    $query = 'update adjustment set matchingid=0 where matchingid=?';
    $query_prm = array($matchingid);
    require('inc/doquery.php');
  }
}

echo '<br><br><h2>Accounting</h2>';

for ($i=0; $i < $num_results_main; $i++)
{
  $matchingid = $main_result[$i]['matchingid'];
  $mval = 0;
  echo '<br>Checking matchingid : ',$matchingid;
  $query = 'select sum(value) as value from adjustment where matchingid=? and debit=1 and accountingnumberid=1';
  $query_prm = array($matchingid);
  require('inc/doquery.php');
  $mval += $query_result[0]['value'];
  $query = 'select sum(value) as value from adjustment where matchingid=? and debit=0 and accountingnumberid=1';
  $query_prm = array($matchingid);
  require('inc/doquery.php');
  $mval -= $query_result[0]['value'];
  if ($mval != 0)
  {
    echo ' !!! Delettrage matchingid ' . $matchingid;
    $query = 'update invoicehistory set matchingid=0 where matchingid=?';
    $query_prm = array($matchingid);
    require('inc/doquery.php');
    $query = 'update payment set matchingid=0 where matchingid=?';
    $query_prm = array($matchingid);
    require('inc/doquery.php');
    $query = 'update adjustment set matchingid=0 where matchingid=?';
    $query_prm = array($matchingid);
    require('inc/doquery.php');
  }
}

echo '<br>Done.';
?>