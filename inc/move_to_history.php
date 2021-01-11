<?php

# not safe for include
# TODO refactor

$query = 'select integrated_journalid,onbehalf_anid from globalvariables_accounting';
$query_prm = array();
require('inc/doquery.php');
$integrated_journalid = $query_result[0]['integrated_journalid'];
$onbehalf_anid = $query_result[0]['onbehalf_anid'];

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
  echo '<p class=alert>Problème d\'archivage. Veuiller contacter support.</p>';
  if ($_SESSION['ds_systemaccess'] == 1) { echo '<br>(<a href="system.php?systemmenu=tohistoryfix">Cliquer ici pour régler</a>)'; }
  exit;
}

# move invoices to history table

if ($_SESSION['ds_directtoacc'] == 1)
{
  ###
  $query = 'select adjustmentdate from adjustmentgroup where closing=1 and deleted=0 order by adjustmentdate desc limit 1';
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results) { $min_adjustmentdate = $query_result[0]['adjustmentdate']; }
  else { $min_adjustmentdate = '0000-00-00'; }
  ###

  # load accountingnumberids from taxcode
  $query = 'select taxcodeid,accountingnumberid,base_accountingnumberid from taxcode order by taxcodeid';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $acctax[$query_result[$i]['taxcodeid']] = $query_result[$i]['accountingnumberid'];
    $base_acctax[$query_result[$i]['taxcodeid']] = $query_result[$i]['base_accountingnumberid'];
  }
}

$query = 'select invoiceid,cancelledid from invoice where confirmed=1 or cancelledid=1';
$query_prm = array();
if (isset($move_to_history_invoiceid) && $move_to_history_invoiceid > 0) { $query .= ' and invoiceid=?'; array_push($query_prm, $move_to_history_invoiceid); }
$query = $query . ' LIMIT 1000';
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results; unset($query_result, $num_results);
for ($x=0;$x<$num_results_main;$x++)
{
  $row = $main_result[$x];
  $cancelledid = (int) $row['cancelledid'];
  
  usleep(100000); # do not stress system (wait 0.1 seconds)

  ## invoice insert
  $query = 'insert ignore into invoicehistory select * from invoice where invoiceid=?';
  $query_prm = array($row['invoiceid']);
  require('inc/doquery.php');
  
  if ($_SESSION['ds_confirm_remove_proforma'] == 1)
  {
    $query = 'update invoicehistory set proforma=0 where invoiceid=?';
    $query_prm = array($row['invoiceid']);
    require('inc/doquery.php');
  }
  
  $query = 'select invoiceitemid from invoiceitem where invoiceid=?';
  $query_prm = array($row['invoiceid']);
  require('inc/doquery.php');
  $item_result = $query_result; $num_results_item = $num_results; unset($query_result, $num_results);

  for ($y=0; $y < $num_results_item; $y++)
  {
    $row2 = $item_result[$y];
    
    ## invoiceitem insert
    $query = 'insert ignore into invoiceitemhistory select * from invoiceitem where invoiceitemid=?';
    $query_prm = array($row2['invoiceitemid']);
    require('inc/doquery.php');
    
    ## invoiceitem delete
    $query = 'delete from invoiceitem where invoiceitemid=?';
    $query_prm = array($row2['invoiceitemid']);
    require('inc/doquery.php');
  }

  ## invoice delete
  $query = 'delete from invoice where invoiceid=?';
  $query_prm = array($row['invoiceid']);
  require('inc/doquery.php');
  
  ######### toacc
  if ($_SESSION['ds_directtoacc'] == 1 && $cancelledid == 0)
  {
    $query = 'select clientid,accountingdate,isreturn from invoicehistory where invoiceid=?';
    $query_prm = array($row['invoiceid']);
    require('inc/doquery.php');
    $accountingdate = $query_result[0]['accountingdate'];
    $clientid = $query_result[0]['clientid'];
    if ($query_result[0]['isreturn'] == 1)
    {
      #$comment = 'Avoir ' . $row['invoiceid'];
      $comment = 'Avoir';
      $debit = 0;
    }
    else
    {
      #$comment = 'Facture ' . $row['invoiceid'];
      $comment = 'Facture';
      $debit = 1;
    }
    unset($netA,$vatA,$total);
    $netA = array(); $vatA = array(); $total = 0; $on_behalfA = array();
    /*$query = 'select lineprice,linevat,linetaxcodeid,accountingnumberid,on_behalf,supplierid from invoiceitemhistory,product
    where invoiceitemhistory.productid=product.productid and invoiceid=?';*/
    $query = 'select lineprice,linevat,linetaxcodeid,accountingnumberid,on_behalf,supplierid from invoiceitemhistory
    left outer join product on invoiceitemhistory.productid=product.productid 
    where invoiceid=?';
    $query_prm = array($row['invoiceid']);
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      if ($query_result[$i]['on_behalf'] == 1)
      {
        $supplierid = $query_result[$i]['supplierid'];
        if (!isset($on_behalfA[$supplierid])) { $on_behalfA[$supplierid] = 0; }
        $on_behalfA[$supplierid] += myround($query_result[$i]['lineprice']);
      }
      else
      {
        $base_accnumid = $query_result[$i]['accountingnumberid'];
        if ($base_accnumid == 0)
        {
          $base_accnumid = $base_acctax[$query_result[$i]['linetaxcodeid']];
        }
        if (!isset($netA[$base_accnumid])) { $netA[$base_accnumid] = 0; }
        $netA[$base_accnumid] += myround($query_result[$i]['lineprice']);
      }
      $accnumid = $acctax[$query_result[$i]['linetaxcodeid']];
      if (!isset($vatA[$accnumid])) { $vatA[$accnumid] = 0; }
      $vatA[$accnumid] += myround($query_result[$i]['linevat']);
      $total += myround($query_result[$i]['lineprice']) + myround($query_result[$i]['linevat']);
    }
    if ($total > 0 && $accountingdate >= $min_adjustmentdate)
    {
      # TODO IMPORTANT param for journalid ALSO set in manual integration
      $journalid = $integrated_journalid; if ($_SESSION['ds_customname'] == 'Espace 7') { $journalid = 5; }
      $query = 'insert into adjustmentgroup
      (userid,adjustmentdate,originaladjustmentdate,adjustmenttime,adjustmentcomment,reference,integrated,journalid)
      values (?, ?, curdate(), curtime(), ?, ?, 1, ?)';
      $query_prm = array($_SESSION['ds_userid'], $accountingdate, $comment, $row['invoiceid'], $journalid);
      require('inc/doquery.php');
      $adjustmentgroupid = $query_insert_id;
      $query = 'insert into adjustment (debit,adjustmentgroupid,value,referenceid,accountingnumberid,matchingid,nomatch) values (?,?,?,?,?,0,1)';
      $query_prm = array($debit, $adjustmentgroupid, $total, $clientid, 1); # hardcode accountingnumberid=1 for client sales
      require('inc/doquery.php');
      if ($debit == 1) { $debit = 0; }
      else { $debit = 1; }
      $supplierid = 0;
      foreach ($netA as $id => $value)
      {
        if ($value > 0)
        {
          $query = 'insert into adjustment (debit,adjustmentgroupid,value,accountingnumberid,matchingid,nomatch,referenceid) values (?,?,?,?,0,1,?)';
          $query_prm = array($debit, $adjustmentgroupid, $value, $id, $supplierid);
          require('inc/doquery.php');
        }
      }
      foreach ($on_behalfA as $supplierid => $value)
      {
        if ($value > 0)
        {
          $query = 'insert into adjustment (debit,adjustmentgroupid,value,accountingnumberid,matchingid,nomatch,referenceid) values (?,?,?,?,0,1,?)';
          $query_prm = array($debit, $adjustmentgroupid, $value, $onbehalf_anid, $supplierid);
          require('inc/doquery.php');
        }
      }
      $supplierid = 0;
      foreach ($vatA as $id => $value)
      {
        if ($value > 0)
        {
          $query = 'insert into adjustment (debit,adjustmentgroupid,value,accountingnumberid,matchingid,nomatch,referenceid) values (?,?,?,?,0,1,?)';
          $query_prm = array($debit, $adjustmentgroupid, $value, $id, $supplierid);
          require('inc/doquery.php');
        }
      }
    }
    if ($accountingdate >= $min_adjustmentdate)
    {
      $query = 'update invoicehistory set toacc=1 where invoiceid=?';
      $query_prm = array($row['invoiceid']);
      require('inc/doquery.php');
    }
  }
  #########
  
  if ($_SESSION['ds_continuousstock'] == 1 && $cancelledid == 0)
  {
    $query = 'select invoiceitemhistory.productid,quantity,isreturn,returntostock,numberperunit from invoiceitemhistory,invoicehistory,product
    where invoicehistory.invoiceid=invoiceitemhistory.invoiceid and invoiceitemhistory.productid=product.productid and invoicehistory.invoiceid=?';
    $query_prm = array($row['invoiceid']);
    require('inc/doquery.php');
    $main_result2 = $query_result; $num_results_main2 = $num_results;
    for ($i=0; $i < $num_results_main2; $i++)
    {
      # TODO optimise, add all sums before updating currentstock
      $cs = floor($main_result2[$i]['quantity'] / $main_result2[$i]['numberperunit']);
      $csr = $main_result2[$i]['quantity'] % $main_result2[$i]['numberperunit'];
      if($main_result2[$i]['isreturn'] == 0)
      {
        # remove stock
        if ($cs > 0)
        {
          $query = 'update product set currentstock=currentstock-? where productid=?';
          $query_prm = array($cs,$main_result2[$i]['productid']);
          require('inc/doquery.php');
        }
        if ($csr > 0)
        {
          $query = 'update product set currentstockrest=currentstockrest-? where productid=?';
          $query_prm = array($csr,$main_result2[$i]['productid']);
          require('inc/doquery.php');
        }
      }
      elseif ($main_result2[$i]['isreturn'] == 1 && $main_result2[$i]['returntostock'])
      {
        # add stock
        if ($cs > 0)
        {
          $query = 'update product set currentstock=currentstock+? where productid=?';
          $query_prm = array($cs,$main_result2[$i]['productid']);
          require('inc/doquery.php');
        }
        if ($csr > 0)
        {
          $query = 'update product set currentstockrest=currentstockrest+? where productid=?';
          $query_prm = array($csr,$main_result2[$i]['productid']);
          require('inc/doquery.php');
        }
      }
    }
  }

}


?>

