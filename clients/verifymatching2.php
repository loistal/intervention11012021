<?php

$clientid = (int) $_GET['clientid'];

if ($clientid > 0)
{
  echo '<h2>Détection des problèmes lettrage pour client numéro '.$clientid.'</h2>';
  
  $query = 'select sum(invoiceprice) as value,matchingid from invoicehistory
  where clientid=? and confirmed=1 and cancelledid=0 and isreturn=0 group by matchingid';
  $query_prm = array($clientid);
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $invA[$query_result[$i]['matchingid']] = $query_result[$i]['value'];
  }
  
  $query = 'select sum(invoiceprice) as value,matchingid from invoicehistory
  where clientid=? and confirmed=1 and cancelledid=0 and isreturn=1 group by matchingid';
  $query_prm = array($clientid);
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $retA[$query_result[$i]['matchingid']] = $query_result[$i]['value'];
  }
  
  $query = 'select sum(value) as value,matchingid from payment
  where clientid=? and reimbursement=1 group by matchingid';
  $query_prm = array($clientid);
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $reiA[$query_result[$i]['matchingid']] = $query_result[$i]['value'];
  }
  
  $query = 'select sum(value) as value,matchingid from payment
  where clientid=? and reimbursement=0 group by matchingid';
  $query_prm = array($clientid);
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $payA[$query_result[$i]['matchingid']] = $query_result[$i]['value'];
  }
  
  $query = 'select sum(value) as value,matchingid from adjustment,adjustmentgroup
  where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
  and referenceid=? and debit=1 and nomatch=0 and closing=0 and accountingnumberid=1 group by matchingid';
  $query_prm = array($clientid);
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $debA[$query_result[$i]['matchingid']] = $query_result[$i]['value'];
  }
  
  $query = 'select sum(value) as value,matchingid from adjustment,adjustmentgroup
  where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
  and referenceid=? and debit=0 and nomatch=0 and closing=0 and accountingnumberid=1 group by matchingid';
  $query_prm = array($clientid);
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $creA[$query_result[$i]['matchingid']] = $query_result[$i]['value'];
  }
  
  $query = 'select matchingid from invoicehistory where matchingid>0 and clientid=?
  union distinct
  select matchingid from payment where matchingid>0 and clientid=?
  union distinct
  select matchingid from adjustment,adjustmentgroup
  where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
  and matchingid>0 and nomatch=0 and closing=0 and referenceid=?';
  $query_prm = array($clientid,$clientid,$clientid);
  require ('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0; $i < $num_results_main; $i++)
  {
    $matchingid = $main_result[$i]['matchingid'];
    $mval = 0;
    if (isset($invA[$matchingid])) { $mval += $invA[$matchingid]; }
    if (isset($retA[$matchingid])) { $mval -= $retA[$matchingid]; }
    if (isset($reiA[$matchingid])) { $mval += $reiA[$matchingid]; }
    if (isset($payA[$matchingid])) { $mval -= $payA[$matchingid]; }
    if (isset($debA[$matchingid])) { $mval += $debA[$matchingid]; }
    if (isset($creA[$matchingid])) { $mval -= $creA[$matchingid]; }
    if ($mval != 0)
    {
      /*
      echo '<br>';
      echo $invA[$matchingid] . '+' . $reiA[$matchingid] . '+' . $debA[$matchingid];
      echo '<br>';
      echo '-' . $retA[$matchingid] . '-' . $payA[$matchingid] . '-' . $creA[$matchingid];
      echo '<br>' . $mval;
      */
      echo '<br>Problème sur léttrage : ' . $matchingid;
      $query = 'select date,clientid,userid from matching where matchingid=?';
      $query_prm = array($matchingid);
      require ('inc/doquery.php');
      echo ' de '.datefix($query_result[0]['date'],'short');
      echo ' &nbsp; <a href="clients.php?clientsmenu=unmatch&currentstep=1&matchingid='.$matchingid.'">Délettrer</a>';
    }
    /*
    if ($mval != 0)
    {
      echo '<br>Delettrage matchingid ' . $matchingid;
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
    */
  }
}

?>