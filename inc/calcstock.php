<?php
# mandatory input: $productid $currentyear $numberperunit
# optional input: $dp_donotupdate $dp_onlyupdate
# outout: $stock $currentstock $unitstock $endyear $endyearrest $purchases $purchasesrest $sales $adjust $returns
$calckstock_debug = 0;

if (!isset($numberperunit) || $numberperunit < 1) { $numberperunit = 1; }
if (!isset($dp_onlyupdate)) { $dp_onlyupdate = false; }

if(!$dp_onlyupdate)
{
  $query = 'select stock from endofyearstock where productid=? and year=?';
  $query_prm = array($productid,($currentyear-1));
  require('inc/doquery.php');
  if ($num_results)
  {
    $stock = $query_result[0]['stock'];
    $endyear = floor($query_result[0]['stock'] / $numberperunit);
    $endyearrest = $query_result[0]['stock'] % $numberperunit;
  }
  else
  {
    $stock = 0;
    $endyear = 0;
    $endyearrest = 0;
  }
  if ($calckstock_debug) { echo 'endofyearstock=',$stock; }

  $query = 'select sum(origamount) as stock from purchasebatch where deleted=0 and productid=? and year(arrivaldate)=?';
  $query_prm = array($productid,$currentyear);
  require('inc/doquery.php');
  if ($num_results)
  {
    $stock += $query_result[0]['stock'];
    $purchases = floor($query_result[0]['stock'] / $numberperunit);
    $purchasesrest = $query_result[0]['stock'] % $numberperunit;
  }
  else
  {
    $purchases = 0;
    $purchasesrest = 0;
  }
  if ($calckstock_debug) { echo '<br>purchases=',$purchases; }

  $sales = 0;
  $query = 'select SUM(quantity) as stock from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and productid=? and year(accountingdate)=? and isreturn=0 and cancelledid=0';
  $query_prm = array($productid,$currentyear);
  require('inc/doquery.php');
  if ($num_results)
  {
    $stock -= $query_result[0]['stock'];
    $sales += $query_result[0]['stock'];
  }
  if ($_SESSION['ds_unconfirmedcountsinstock'] == 1)
  {
  $query = 'select SUM(quantity) as stock from invoice,invoiceitem where invoiceitem.invoiceid=invoice.invoiceid and productid=? and year(accountingdate)=? and isreturn=0 and cancelledid=0 and proforma=0';
  $query_prm = array($productid,$currentyear);
  require('inc/doquery.php');
  if ($num_results)
  {
    $stock -= $query_result[0]['stock'];
    $sales += $query_result[0]['stock'];
  }
  }
  $salesrest = $sales % $numberperunit;
  $sales = floor($sales / $numberperunit);
  if ($calckstock_debug) { echo '<br>sales=',$sales; }

  $returns = 0;
  $query = 'select SUM(quantity) as stock from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and productid=? and year(accountingdate)=? and isreturn=1 and returntostock=1 and cancelledid=0';
  $query_prm = array($productid,$currentyear);
  require('inc/doquery.php');
  if ($num_results)
  {
    $stock += $query_result[0]['stock'];
    $returns += $query_result[0]['stock'];
  }
  $query = 'select SUM(quantity) as stock from invoice,invoiceitem where invoiceitem.invoiceid=invoice.invoiceid and productid=? and year(accountingdate)=? and isreturn=1 and returntostock=1 and cancelledid=0';
  $query_prm = array($productid,$currentyear);
  require('inc/doquery.php');
  if ($num_results)
  {
    $stock += $query_result[0]['stock'];
    $returns += $query_result[0]['stock'];
  }
  $returnsrest = $returns % $numberperunit;
  $returns = floor($returns / $numberperunit);
  if ($calckstock_debug) { echo '<br>returns=',$returns; }

  $query = 'select sum(netchange) as stock from modifiedstock where productid=? and year(changedate)=?';
  $query_prm = array($productid,$currentyear);
  require('inc/doquery.php');
  if ($num_results)
  {
    $stock += $query_result[0]['stock'];
    $adjust = floor(d_abs($query_result[0]['stock']) / $numberperunit);
    $adjustrest = $query_result[0]['stock'] % $numberperunit;
    $posadjust = 0; if ($query_result[0]['stock'] >= 0) { $posadjust = 1; }
  }
  else
  {
    $adjust = 0;
    $adjustrest = 0;
    $posadjust = 1;
  }
  if ($calckstock_debug) { echo '<br>adjust=',$adjust; }
    
  $currentstock = floor($stock / $numberperunit);
  $unitstock = $stock % $numberperunit;
}  

if (!isset($dp_donotupdate) || $dp_donotupdate != 1)
{
  $query = 'update product set currentstock=?,currentstockrest=?,stockdate=curdate() where productid=?';
  $query_prm = array($currentstock,$unitstock,$productid);
  require('inc/doquery.php');
  
  ################################################ 2013 11 13 find current batch
  if ($_SESSION['ds_salestrace'])
  {
    $kladdstock = $stock;
    $query = 'select prev,shipmentid,batchname,supplierbatchname,initials,purchasebatch.userid,purchasebatchid,arrivaldate,origamount,amount,totalcost,vat,useby';
    #if ($_SESSION['ds_useemplacement']) { $query = $query . ',placementname,warehousename'; }
    $query = $query . ' from purchasebatch,usertable';
    #if ($_SESSION['ds_useemplacement']) { $query = $query . ',placement,warehouse'; }
    $query = $query . ' where purchasebatch.userid=usertable.userid and purchasebatch.deleted=0';
    #if ($_SESSION['ds_useemplacement']) { $query = $query . ' and purchasebatch.placementid=placement.placementid and placement.warehouseid=warehouse.warehouseid'; }
    $query = $query . ' and productid="' . $productid . '"';
    $query = $query . ' order by ';
    #if ($_SESSION['ds_useemplacement']) { $query = $query . 'placementrank asc,'; } TODO
    $query = $query . 'arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc';
    $query_prm = array();
    require('inc/doquery.php');
    $showemptylots = 1; $currentpurchasebatchid = -1;
    for ($i_temp=0; $i_temp < $num_results; $i_temp++)
    {
      $rowX = $query_result[$i_temp];
      if ($showemptylots > -1)
      {
        $lotsize = $rowX['amount'];
        $kladdstock = $kladdstock - $lotsize;
        $amountleft = $lotsize;
        if ($kladdstock < 0) { $amountleft = $amountleft + $kladdstock; }
        if ($amountleft < 0) { $amountleft = 0; }
        if ($amountleft > 0) { $currentpurchasebatchid = $rowX['purchasebatchid']; }
        if ($kladdstock <= 0) { $showemptylots--; }
      }
    }
    if ($currentpurchasebatchid > 0)
    {
      $query = 'update product set currentpurchasebatchid=? where productid=?';
      $query_prm = array($currentpurchasebatchid, $productid);
      require('inc/doquery.php');
    }
  }
}
################################################
?>