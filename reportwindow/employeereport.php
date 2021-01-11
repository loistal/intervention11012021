<?php
require('reportwindow/employeereport_cf.php');

$PA['qualificationid'] = 'int';
require('inc/readpost.php');

session_write_close();

$title = 'Rapport employé(e)s';
showtitle_new($title);

require('inc/showparams.php');

### 2020 07 18 stupid, but outer join select * does not give all ids
$query = 'select employeeid from employee where deleted=0';
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i<$num_results_main; $i++)
{
  $query = 'select employeeid from employeepersoinfos where employeeid=?';
  $query_prm = array($main_result[$i]['employeeid']);
  require('inc/doquery.php');
  if ($num_results == 0)
  {
    $query = 'insert into employeepersoinfos (employeeid) values (?)';
    $query_prm = array($main_result[$i]['employeeid']);
    require('inc/doquery.php');
  }
}
###

$query_prm = array();
$query = 'select * from employee,employeepersoinfos';
if ($qualificationid > 0) { $query .= ' join employeequalification on employee.employeeid=employeequalification.employeeid'; }
$query .= ' where employee.employeeid=employeepersoinfos.employeeid';
if ($qualificationid > 0) { $query .= ' and employeequalification.qualificationid=?'; array_push($query_prm,$qualificationid); }
$query .= ' and employee.deleted=0';
require('inc/doquery.php');
$row = $query_result; $num_rows = $num_results; unset($query_result, $num_results);

### 2020 07 18
# detect if we need solde des congés
# if so add it to $row
$query = 'select showfield from cf_report where reportid=? and userid=? and showfield=28';
$query_prm = array($reportid, $_SESSION['ds_userid']);
require('inc/doquery.php');
if ($num_results)
{
  for ($i=0; $i<$num_rows; $i++)
  {
    $query = 'select vacationdays from payslip where employeeid=? order by payslipid desc limit 1';
    $query_prm = array($row[$i]['employeeid']);
    require('inc/doquery.php');
    if ($num_results) { $row[$i]['vacationdays'] = $query_result[0]['vacationdays']; }
    else { $row[$i]['vacationdays'] = 0; }
  }
}
###

require('inc/showreport.php');
?>