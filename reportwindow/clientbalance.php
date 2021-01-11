<?php

$ourdate = '2014-12-31';
$datename = 'ourdate'; require('inc/datepickerresult.php');

session_write_close();
$title = 'Balance Client du '.datefix2($ourdate);
showtitle($title);
echo '<h2>' . $title . '</h2>';

# all unmatched invoices before or on date
$query = 'select accountingdate as date,invoiceid as id,invoiceprice as value,invoicehistory.clientid,clientname
from invoicehistory,client
where invoicehistory.clientid=client.clientid
and cancelledid=0 and matchingid=0 and isreturn=0 and confirmed=1 and invoiceprice>0
and accountingdate<=?
order by clientid,date,id';
$query_prm = array($ourdate);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $query_result[$i]['type'] = 'Facture';
}
$merged_result = $query_result; $num_merged = $num_results;

#plus all invoices matched after or on date
$query = 'select accountingdate as date,invoiceid as id,invoiceprice as value,invoicehistory.clientid,clientname
from invoicehistory,matching,client
where invoicehistory.matchingid=matching.matchingid and invoicehistory.clientid=client.clientid
and accountingdate<=? and date>? and cancelledid=0 and isreturn=0 and confirmed=1 and invoiceprice>0
order by clientid,date,id';
$query_prm = array($ourdate, $ourdate);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $query_result[$i]['type'] = 'Facture';
  $merged_result[($num_merged+$i)] = $query_result[$i];
}
$num_merged += $num_results;

# all unmatched returns before or on date
$query = 'select accountingdate as date,invoiceid as id,invoiceprice as value,invoicehistory.clientid,clientname
from invoicehistory,client
where invoicehistory.clientid=client.clientid
and cancelledid=0 and matchingid=0 and isreturn=1 and confirmed=1 and invoiceprice>0
and accountingdate<=?
order by clientid,date,id';
$query_prm = array($ourdate);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $query_result[$i]['type'] = 'Avoir';
  $query_result[$i]['value'] = 0 - $query_result[$i]['value'];
  $merged_result[($num_merged+$i)] = $query_result[$i];
}
$num_merged += $num_results;

#plus all returns matched after or on date
$query = 'select accountingdate as date,invoiceid as id,invoiceprice as value,invoicehistory.clientid,clientname
from invoicehistory,matching,client
where invoicehistory.matchingid=matching.matchingid and invoicehistory.clientid=client.clientid
and accountingdate<=? and date>? and cancelledid=0 and isreturn=1 and confirmed=1 and invoiceprice>0
order by clientid,date,id';
$query_prm = array($ourdate, $ourdate);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $query_result[$i]['type'] = 'Avoir';
  $query_result[$i]['value'] = 0 - $query_result[$i]['value'];
  $merged_result[($num_merged+$i)] = $query_result[$i];
}
$num_merged += $num_results;

# all unmatched payments before or on date
$query = 'select paymentdate as date,paymentid as id,value,payment.clientid,clientname
from payment,client
where payment.clientid=client.clientid
and matchingid=0 and reimbursement=0 and value>0
and paymentdate<=?
order by clientid,date,id';
$query_prm = array($ourdate);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $query_result[$i]['type'] = 'Paiement';
  $query_result[$i]['value'] = 0 - $query_result[$i]['value'];
  $merged_result[($num_merged+$i)] = $query_result[$i];
}
$num_merged += $num_results;

#plus all payments matched after or on date
$query = 'select paymentdate as date,paymentid as id,value,payment.clientid,clientname
from payment,matching,client
where payment.matchingid=matching.matchingid and payment.clientid=client.clientid
and reimbursement=0 and value>0
and paymentdate<=? and date>?
order by clientid,date,id';
$query_prm = array($ourdate, $ourdate);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $query_result[$i]['type'] = 'Paiement';
  $query_result[$i]['value'] = 0 - $query_result[$i]['value'];
  $merged_result[($num_merged+$i)] = $query_result[$i];
}
$num_merged += $num_results;

# all unmatched reimburse before or on date
$query = 'select paymentdate as date,paymentid as id,value,payment.clientid,clientname
from payment,client
where payment.clientid=client.clientid
and matchingid=0 and reimbursement=1 and value>0
and paymentdate<=?
order by clientid,date,id';
$query_prm = array($ourdate);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $query_result[$i]['type'] = 'Remboursement';
  $merged_result[($num_merged+$i)] = $query_result[$i];
}
$num_merged += $num_results;

#plus all reimburse matched after or on date
$query = 'select paymentdate as date,paymentid as id,value,payment.clientid,clientname
from payment,matching,client
where payment.matchingid=matching.matchingid and payment.clientid=client.clientid
and reimbursement=1 and value>0
and paymentdate<=? and date>?
order by clientid,date,id';
$query_prm = array($ourdate, $ourdate);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $query_result[$i]['type'] = 'Remboursement';
  $merged_result[($num_merged+$i)] = $query_result[$i];
}
$num_merged += $num_results;

# all unmatched debit OD before or on date
$query = 'select adjustmentdate as date,adjustmentid as id,value,adjustment.referenceid as clientid,clientname
from adjustment,client,adjustmentgroup
where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and adjustment.referenceid=client.clientid
and matchingid=0 and debit=1 and value>0 and nomatch=0 and adjustmentgroup.deleted=0 and adjustment.accountingnumberid=1
and adjustmentdate<=?
order by clientid,date,id'; # TODO warning hardcode accountingnumberid=1
$query_prm = array($ourdate);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $query_result[$i]['type'] = 'OD Débit';
  $merged_result[($num_merged+$i)] = $query_result[$i];
}
$num_merged += $num_results;

# plus all debit OD matched after or on date
$query = 'select adjustmentdate as date,adjustmentid as id,value,adjustment.referenceid as clientid,clientname
from adjustment,matching,client,adjustmentgroup
where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and adjustment.referenceid=client.clientid and adjustment.matchingid=matching.matchingid
and debit=1 and value>0 and nomatch=0 and adjustmentgroup.deleted=0 and adjustment.accountingnumberid=1
and adjustmentdate<=? and date>?
order by clientid,date,id';
$query_prm = array($ourdate, $ourdate);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $query_result[$i]['type'] = 'OD Débit';
  $merged_result[($num_merged+$i)] = $query_result[$i];
}
$num_merged += $num_results;

# all unmatched credit OD before or on date
$query = 'select adjustmentdate as date,adjustmentid as id,value,adjustment.referenceid as clientid,clientname
from adjustment,client,adjustmentgroup
where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and adjustment.referenceid=client.clientid
and matchingid=0 and debit=0 and value>0 and nomatch=0 and adjustmentgroup.deleted=0 and adjustment.accountingnumberid=1
and adjustmentdate<=?
order by clientid,date,id'; # TODO warning hardcode accountingnumberid=1
$query_prm = array($ourdate);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $query_result[$i]['type'] = 'OD Crédit';
  $query_result[$i]['value'] = 0 - $query_result[$i]['value'];
  $merged_result[($num_merged+$i)] = $query_result[$i];
}
$num_merged += $num_results;

# plus all credit OD matched after or on date
$query = 'select adjustmentdate as date,adjustmentid as id,value,adjustment.referenceid as clientid,clientname
from adjustment,matching,client,adjustmentgroup
where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and adjustment.referenceid=client.clientid and adjustment.matchingid=matching.matchingid
and debit=0 and value>0 and nomatch=0 and adjustmentgroup.deleted=0 and adjustment.accountingnumberid=1
and adjustmentdate<=? and date>?
order by clientid,date,id';
$query_prm = array($ourdate, $ourdate);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $query_result[$i]['type'] = 'OD Crédit';
  $query_result[$i]['value'] = 0 - $query_result[$i]['value'];
  $merged_result[($num_merged+$i)] = $query_result[$i];
}
$num_merged += $num_results;

d_sortresults($merged_result, 'clientid', $num_merged);

$subtotal = 0; $total = 0;
echo '<table class=report><thead><th>Client<th colspan="2">Facture<th>Date<th>Montant</thead>';
for ($i=0; $i < $num_merged; $i++)
{
  $subtotal += $merged_result[$i]['value'];
  echo d_tr();
  echo d_td_old($merged_result[$i]['clientid'].': '.d_output(d_decode($merged_result[$i]['clientname'])));
  echo d_td_old($merged_result[$i]['type']);
  echo d_td_old($merged_result[$i]['id'],1);
  echo d_td_old(datefix2($merged_result[$i]['date']),1);
  echo d_td_old(myfix($merged_result[$i]['value']),1);
  if ($merged_result[$i]['clientid'] != $merged_result[($i+1)]['clientid'])
  {
    echo d_tr(1);
    echo d_td_old(substr(d_decode($merged_result[$i]['clientname']),0,30),0,2,4);
    echo d_td_old(myfix($subtotal),1,2);
    $total += $subtotal;
    $subtotal = 0;
  }
}
echo d_tr(1);
echo d_td_old('Total',0,2,4);
echo d_td_old(myfix($total),1,2);

?>