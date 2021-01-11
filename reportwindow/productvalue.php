<?php

require('preload/unittype.php');
require('preload/taxcode.php');

$PA['stockdate'] = 'date';
$PA['product'] = 'product';
$PA['productdepartmentid'] = 'int';
$PA['productfamilygroupid'] = 'int';
$PA['productfamilyid'] = 'int';
$PA['excludesupplier'] = 'uint';
$PA['client'] = 'supplier';
$PA['userid'] = 'uint';
require('inc/readpost.php');

session_write_close();

$currentyear = (int) substr($stockdate,0,4);
$t_stock = $total = 0;

$title = 'Valeur du Stock ' . datefix2($stockdate);
showtitle_new($title);

require('inc/showparams.php');

echo d_table('report');
echo '<thead><th colspan=3>Produit<th>Stock<th>Prix de vente TTC<th>Prix de Revient<th>Valeur</thead>';

$query = 'select productid,productname,numberperunit,unittypeid,netweightlabel,suppliercode,salesprice,taxcodeid,
          departmentrank,productdepartmentname,familygrouprank,productfamilygroupname,familyrank,productfamilyname
          from product';
$query .= ',productfamily,productfamilygroup,productdepartment';
$query .= ' where discontinued=0';
$query .= ' and product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid
            and productfamilygroup.productdepartmentid=productdepartment.productdepartmentid';
$query_prm = array();
if ($productdepartmentid > 0) { $query .= ' and productfamilygroup.productdepartmentid=?'; array_push($query_prm, $productdepartmentid); }
if ($productfamilygroupid > 0) { $query .= ' and productfamily.productfamilygroupid=?'; array_push($query_prm, $productfamilygroupid); }
if ($productfamilyid > 0) { $query .= ' and product.productfamilyid=?'; array_push($query_prm, $productfamilyid); }
if ($productid > 0) { $query .= ' and productid=?'; array_push($query_prm, $productid); }
if ($supplierid > 0)
{
  if ($excludesupplier) { $query .= ' and product.supplierid<>?'; array_push($query_prm, $supplierid); }
  else { $query .= ' and product.supplierid=?'; array_push($query_prm, $supplierid); }
}
$query .= ' order by departmentrank,productdepartmentname,familygrouprank,productfamilygroupname,familyrank,productfamilyname,productname';
#$query .= ' limit 10';# HERE DEBUG
require ('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{
  $prev_error = 0;
  $query = 'select prev from purchasebatch where productid=? and arrivaldate<=? and prev>0 order by arrivaldate desc limit 1';
  $query_prm = array($main_result[$i]['productid'],$stockdate);
  require('inc/doquery.php');
  if ($num_results) { $prev = $query_result[0]['prev']; }
  else { $prev_error = 1; }
    
  echo d_tr();
  if ($_SESSION['ds_useproductcode']) { echo d_td($main_result[$i]['suppliercode']); }
  else { echo d_td($main_result[$i]['productid'], 'int'); }
  echo d_td(d_decode($main_result[$i]['productname']));
  echo d_td($main_result[$i]['netweightlabel']);
  
  ###
  if ($userid > 0)
  {
    # TODO calc userstock
    $productid = $main_result[$i]['productid'];
    $npu = $main_result[$i]['numberperunit'];
    $dp_userid = $userid;
    ################################
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
    where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and productid=? and year(accountingdate)=? and accountingdate<=? and isreturn=0 and cancelledid=0 and userid=?';
    $query_prm = array($productid,$currentyear,$stockdate,$dp_userid);
    require('inc/doquery.php');
    if ($num_results)
    {
      $stock -= $query_result[0]['stock'];
      $sales += $query_result[0]['stock'];
    }
    if ($_SESSION['ds_unconfirmedcountsinstock'] == 1)
    {
    $query = 'select SUM(quantity) as stock from invoice,invoiceitem
    where invoiceitem.invoiceid=invoice.invoiceid and productid=? and year(accountingdate)=? and accountingdate<=? and isreturn=0 and cancelledid=0 and proforma=0 and userid=?';
    $query_prm = array($productid,$currentyear,$stockdate,$dp_userid);
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
    where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and productid=? and year(accountingdate)=? and accountingdate<=? and isreturn=1 and returntostock=1 and cancelledid=0 and userid=?';
    $query_prm = array($productid,$currentyear,$stockdate,$dp_userid);
    require('inc/doquery.php');
    if ($num_results)
    {
      $stock += $query_result[0]['stock'];
      $returns += $query_result[0]['stock'];
    }
    $query = 'select SUM(quantity) as stock from invoice,invoiceitem
    where invoiceitem.invoiceid=invoice.invoiceid and productid=? and year(accountingdate)=? and accountingdate<=? and isreturn=1 and returntostock=1 and cancelledid=0 and userid=?';
    $query_prm = array($productid,$currentyear,$stockdate,$dp_userid);
    require('inc/doquery.php');
    if ($num_results)
    {
      $stock += $query_result[0]['stock'];
      $returns += $query_result[0]['stock'];
    }
    $returnsrest = $returns % $npu;
    $returns = floor($returns / $npu);

    $query = 'select sum(netchange) as stock from modifiedstock_user where productid=? and year(changedate)=? and changedate<=? and foruserid=?';
    $query_prm = array($productid,$currentyear,$stockdate,$dp_userid);
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
    
    $currentstock = $userstock;
    ################################
  }
  else
  {
    $productid = $main_result[$i]['productid'];
    $numberperunit = $main_result[$i]['numberperunit'];
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

    $sales = 0;
    $query = 'select SUM(quantity) as stock from invoicehistory,invoiceitemhistory
    where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and productid=? and year(accountingdate)=? and accountingdate<=? and isreturn=0 and cancelledid=0';
    $query_prm = array($productid,$currentyear,$stockdate);
    require('inc/doquery.php');
    if ($num_results)
    {
      $stock -= $query_result[0]['stock'];
      $sales += $query_result[0]['stock'];
    }
    if ($_SESSION['ds_unconfirmedcountsinstock'] == 1)
    {
    $query = 'select SUM(quantity) as stock from invoice,invoiceitem
    where invoiceitem.invoiceid=invoice.invoiceid and productid=? and year(accountingdate)=? and accountingdate<=? and isreturn=0 and cancelledid=0 and proforma=0';
    $query_prm = array($productid,$currentyear,$stockdate);
    require('inc/doquery.php');
    if ($num_results)
    {
      $stock -= $query_result[0]['stock'];
      $sales += $query_result[0]['stock'];
    }
    }
    $salesrest = $sales % $numberperunit;
    $sales = floor($sales / $numberperunit);

    $returns = 0;
    $query = 'select SUM(quantity) as stock from invoicehistory,invoiceitemhistory
    where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and productid=? and year(accountingdate)=? and accountingdate<=? and isreturn=1 and returntostock=1 and cancelledid=0';
    $query_prm = array($productid,$currentyear,$stockdate);
    require('inc/doquery.php');
    if ($num_results)
    {
      $stock += $query_result[0]['stock'];
      $returns += $query_result[0]['stock'];
    }
    $query = 'select SUM(quantity) as stock from invoice,invoiceitem
    where invoiceitem.invoiceid=invoice.invoiceid and productid=? and year(accountingdate)=? and accountingdate<=? and isreturn=1 and returntostock=1 and cancelledid=0';
    $query_prm = array($productid,$currentyear,$stockdate);
    require('inc/doquery.php');
    if ($num_results)
    {
      $stock += $query_result[0]['stock'];
      $returns += $query_result[0]['stock'];
    }
    $returnsrest = $returns % $numberperunit;
    $returns = floor($returns / $numberperunit);

    $query = 'select sum(netchange) as stock from modifiedstock where productid=? and year(changedate)=? and changedate<=?';
    $query_prm = array($productid,$currentyear,$stockdate);
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

    $currentstock = floor($stock / $numberperunit) / $unittype_dmpA[$main_result[$i]['unittypeid']];
    $unitstock = $stock % $numberperunit;
  }
  ###
  echo d_td($currentstock,'int');
  $t_stock += $currentstock;
  
  echo d_td($main_result[$i]['salesprice']*$taxcodeA[$main_result[$i]['taxcodeid']],'currency');

  if ($prev_error == 1) { echo d_td('Ne peut calculer le prix de revient','',10); }
  else
  {
    echo d_td($prev,'decimal');
    echo d_td($prev*$currentstock,'currency');
    $total += $prev*$currentstock;
  }
}

echo d_tr(1);
echo d_td('Total','',3);
echo d_td($t_stock,'int');
echo d_td();
echo d_td();
echo d_td($total,'int');
echo d_table_end();

?>