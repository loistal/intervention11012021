<?php
require('preload/clientcategory.php');
require('preload/island.php');
require('preload/employee.php');

$ORDER_BY_SALES = 1;
$ORDER_BY_CLIENTNAME = 2;
$ORDER_BY_CLIENTCATEGORY = 3;
$ORDER_BY_ISLANDNAME = 4;

$choice = $_POST['choice'] + 0;
$clientcategoryid = $_POST['clientcategoryid'] + 0;
$islandid = $_POST['islandid'] + 0;
$employeeid = $_POST['employeeid'] + 0;
$datename = 'startdate';require('inc/datepickerresult.php');
$datename = 'stopdate';require('inc/datepickerresult.php');
$orderby = $_POST['orderby'] + 0;

$title = d_trad('topclientsreport');
showtitle($title);
echo '<h2>' . $title . '</h2><br>';

$ourparams = d_trad('topparam',$choice) . '&nbsp;';
$query = 'select sum(ih.invoiceprice-ih.invoicevat),c.clientid,c.clientname,c.clientcategoryid,t.islandid from invoicehistory ih,client c,town t';
#do the same request for sales who are return
$query_isreturn = 'select sum(ih.invoiceprice-ih.invoicevat) from invoicehistory ih';
$query_prm = array();
$query_prm_isreturn = array();

if ($orderby == $ORDER_BY_ISLANDNAME)
{
  $query .= ',island i';
}

if ($orderby == $ORDER_BY_CLIENTCATEGORY)
{
  $query .= ',clientcategory cc';
}

$query .= ' where c.clientid = ih.clientid and t.townid = c.townid';
$query_isreturn .= ' where ih.clientid=?';
#todo later: add clientid to query_prm_isreturn

if ($clientcategoryid > 0) 
{ 
  $ourparams .= $clientcategoryA[$clientcategoryid] . '&nbsp;';  
  $query .= ' and c.clientcategoryid = ?';  
  array_push($query_prm,$clientcategoryid);
}

if ($islandid > 0)
{
  $ourparams .= $islandA[$islandid] . '&nbsp;';  
  $query .= '  and t.islandid = ?';
  array_push($query_prm,$islandid);
}

if ($orderby == $ORDER_BY_ISLANDNAME)
{
  $query .= ' and i.islandid = t.islandid';
} 

if ($orderby == $ORDER_BY_CLIENTCATEGORY)
{
  $query .= 'and cc.clientcategoryid = c.clientcategoryid';
}

if ($employeeid > 0)
{
  $ourparams .= $employeeA[$employeeid] . '&nbsp;';  
  $query .= ' and ih.employeeid = ?';
  $query_isreturn .= ' and ih.employeeid = ?';  
  array_push($query_prm,$employeeid);  
  array_push($query_prm_isreturn,$employeeid);  
}

if($startdate != '')
{
  $ourparams .= '<br>' . d_trad('fromto',array(datefix2($startdate),datefix2($stopdate)));
  $query .= ' and ih.accountingdate >= ?';
  $query_isreturn .= ' and ih.accountingdate >= ?'; 
  array_push($query_prm,$startdate);  
  array_push($query_prm_isreturn,$startdate);  
}

if($stopdate != '')
{
  $query .= ' and ih.accountingdate <= ?';
  $query_isreturn .= ' and ih.accountingdate <= ?';
  array_push($query_prm,$stopdate);  
  array_push($query_prm_isreturn,$stopdate);  
}

$query .= ' and confirmed=1 and cancelledid=0 and ih.isreturn = 0';
$query_isreturn .= ' and confirmed=1 and cancelledid=0 and ih.isreturn = 1';

$query .= ' group by ih.clientid';
$query .= ' order by ';

switch ($orderby)
{
  case $ORDER_BY_SALES:
    $query .= ' sum(ih.invoiceprice-ih.invoicevat) desc';
    break;
  case $ORDER_BY_CLIENTNAME:
    $query .= ' UPPER(c.clientname),sum(ih.invoiceprice-ih.invoicevat) desc';
    break;
  case $ORDER_BY_CLIENTCATEGORY:
    $query .= ' UPPER(cc.clientcategoryname),sum(ih.invoiceprice-ih.invoicevat) desc';
    break;
  case $ORDER_BY_ISLANDNAME:
    $query .= ' UPPER(i.islandname),sum(ih.invoiceprice-ih.invoicevat) desc';
    break;
}

echo '<p>' . $ourparams . '</p>';
require ('inc/doquery.php');
$numsales = $num_results; $salesA = $query_result;
$sumA = array();

if ($numsales > 0)
{
  #1rst loop: for each client, check if there are return to substract them to sum of sales 
  for ($i=0;$i<$numsales;$i++)
  {
    $clientid = $salesA[$i]['clientid'];
    # for each client, check if there are return to substract them to sum of sales
    $query = $query_isreturn;   
    $query_prm = $query_prm_isreturn;
    #add client id at the beginning of the array
    array_unshift($query_prm,$clientid);
    
    require('inc/doquery.php');
    $numreturns = $num_results; $returns = $query_result;
    $sumreturns = 0;
    if ($numreturns == 1)
    {    
      $sumreturns = $returns[0]['sum(ih.invoiceprice-ih.invoicevat)'] +0;
    }
    $sum = $salesA[$i]['sum(ih.invoiceprice-ih.invoicevat)'] - $sumreturns;
    array_push($sumA,$sum); # to be used for graph   
  }  
  
  #2nd loop to sort array
  $sortedsalesA = array();  
  for ($i=0;$i<$numsales;$i++)
  {
    #create an array to be sorted
    # key = $i
    # value depends on order by 
    switch ($orderby)
    {
      case $ORDER_BY_SALES:
        array_push($sortedsalesA,$sumA[$i]);
        break;
      case $ORDER_BY_CLIENTNAME:
        array_push($sortedsalesA,$salesA[$i]['clientname']);
        break;
      case $ORDER_BY_CLIENTCATEGORY:
        array_push($sortedsalesA,$salesA[$i]['clientcategoryname']);
        break;
      case $ORDER_BY_ISLANDNAME:
        array_push($sortedsalesA,$salesA[$i]['islandname']);
        break;
    }    
  }
  arsort($sortedsalesA);
  echo '<table class=report>';
  echo '<thead><th colspan=2>' . d_trad('client') . '</th><th>' . d_trad('sales') . '</th><th>' . d_trad('clientcategory') . '</th><th>' . d_trad('island') . '</th></thead>';
  #3rd loop to display the array
  $i = 0;
  foreach($sortedsalesA as $key=>$value)
  {
    if ($i < $choice)
    {
      $row = $salesA[$key];
      $clientid = $row['clientid'];
      echo d_tr();
      echo d_td_old($row['clientid']); 
      echo d_td_old(d_output(d_decode($row['clientname']))); 
      echo d_td_old(myfix($sumA[$key]),1);
      $clientcategoryid = $row['clientcategoryid'];
      if (isset($clientcategoryA[$clientcategoryid])) { echo d_td_old($clientcategoryA[$clientcategoryid]); }
      else { echo d_td_old(); }
      $islandid = $row['islandid'];
      echo d_td_old($islandA[$islandid]);
      $i++;
    }
  }
  echo '</table>';
  echo '<br><br>';
}
else
{
  echo '<br>' .d_trad('noresult');
}
