<?php
/*
create table if not exists animalice_userstock (
  animalice_userstockid int unsigned not null primary key auto_increment,
  productid int unsigned not null default 0,
  userid int unsigned not null default 0,
  stock int signed not null default 0
);
*/

set_time_limit(3600);

$dauphin_hostname = 'localhost';
$dauphin_login = 'temsaas_user';
$dauphin_password = 'TemSaas!';
$dauphin_instancename = 'temsaas_animalice';
$dauphin_timezone = 'Pacific/Tahiti';

/* #local testing
$dauphin_hostname = 'localhost:3306';
$dauphin_login = 'root';
$dauphin_password = 'skyline';
$dauphin_instancename = 'dev';
$dauphin_timezone = '-11:00';
*/

$query = 'truncate table animalice_userstock';
$query_prm = array();
### doquery insert/update
$connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
$sth_temp->execute();
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$num_results = $sth_temp->rowCount();
$dbh_temp = NULL;
###


$query = 'select year(curdate()) as currentyear';
$query_prm = array();
### doquery select
$connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
$sth_temp->execute();
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$query_result = $sth_temp->fetchAll();
$num_results = $sth_temp->rowCount();
$dbh_temp = NULL;
###
$currentyear = $query_result[0]['currentyear'];

if ($currentyear < 2000) { exit; }


$query = 'select userid,username from usertable where deleted=0 and stockperthisuser=1 order by username';
$query_prm = array();
### doquery select
$connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
$sth_temp->execute();
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$query_result = $sth_temp->fetchAll();
$num_results = $sth_temp->rowCount();
$dbh_temp = NULL;
###
for ($i=0; $i < $num_results; $i++)
{
  $stockperuserA[$query_result[$i]['userid']] = $query_result[$i]['username'];
}
/*
echo '<table border=1 cellspacing=0 cellpadding=1>';
echo '<thead><th>Produit';
foreach ($stockperuserA as $name)
{
  echo '<th>',$name;
}
echo '<th>Total<th>Global<th>Écart</thead>';*/

$query_prm = array();
$query = 'select generic,discontinued,notforsale,productname,brand,supplierid,numberperunit,netweightlabel,suppliercode,productid
from product'; #$query .= ' where productid=1595 or productid=2096';
### doquery select
$connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
$sth_temp->execute();
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$query_result = $sth_temp->fetchAll();
$num_results = $sth_temp->rowCount();
$dbh_temp = NULL;
###
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{
  $usertotal = 0;
  $productid = $main_result[$i]['productid'];
  $numberperunit = $npu = $main_result[$i]['numberperunit'];
  #echo d_tr();
  #echo d_td($main_result[$i]['productname'].' ('.$main_result[$i]['productid'].')');
  foreach ($stockperuserA as $dp_userid => $name)
  {
    ###
    # mandatory input: $productid $currentyear $npu $dp_userid
    # outout: $stock $userstock $userunitstock $endyear $endyearrest $purchases $purchasesrest $sales $adjust $returns

    if (!isset($npu) || $npu < 1) { $npu = 1; }

    $query = 'select stock from endofyearstock_user where productid=? and year=? and userid=?';
    $query_prm = array($productid,($currentyear-1),$dp_userid);
    $connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
$sth_temp->execute();
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$query_result = $sth_temp->fetchAll();
$num_results = $sth_temp->rowCount();
$dbh_temp = NULL;
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
    }

    $sales = 0;
    $query = 'select SUM(quantity) as stock from invoicehistory,invoiceitemhistory
    where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and productid=? and year(accountingdate)=? and isreturn=0 and cancelledid=0 and userid=?';
    $query_prm = array($productid,$currentyear,$dp_userid);
    $connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
$sth_temp->execute();
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$query_result = $sth_temp->fetchAll();
$num_results = $sth_temp->rowCount();
$dbh_temp = NULL;
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
    $connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
$sth_temp->execute();
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$query_result = $sth_temp->fetchAll();
$num_results = $sth_temp->rowCount();
$dbh_temp = NULL;
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
    $connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
$sth_temp->execute();
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$query_result = $sth_temp->fetchAll();
$num_results = $sth_temp->rowCount();
$dbh_temp = NULL;
    if ($num_results)
    {
      $stock += $query_result[0]['stock'];
      $returns += $query_result[0]['stock'];
    }
    $query = 'select SUM(quantity) as stock from invoice,invoiceitem
    where invoiceitem.invoiceid=invoice.invoiceid and productid=? and year(accountingdate)=? and isreturn=1 and returntostock=1 and cancelledid=0 and userid=?';
    $query_prm = array($productid,$currentyear,$dp_userid);
    $connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
$sth_temp->execute();
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$query_result = $sth_temp->fetchAll();
$num_results = $sth_temp->rowCount();
$dbh_temp = NULL;
    if ($num_results)
    {
      $stock += $query_result[0]['stock'];
      $returns += $query_result[0]['stock'];
    }
    $returnsrest = $returns % $npu;
    $returns = floor($returns / $npu);

    $query = 'select sum(netchange) as stock from modifiedstock_user where productid=? and year(changedate)=? and foruserid=?';
    $query_prm = array($productid,$currentyear,$dp_userid);
    $connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
$sth_temp->execute();
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$query_result = $sth_temp->fetchAll();
$num_results = $sth_temp->rowCount();
$dbh_temp = NULL;
    if ($num_results)
    {
      $stock += $query_result[0]['stock'];
      $adjust = floor(abs($query_result[0]['stock']) / $npu);
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
    ###
    #echo d_td($userstock, 'int');
    $usertotal += $userstock;
    
    $query = 'insert into animalice_userstock (productid,userid,stock) values (?,?,?)';
    $query_prm = array($main_result[$i]['productid'],$dp_userid,$userstock);
### doquery insert/update
$connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
$sth_temp->execute();
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$num_results = $sth_temp->rowCount();
$dbh_temp = NULL;
###
    
  }
  #echo d_td();
  ###
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
    $connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
$sth_temp->execute();
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$query_result = $sth_temp->fetchAll();
$num_results = $sth_temp->rowCount();
$dbh_temp = NULL;
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
    $connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
$sth_temp->execute();
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$query_result = $sth_temp->fetchAll();
$num_results = $sth_temp->rowCount();
$dbh_temp = NULL;
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
    $connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
$sth_temp->execute();
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$query_result = $sth_temp->fetchAll();
$num_results = $sth_temp->rowCount();
$dbh_temp = NULL;
    if ($num_results)
    {
      $stock -= $query_result[0]['stock'];
      $sales += $query_result[0]['stock'];
    }
    $salesrest = $sales % $numberperunit;
    $sales = floor($sales / $numberperunit);
    if ($calckstock_debug) { echo '<br>sales=',$sales; }

    $returns = 0;
    $query = 'select SUM(quantity) as stock from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and productid=? and year(accountingdate)=? and isreturn=1 and returntostock=1 and cancelledid=0';
    $query_prm = array($productid,$currentyear);
    $connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
$sth_temp->execute();
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$query_result = $sth_temp->fetchAll();
$num_results = $sth_temp->rowCount();
$dbh_temp = NULL;
    if ($num_results)
    {
      $stock += $query_result[0]['stock'];
      $returns += $query_result[0]['stock'];
    }
    $query = 'select SUM(quantity) as stock from invoice,invoiceitem where invoiceitem.invoiceid=invoice.invoiceid and productid=? and year(accountingdate)=? and isreturn=1 and returntostock=1 and cancelledid=0';
    $query_prm = array($productid,$currentyear);
    $connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
$sth_temp->execute();
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$query_result = $sth_temp->fetchAll();
$num_results = $sth_temp->rowCount();
$dbh_temp = NULL;
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
    $connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
$sth_temp->execute();
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$query_result = $sth_temp->fetchAll();
$num_results = $sth_temp->rowCount();
$dbh_temp = NULL;
    if ($num_results)
    {
      $stock += $query_result[0]['stock'];
      $adjust = floor(abs($query_result[0]['stock']) / $numberperunit);
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
  ################################################
  ###
  #echo d_td($currentstock, 'int');
  #echo d_td($currentstock - $usertotal, 'int');
  
  $query = 'insert into animalice_userstock (productid,userid,stock) values (?,?,?)';
  $query_prm = array($main_result[$i]['productid'],0,$currentstock);
### doquery insert/update
$connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
$sth_temp->execute();
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$num_results = $sth_temp->rowCount();
$dbh_temp = NULL;
###
  
  
}
#echo d_table_end();

?>