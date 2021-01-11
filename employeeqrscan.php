<?php

if ($_SESSION['ds_userid'] < 1) { require('logout.php'); exit; }

require ('inc/standard.php');
require ('inc/top.php');
echo '<h2 style="font-size: 500%">';

$employeeid = (int) $_GET['employeeid'];

$query = 'select employeeid,employeename,employeefirstname,curdate() as date,curtime() as time from employee where employeeid=?';
$query_prm = array($employeeid);
require('inc/doquery.php');
if (!$num_results)
{
  echo 'Employé non identifié.';
}
else
{
  $employeeid = $query_result[0]['employeeid'];
  $date = $query_result[0]['date'];
  $time = $query_result[0]['time'];
  $name = $query_result[0]['employeename'];
  if ($query_result[0]['employeefirstname']) { $name .= ' ' . $query_result[0]['employeefirstname']; }
  echo d_output($name) . '<br>' . datefix($date) . '<br>' . substr($time,0,5);
  
  $query = 'insert into badgelog (ismanual,badgedate,badgetime,employeeid) values (1,?,?,?)';
  $query_prm = array($date,$time,$employeeid);
  require('inc/doquery.php');
}

echo '</h2>';
require ('inc/bottom.php');

?>