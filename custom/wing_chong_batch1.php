<?php

/*
Nous confirmons le calcul sur les 12 derniers mois :

- par contre s'il y a 1 mois avec 0 ventes, le calcul se fera sur 11 mois seulement.

- de meme, s'il y a 1 mois avec ventes inferieures a la moitie, le calcul se fera sur 11 mois seulement.

"Je peux le faire, par contre pour un produit avec un mois de vente exceptionels, le calcul va dire que la moyenne = ce mois exceptionel"

dans le cas d'une vente exceptionnelle superieure a la moyenne, le calcul se fera normalement, c'est a dire sur la moyenne des 12.
*/

set_time_limit(3600);

# build a valid date, format yy-mm-dd
function d_builddate($day,$month,$year)
{
  $day = $day + 0; $month = $month + 0;
  switch ($month)
  {
    case 2:
      if ($year%4 == 0) { $maxday = 29; }
      else { $maxday = 28; }
    break;
    case 4:
    case 6:
    case 9:
    case 11:
      $maxday = 30;
    break;
    default:
      $maxday = 31;
    break;
  }
  if ($day > $maxday) { $day = $maxday; }
  if ($day < 10) { $day = '0' . $day; }
  if ($month < 10) { $month = '0' . $month; }
  $date = $year . '-' .  $month . '-' . $day;
  return $date;
}

$dauphin_hostname = 'localhost';
$dauphin_login = 'temsaas';
$dauphin_password = 'wico';
$dauphin_instancename = 'temsaas_wico';
$dauphin_timezone = 'Pacific/Tahiti';
$connectstring_temp = 'mysql:host=' . $dauphin_hostname . ';dbname=' . $dauphin_instancename;

$query = 'select month(curdate()) as month,year(curdate()) as year';
$query_prm = array();
### doquery select
$dbh_temp = new PDO($connectstring_temp, $dauphin_login, $dauphin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$dbh_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh_temp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$query_result = $sth_temp->fetchAll();
$num_results = $sth_temp->rowCount();
###
$endmonth = $query_result[0]['month'] - 1;
$endyear = $query_result[0]['year'];
if ($endmonth == 0) { $endmonth = 12; $endyear--; }
$startmonth = $endmonth + 1;
$startyear = $endyear - 1;
if ($startmonth == 13) { $startmonth = 1; $startyear++; }
$start = d_builddate(1,$startmonth,$startyear);
$stop = d_builddate(31,$endmonth,$endyear);

$query = 'select p.currentstock,p.productid,p.numberperunit,p.netweightlabel,p.productname,pf.productfamilyname,pg.productfamilygroupname,pd.productdepartmentname,u.unittypename,u.displaymultiplier as dmp
from product p,productfamily pf,productfamilygroup pg,productdepartment pd,unittype u
where p.unittypeid=u.unittypeid and p.productfamilyid=pf.productfamilyid
and pf.productfamilygroupid=pg.productfamilygroupid and pg.productdepartmentid=pd.productdepartmentid
and p.discontinued=0';
$query_prm = array();
### doquery select
$sth_temp = $dbh_temp->prepare($query);
$sth_temp->execute($query_prm);
$main_result = $sth_temp->fetchAll();
$num_results_main = $sth_temp->rowCount();
###
for ($y=0; $y < $num_results_main; $y++)
{
  $sales = array();
  for ($i=1;$i <= 12; $i++)
  {
    $sales[$i] = 0;
  }
  #echo "\r\n" . $main_result[$y]['productid'] . "\r\n";
  $query = 'select sum(quantity) as sales,month(accountingdate) as month
  from invoicehistory,invoiceitemhistory
  where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and isreturn=0 and cancelledid=0
  and invoiceitemhistory.productid=? and accountingdate>=? and accountingdate<=? group by month';
  $query_prm = array($main_result[$y]['productid'],$start,$stop);
  ### doquery select
  $sth_temp = $dbh_temp->prepare($query);
  $sth_temp->execute($query_prm);
  $query_result = $sth_temp->fetchAll();
  $num_results = $sth_temp->rowCount();
  ###
  for ($i=0;$i < $num_results; $i++)
  {
    $month = $query_result[$i]['month'];
    $sales[$month] += $query_result[$i]['sales'];
  }
  $query = 'select sum(quantity) as sales,month(accountingdate) as month
  from invoicehistory,invoiceitemhistory
  where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and isreturn=1 and returntostock=0 and cancelledid=0
  and invoiceitemhistory.productid=? and accountingdate>=? and accountingdate<=? group by month';
  $query_prm = array($main_result[$y]['productid'],$start,$stop);
  ### doquery select
  $sth_temp = $dbh_temp->prepare($query);
  $sth_temp->execute($query_prm);
  $query_result = $sth_temp->fetchAll();
  $num_results = $sth_temp->rowCount();
  ###
  for ($i=0;$i < $num_results; $i++)
  {
    $month = $query_result[$i]['month'];
    $sales[$month] -= $query_result[$i]['sales'];
  }
  $zero_months = array_count_values($sales);
  $divider = 12;
  if (isset($zero_months[0])) { $divider = 12 - $zero_months[0]; }
  if ($divider == 0)
  {
    $avgmonthly = 0;
  }
  else
  {
    $avgmonthly = array_sum($sales) / $divider;
    # now exclude months with less than half the average
    $half = $avgmonthly / 2;
    #echo $half;
    for ($month=1;$month <= 12; $month++)
    {
      if ($sales[$month] < $half) { unset($sales[$month]); }
      #echo 'month ',$month,' ',$sales[$month],"\r\n";
    }
    if (count($sales) > 1)
    {
      $avgmonthly = array_sum($sales) / count($sales);
    }
  }
  
  $query = 'update product set avgmonthly=? where productid=?';
  $query_prm = array($avgmonthly, $main_result[$y]['productid']);
  ### doquery update
  $sth_temp = $dbh_temp->prepare($query);
  $sth_temp->execute($query_prm);
  ###
  #echo $avgmonthly;
}

$dbh_temp = NULL;

?>