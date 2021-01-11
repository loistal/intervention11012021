<?php

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
?>