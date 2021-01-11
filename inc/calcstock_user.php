<?php
# mandatory input: $productid $currentyear $npu $dp_userid
# outout: $stock $userstock $userunitstock $endyear $endyearrest $purchases $purchasesrest $sales $adjust $returns

if (!isset($npu) || $npu < 1) { $npu = 1; }

$query = 'select stock from endofyearstock_user where productid=? and year=? and userid=?';
$query_prm = array($productid,($currentyear-1),$dp_userid);
require('inc/doquery.php');
if ($num_results)
{
  $stock = $query_result[0]['stock'];
  $endyear = floor($query_result[0]['stock'] / $npu);
  $endyearrest = $query_result[0]['stock'] % $npu;
}
else
{
  $stock = 0;
  $endyear = 0;
  $endyearrest = 0;
  $query = 'insert into endofyearstock_user (userid,productid,year) values (?,?,?)';
  $query_prm = array($dp_userid, $productid, ($currentyear-1));
  require('inc/doquery.php');
}

$sales = 0;
$query = 'select SUM(quantity) as stock from invoicehistory,invoiceitemhistory
where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and productid=? and year(accountingdate)=? and isreturn=0 and cancelledid=0 and userid=?';
$query_prm = array($productid,$currentyear,$dp_userid);
require('inc/doquery.php');
if ($num_results)
{
  $stock -= $query_result[0]['stock'];
  $sales += $query_result[0]['stock'];
}
if ($_SESSION['ds_unconfirmedcountsinstock'] == 1)
{
$query = 'select SUM(quantity) as stock from invoice,invoiceitem
where invoiceitem.invoiceid=invoice.invoiceid and productid=? and year(accountingdate)=? and isreturn=0 and cancelledid=0 and proforma=0 and userid=?';
$query_prm = array($productid,$currentyear,$dp_userid);
require('inc/doquery.php');
if ($num_results)
{
  $stock -= $query_result[0]['stock'];
  $sales += $query_result[0]['stock'];
}
}
$salesrest = $sales % $npu;
$sales = floor($sales / $npu);

$returns = 0;
$query = 'select SUM(quantity) as stock from invoicehistory,invoiceitemhistory
where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and productid=? and year(accountingdate)=? and isreturn=1 and returntostock=1 and cancelledid=0 and userid=?';
$query_prm = array($productid,$currentyear,$dp_userid);
require('inc/doquery.php');
if ($num_results)
{
  $stock += $query_result[0]['stock'];
  $returns += $query_result[0]['stock'];
}
$query = 'select SUM(quantity) as stock from invoice,invoiceitem
where invoiceitem.invoiceid=invoice.invoiceid and productid=? and year(accountingdate)=? and isreturn=1 and returntostock=1 and cancelledid=0 and userid=?';
$query_prm = array($productid,$currentyear,$dp_userid);
require('inc/doquery.php');
if ($num_results)
{
  $stock += $query_result[0]['stock'];
  $returns += $query_result[0]['stock'];
}
$returnsrest = $returns % $npu;
$returns = floor($returns / $npu);

$query = 'select sum(netchange) as stock from modifiedstock_user where productid=? and year(changedate)=? and foruserid=?';
$query_prm = array($productid,$currentyear,$dp_userid);
require('inc/doquery.php');
if ($num_results)
{
  $stock += $query_result[0]['stock'];
  $adjust = floor(d_abs($query_result[0]['stock']) / $npu);
  $adjustrest = $query_result[0]['stock'] % $npu;
  $posadjust = 0; if ($query_result[0]['stock'] >= 0) { $posadjust = 1; }
}
else
{
  $adjust = 0;
  $adjustrest = 0;
  $posadjust = 1;
}

$userstock = floor($stock / $npu);
$userunitstock = $stock % $npu;

?>