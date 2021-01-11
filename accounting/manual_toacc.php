<?php

# TODO optimize

###
$query = 'select adjustmentdate from adjustmentgroup where closing=1 and deleted=0 order by adjustmentdate desc limit 1';
$query_prm = array();
require('inc/doquery.php');
$min_adjustmentdate = $query_result[0]['adjustmentdate'];
###

$integrate = (int) $_POST['integrate'];

if ($integrate == 1)
{
  $datename = 'startdate'; require('inc/datepickerresult.php');
  $datename = 'stopdate'; require('inc/datepickerresult.php');
  
  if ($startdate < $min_adjustmentdate) { $startdate = $min_adjustmentdate; }
  
  $query = 'select integrated_journalid,onbehalf_anid from globalvariables_accounting';
  $query_prm = array();
  require('inc/doquery.php');
  $integrated_journalid = $query_result[0]['integrated_journalid'];
  $onbehalf_anid = $query_result[0]['onbehalf_anid'];
  
  ######### invoices
  $invoiceA = array();
  $query = 'select invoiceid from invoicehistory where accountingdate>=? and accountingdate<=? and confirmed=1 and toacc=0 limit 100';
  $query_prm = array($startdate, $stopdate);
  require('inc/doquery.php');
  $num_invoices = $num_results;
  for ($i=0; $i < $num_results; $i++)
  {
    array_push($invoiceA,$query_result[$i]['invoiceid']); #echo $query_result[$i]['invoiceid'],'<br>';
  }

  # load accountingnumberids from taxcode
  $query = 'select taxcodeid,accountingnumberid,base_accountingnumberid from taxcode order by taxcodeid';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $acctax[$query_result[$i]['taxcodeid']] = $query_result[$i]['accountingnumberid'];
    $base_acctax[$query_result[$i]['taxcodeid']] = $query_result[$i]['base_accountingnumberid'];
  }

  foreach ($invoiceA as $invoiceid)
  {
    $query = 'select clientid,accountingdate,isreturn from invoicehistory where invoiceid=?';
    $query_prm = array($invoiceid);
    require('inc/doquery.php');
    $accountingdate = $query_result[0]['accountingdate'];
    $clientid = $query_result[0]['clientid'];
    if ($query_result[0]['isreturn'] == 1)
    {
      #$comment = 'Avoir ' . $invoiceid;
      $comment = 'Avoir';
      $debit = 0;
    }
    else
    {
      #$comment = 'Facture ' . $invoiceid;
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
    $query_prm = array($invoiceid);
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      if ($query_result[$i]['on_behalf'] == 1)
      {
        $supplierid = $query_result[$i]['supplierid'];
        $on_behalfA[$supplierid] += myround($query_result[$i]['lineprice']);
      }
      else
      {
        $base_accnumid = $query_result[$i]['accountingnumberid'];
        if ($base_accnumid == 0)
        {
          $base_accnumid = $base_acctax[$query_result[$i]['linetaxcodeid']];
        }
        $netA[$base_accnumid] += myround($query_result[$i]['lineprice']);
      }
      $accnumid = $acctax[$query_result[$i]['linetaxcodeid']];
      $vatA[$accnumid] += myround($query_result[$i]['linevat']);
      $total += myround($query_result[$i]['lineprice']) + myround($query_result[$i]['linevat']);
    }
    if ($total > 0)
    {
      # TODO IMPORTANT param for journalid ALSO set in manual integration
      $journalid = $integrated_journalid; if ($_SESSION['ds_customname'] == 'Espace 7') { $journalid = 5; }
      $query = 'insert into adjustmentgroup
      (userid,adjustmentdate,originaladjustmentdate,adjustmenttime,adjustmentcomment,reference,integrated,journalid)
      values (?, ?, curdate(), curtime(), ?, ?, 1, ?)';
      $query_prm = array($_SESSION['ds_userid'], $accountingdate, $comment, $invoiceid, $journalid);
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
    $query = 'update invoicehistory set toacc=1 where invoiceid=?';
    $query_prm = array($invoiceid);
    require('inc/doquery.php');
  }
  echo '<p>' . $num_invoices . ' factures intégrées.</p>';
  #########
  
  ######### payments
  $paymentA = array();
  $query = 'select paymentid from payment where paymentdate>=? and paymentdate<=? and toacc=0 limit 100';
  $query_prm = array($startdate, $stopdate);
  require('inc/doquery.php');
  $num_payments = $num_results;
  for ($i=0; $i < $num_results; $i++)
  {
    array_push($paymentA,$query_result[$i]['paymentid']); #echo 'p '. $query_result[$i]['paymentid'],'<br>';
  }
  
  foreach ($paymentA as $paymentid)
  {
    $query = 'select paymenttypeid,paymentdate,reimbursement,value,clientid from payment where paymentid=?';
    $query_prm = array($paymentid);
    require('inc/doquery.php');
    $paymenttypeid = $query_result[0]['paymenttypeid'];
    $paymentdate = $query_result[0]['paymentdate'];
    $reimbursement = $query_result[0]['reimbursement'];
    $value = $query_result[0]['value'];
    $clientid = $query_result[0]['clientid'];
    
    # find accountingnumberid
    $query = 'select accountingnumberid from paymenttype where paymenttypeid=?';
    $query_prm = array($paymenttypeid);
    require('inc/doquery.php');
    $id = $query_result[0]['accountingnumberid'];
    if ($reimbursement == 1)
    {
      #$comment = 'Remboursement ' . $inserted_paymentid;
      $comment = 'Remboursement';
      $debit = 0;
    }
    else
    {
      #$comment = 'Paiement ' . $inserted_paymentid;
      $comment = 'Paiement';
      $debit = 1;
    }
    $query = 'insert into adjustmentgroup (userid,adjustmentdate,originaladjustmentdate,adjustmenttime,adjustmentcomment,reference,integrated) values (?, ?, curdate(), curtime(), ?, ?, 2)';
    $query_prm = array($_SESSION['ds_userid'], $paymentdate, $comment, $paymentid);
    require('inc/doquery.php');
    $adjustmentgroupid = $query_insert_id;
    $query = 'insert into adjustment (debit,adjustmentgroupid,value,accountingnumberid,matchingid,nomatch) values (?,?,?,?,0,1)';
    $query_prm = array($debit, $adjustmentgroupid, $value, $id);
    require('inc/doquery.php');
    if ($debit == 1) { $debit = 0; }
    else { $debit = 1; }
    $query = 'insert into adjustment (debit,adjustmentgroupid,value,referenceid,accountingnumberid,matchingid,nomatch) values (?,?,?,?,?,0,1)';
    $query_prm = array($debit, $adjustmentgroupid, $value, $clientid, 1); # hardcode accountingnumberid=1 for client sales
    require('inc/doquery.php');
    $query = 'update payment set toacc=1 where paymentid=?';
    $query_prm = array($paymentid);
    require('inc/doquery.php');
  }
  echo '<p>' . $num_payments . ' paiements intégrés.</p>';
  #########
  
  ######### deposits TODO !
  
  #########
  
  echo '<br>';
}


echo '<h2>Intégration manuelle</h2>
<p>Il y a une limite de 100 factures/paiements par intégration.</p>
<form method="post" action="accounting.php"><table class=report>';
echo '<tr><td>De:<td>'; $datename = 'startdate'; $dp_datepicker_min = $min_adjustmentdate; require('inc/datepicker.php');
echo '<tr><td>A:<td>'; $datename = 'stopdate'; $dp_datepicker_min = $min_adjustmentdate; require('inc/datepicker.php');
echo '</table>
<input type=hidden name="integrate" value="1"><input type=hidden name="accountingmenu" value="'. $accountingmenu .'">
<input type="submit" value="Valider"></form>';

?>