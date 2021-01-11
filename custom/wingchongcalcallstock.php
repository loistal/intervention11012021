<?php

set_time_limit(3600);

$dauphin_hostname = 'localhost';
$dauphin_login = 'temsaas';
$dauphin_password = 'wico';
$dauphin_instancename = 'temsaas_wico';
$dauphin_timezone = 'Pacific/Tahiti';

/* #local testing
$dauphin_hostname = 'localhost:3306';
$dauphin_login = 'root';
$dauphin_password = 'skyline';
$dauphin_instancename = 'wc_copy';
$dauphin_timezone = '-11:00';
*/

$query = 'select year(curdate()) as currentyear';
$query_prm = array();
### doquery select
$connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true); # only for mysql
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
else
{
  $query = 'select productid,numberperunit from product where discontinued=0 order by productid';
#$query = 'select productid,numberperunit from product where productid=5671'; # debug test
  $query_prm = array();
  ### doquery select
  $connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
  $dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
  $dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true); # only for mysql
  $sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
  $sth_temp->execute();
  $sth_temp = $dbh_temp->prepare($query);
  $sth_temp->execute($query_prm);
  $query_result = $sth_temp->fetchAll();
  $num_results = $sth_temp->rowCount();
  $dbh_temp = NULL;
  ###
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0;$i<$num_results_main;$i++)
  {
    $productid = $main_result[$i]['productid'];
    $numberperunit = $main_result[$i]['numberperunit'];

    if ($productid > 0 && $numberperunit > 0)
    {
      ### calcstock.php
      # INPUT $productid $currentyear $numberperunit
      # OUTPUT $currentstock $unitstock $endyear $endyearrest $purchases $sales $adjust $returns

      $query = 'select stock from endofyearstock where productid="' . $productid . '" and year="' . ($currentyear-1) . '"';
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
      if ($num_results) { $stock = $query_result[0]['stock']; }
      else { $stock = 0; }

      $query = 'select SUM(origamount) as stock from purchasebatch where productid="' . $productid . '"
      and DATE_FORMAT(arrivaldate,"%Y")="' . $currentyear . '"';
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
      $stock += $query_result[0]['stock'];

      $query = 'select SUM(quantity) as stock from invoicehistory,invoiceitemhistory
      where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and productid="' . $productid . '"
      and DATE_FORMAT(accountingdate,"%Y")="' . $currentyear . '" and isreturn=0 and cancelledid=0';
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
      $stock -= $query_result[0]['stock'];

      $query = 'select SUM(netchange) as stock from modifiedstock where productid="' . $productid . '"
      and DATE_FORMAT(changedate,"%Y")="' . $currentyear . '"';
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
      $stock += $query_result[0]['stock'];

      $query = 'select SUM(quantity) as stock from invoicehistory,invoiceitemhistory
      where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and productid="' . $productid . '"
      and DATE_FORMAT(accountingdate,"%Y")="' . $currentyear . '" and isreturn=1 and returntostock=1 and cancelledid=0';
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
      $stock += $query_result[0]['stock'];

      $currentstock = floor($stock / $numberperunit);
      $unitstock = $stock % $numberperunit;
      
      if (abs($currentstock) > 2147483000) { $currentstock = 0; }

      $query = 'update product set currentstock=?,currentstockrest=?,stockdate=curdate() where productid=?';
      $query_prm = array($currentstock,$unitstock,$productid);
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
    
    ################################################ 2013 11 13 find current batch
    
    $query = 'select prev,shipmentid,batchname,supplierbatchname,initials,purchasebatch.userid,purchasebatchid,arrivaldate,origamount,amount,totalcost,vat,useby';
    #if ($_SESSION['ds_useemplacement']) { $query = $query . ',placementname,warehousename'; }
    $query = $query . ' from purchasebatch,usertable';
    #if ($_SESSION['ds_useemplacement']) { $query = $query . ',placement,warehouse'; }
    $query = $query . ' where purchasebatch.userid=usertable.userid and purchasebatch.deleted=0';
    #if ($_SESSION['ds_useemplacement']) { $query = $query . ' and purchasebatch.placementid=placement.placementid and placement.warehouseid=warehouse.warehouseid'; }
    $query = $query . ' and productid="' . $productid . '"';
    $query = $query . ' order by ';
    #if ($_SESSION['ds_useemplacement']) { $query = $query . 'placementrank asc,'; }
    $query = $query . 'arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc';
    $query_prm = array();
    ### doquery select
    $connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;
    $dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true); # only for mysql
    $sth_temp = $dbh_temp->prepare('SET time_zone = "' . $dauphin_timezone . '"');
    $sth_temp->execute();
    $sth_temp = $dbh_temp->prepare($query);
    $sth_temp->execute($query_prm);
    $query_result = $sth_temp->fetchAll();
    $num_results = $sth_temp->rowCount();
    $dbh_temp = NULL;
    ###
    $showemptylots = 1; $currentpurchasebatchid = -1;
    for ($y=0; $y < $num_results; $y++)
    {
      $row = $query_result[$y];
      if ($showemptylots > -1)
      {
        $lotsize = $row['amount'];
        $stock = $stock - $lotsize;
        $amountleft = $lotsize;
        if ($stock < 0) { $amountleft = $amountleft + $stock; }
        if ($amountleft < 0) { $amountleft = 0; }

        if ($amountleft > 0) { $currentpurchasebatchid = $row['purchasebatchid']; }

        if ($stock <= 0) { $showemptylots--; }
      }
    }
    if ($currentpurchasebatchid > 0)
    {
      $query = 'update product set currentpurchasebatchid=? where productid=?';
      $query_prm = array($currentpurchasebatchid, $productid);
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
    
    ################################################
    
  }
}

#echo 'Wing Chong stock calc done';

?>