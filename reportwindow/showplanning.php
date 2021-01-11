<?php

require('preload/employee.php');
require('preload/clientschedulecat.php');

$clientschedulecatid = $_POST['clientschedulecatid']+0;
$employeeid = $_POST['employeeid']+0;
$employeelink = $_POST['employeelink']+0;
if ($employeelink == 2) { $employeelinkterm = $_SESSION['ds_term_clientemployee2']; }
else { $employeelinkterm = $_SESSION['ds_term_clientemployee1']; }
$datename = 'scheduledate';
require('inc/datepickerresult.php');

$ourtitle = 'Planning ' . datefix2($scheduledate);
showtitle($ourtitle);
echo '<h2>' . $ourtitle . '</h2>';

if ($employeeid > -1)
{
  if ($employeeid == 0) { $employeename = '&lt;Aucun&gt;'; }
  else { $employeename = $employeeA[$employeeid]; }
  echo '<p><b>Employé ' . $employeelinkterm . ':</b> ' . $employeename . '</p>';
}

if ($clientschedulecatid > -1)
{
  if ($clientschedulecatid == 0) { $clientschedulecatname = '&lt;Aucun&gt;'; }
  else { $clientschedulecatname = $clientschedulecatA[$clientschedulecatid]; }
  echo '<p><b>Catégorie:</b> ' . $clientschedulecatname . '</p>';
}

$temp_mktime = strtotime($scheduledate);
$dayofweek = date("w",$temp_mktime);
$dayofmonth = mb_substr($scheduledate,8,2)+0;
$numdaysinmonth = date("t",$temp_mktime);
$daybeforelastweek = $numdaysinmonth - 7;

/*
echo '<br>dayofweek= '.$dayofweek;
echo '<br>dayofmonth= '.$dayofmonth;
echo '<br>numdaysinmonth= '.$numdaysinmonth;
*/

$query = 'select daytype,extraaddressid,clientschedule.clientid,clientname,scheduletime,quarter,address,telephone,schedulecomment from clientschedule,client where clientschedule.clientid=client.clientid and clientschedule.deleted=0';
$query = $query . ' and notuntildate<=?';
$query_prm = array($scheduledate);
if ($employeeid > -1)
{
  if ($employeelink == 2) { $query = $query . ' and client.employeeid2=?'; }
  else { $query = $query . ' and client.employeeid=?'; }
  array_push($query_prm, $employeeid);
}
$query = $query . ' and (';
$query = $query . '(periodic=0 and scheduledate=?)'; # specific date
$query = $query . ' or ';
$query = $query . '(periodic=1 and daytype=1 and dayofweek=?)'; # every week
$query = $query . ' or ';
$query = $query . '(periodic=1 and daytype=2 and dayofweek=? and mod(weekofyear(?),2)=0)'; # even weeks
$query = $query . ' or ';
$query = $query . '(periodic=1 and daytype=3 and dayofweek=? and mod(weekofyear(?),2)=1)'; # odd weeks
array_push($query_prm,$scheduledate,$dayofweek,$dayofweek,$scheduledate,$dayofweek,$scheduledate);
#echo '<br>checking $dayofweek ('.$dayofmonth.') < 7';
if ($dayofmonth <= 7)
{
  $query = $query . ' or ';
  $query = $query . '(periodic=1 and daytype=4 and dayofweek=?)'; # first week of month
  array_push($query_prm, $dayofweek);
}
#echo '<br>checking $dayofweek ('.$dayofmonth.') > $daybeforelastweek ('.$daybeforelastweek.')';
if ($dayofmonth > $daybeforelastweek)
{
  $query = $query . ' or ';
  $query = $query . '(periodic=1 and daytype=5 and dayofweek=?)'; # last week of month
  array_push($query_prm, $dayofweek);
}
$query = $query . ')';
$query = $query . ' order by scheduletime,clientname';
#var_dump($query_prm);
require ('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
  
echo '<table class="report" border=1 cellpadding=5 cellspacing=5><tr><td><b>Heure</td><td><b>Client</b></td><td><b>Quartier</b></td><td><b>Adresse</b></td><td><b>Téléphone</b></td><td><b>Commentaire</b></td></tr>';
for ($i=0; $i < $num_results_main; $i++)
{
  # change address for extraaddressid > 0
  if ($main_result[$i]['extraaddressid'] > 0)
  {
    $query = 'select * from extraaddress where extraaddressid=?';
    $query_prm = array($main_result[$i]['extraaddressid']);
    require ('inc/doquery.php');
    $main_result[$i]['quarter'] = $query_result[0]['quarter'];
    $main_result[$i]['address'] = $query_result[0]['address'];
    $main_result[$i]['telephone'] = $query_result[0]['telephone'];
  }
  # possibly check for employee = employee on extra address
  echo '<tr><td align=right>' . mb_substr($main_result[$i]['scheduletime'],0,5) . '</td><td>' . $main_result[$i]['clientname'] . ' (' . $main_result[$i]['clientid'] . ')</td><td>';
  echo $main_result[$i]['quarter'] . '</td><td>' . $main_result[$i]['address'] . '</td><td>' . $main_result[$i]['telephone'] . '</td><td>' . $main_result[$i]['schedulecomment'];
  echo '</td></tr>';
}

?>