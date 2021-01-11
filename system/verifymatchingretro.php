<?php

$countermax = 50;
$offsetstart = 0; # should be 0

echo '<b>Retro matching check</b><br><br>';

$ok = 0; $counter = 0; $maxcounterreached = 0; $needretro = 0;

while(!$ok)
{
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

  $query = 'select referenceid as clientid,sum(value) as value from adjustment where matchingid>0 and debit=1 and accountingnumberid=1 group by clientid';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0;$i<$num_results;$i++)
  {
    $debitA[$query_result[$i]['clientid']] = $query_result[$i]['value'];
  }

  $query = 'select referenceid as clientid,sum(value) as value from adjustment where matchingid>0 and debit=0 and accountingnumberid=1 group by clientid';
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

#for ($i=97;$i<=97;$i++)
  for ($i=1;$i<=$maxid;$i++)
  {
    $value = $invoicesA[$i] + $reimbursementsA[$i] + $debitA[$i] - $paymentsA[$i] - $returnsA[$i] - $creditA[$i];
    if ($value != 0)
    {
      $ok = 0;
      if ($counter == 0) { echo '<br>Problème lettrage client ' . $i . '<br>'; }
      #echo 'Trying to correct... value:&nbsp;'.$value.'<br>';
      $offset = (int) ($counter * 100) +$offsetstart;
      ### RETRO reading ids from invoices
      $query = 'select distinct invoicehistory.matchingid from invoicehistory,matching where invoicehistory.matchingid=matching.matchingid and invoicehistory.clientid=? and matching.clientid=0 and confirmed=1 and cancelledid=0 and invoicehistory.matchingid>0 order by matchingid desc limit 100 offset ' . $offset;#echo $query.'<br>';
      $query_prm = array($i);
      require('inc/doquery.php');
      $main_result = $query_result; unset($query_result); $num_results_main = $num_results;
      for ($y=0;$y<$num_results_main;$y++)
      {
        $matchingid = $main_result[$y]['matchingid'];#echo $counter.' &nbsp; checking matchingid ' . $matchingid .'<br>';
        $mval = 0;
        $query = 'select sum(invoiceprice) as value from invoicehistory where matchingid=? and confirmed=1 and cancelledid=0 and isreturn=0';
        $query_prm = array($matchingid);
        require('inc/doquery.php');
        $mval += $query_result[0]['value'];
        $query = 'select sum(invoiceprice) as value from invoicehistory where matchingid=? and confirmed=1 and cancelledid=0 and isreturn=1';
        $query_prm = array($matchingid);
        require('inc/doquery.php');
        $mval -= $query_result[0]['value'];
        $query = 'select sum(value) as value from payment where matchingid=? and reimbursement=1';
        $query_prm = array($matchingid);
        require('inc/doquery.php');
        $mval += $query_result[0]['value'];
        $query = 'select sum(value) as value from payment where matchingid=? and reimbursement=0';
        $query_prm = array($matchingid);
        require('inc/doquery.php');
        $mval -= $query_result[0]['value'];
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
          echo 'Problem with matchingid ' . $matchingid . '<br>'; exit; # stop immediately to fix
        }
      }
      if ($num_results_main == 0)
      {
        $needretro = $i; $ok = 1;
      }
    }
  }
  
  $counter++;
  if ($counter >= $countermax) { $ok = 1; $maxcounterreached = 1; }
}
if ($maxcounterreached) { echo '<p class=alert>Max counter reached: '.$offset.'.</p>'; }
if ($needretro) { echo '<p class=alert>Tried all possible invoices (client '.$needretro.').</p>'; }


/*
if ($_POST['fixme'] == 1)
{
  # invoice seq
  $query = 'select max(invoiceid) as max from invoice';
  $query_prm = array();
  require('inc/doquery.php');
  $max = $query_result[0]['max'];
  $query = 'select max(invoiceid) as max from invoicehistory';
  $query_prm = array();
  require('inc/doquery.php');
  $max2 = $query_result[0]['max'];
  if ($max2 > $max) { $max = $max2; }
  $query = 'update seq set lastid=? where seqname="invoice"';
  $query_prm = array($max);
  require('inc/doquery.php');

  # invoiceitem seq
  $query = 'select max(invoiceitemid) as max from invoiceitem';
  $query_prm = array();
  require('inc/doquery.php');
  $max = $query_result[0]['max'];
  $query = 'select max(invoiceitemid) as max from invoiceitemhistory';
  $query_prm = array();
  require('inc/doquery.php');
  $max2 = $query_result[0]['max'];
  if ($max2 > $max) { $max = $max2; }
  $query = 'update seq set lastid=? where seqname="invoiceitem"';
  $query_prm = array($max);
  require('inc/doquery.php');
  
  # give new ids to invoices
  $query = 'select invoicehistory.invoiceid from invoicehistory,invoice where invoicehistory.invoiceid=invoice.invoiceid';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0;$i<$num_results_main;$i++)
  {
    $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoice"';
    $query_prm = array();
    require('inc/doquery.php');
    $invoiceid = $query_insert_id;
    $query = 'update invoice set invoiceid=? where invoiceid=? limit 1';
    $query_prm = array($invoiceid,$main_result[$i]['invoiceid']);
    require('inc/doquery.php');
    $query = 'update invoiceitem set invoiceid=? where invoiceid=?';
    $query_prm = array($invoiceid,$main_result[$i]['invoiceid']);
    require('inc/doquery.php');
    $query = 'update payment set forinvoiceid=? where forinvoiceid=?';
    $query_prm = array($invoiceid,$main_result[$i]['invoiceid']);
    require('inc/doquery.php');
  }
  
  # give new ids to invoiceitems
  $query = 'select invoiceitemhistory.invoiceitemid from invoiceitemhistory,invoiceitem where invoiceitemhistory.invoiceitemid=invoiceitem.invoiceitemid';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0;$i<$num_results_main;$i++)
  {
    $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoiceitem"';
    $query_prm = array();
    require('inc/doquery.php');
    $invoiceitemid = $query_insert_id;
    $query = 'update invoiceitem set invoiceitemid=? where invoiceitemid=? limit 1';
    $query_prm = array($invoiceitemid,$main_result[$i]['invoiceitemid']);
    require('inc/doquery.php');
  }
}

$ok = 1;

$query = 'select invoicehistory.invoiceid from invoicehistory,invoice where invoicehistory.invoiceid=invoice.invoiceid';
$query_prm = array();
require('inc/doquery.php');
if ($num_results) { $ok = 0; }

$query = 'select invoiceitemhistory.invoiceitemid from invoiceitemhistory,invoiceitem where invoiceitemhistory.invoiceitemid=invoiceitem.invoiceitemid';
$query_prm = array();
require('inc/doquery.php');
if ($num_results) { $ok = 0; }

if ($ok == 0)
{
  echo '<h2>Problème d\'archivage détecté.</h2>';
  echo '<form method="post" action="system.php"><input type=hidden name="systemmenu" value="' . $systemmenu . '"><input type=hidden name="fixme" value=1><input type="submit" value="Fix"></form>';
}
else
{
  echo '<h2>Archivage OK</h2>';
}
*/
?>