<?php

$PA['month'] = 'uint';
$PA['year'] = 'uint';
$PA['bystatus'] = 'uint';
$PA['format'] = 'uint';
$PA['noentrydate'] = 'uint';
require('inc/readpost.php');

$total = 0;

$title = 'Rapport Solde Congès '. d_trad('month2_'.$month) . ' ' . $year;
showtitle_new($title);
echo d_table('report');

$query = 'select payslip.employeeid,employeename,employeefirstname,employeemiddlename,vacationdays
from payslip,employee
where payslip.employeeid=employee.employeeid and vacationdays<>0
and month(payslipdate)=? and year(payslipdate)=?';
#if ($bystatus) { $query .= ' and status=1'; }
$query .= ' order by employeename,employeefirstname,employeemiddlename';
$query_prm = array($month,$year);
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

echo '<thead><th colspan=2>Employé<th>Solde Congès</thead>';
for ($i=0; $i < $num_results_main; $i++)
{
  echo d_tr();
  echo d_td($main_result[$i]['employeename']);
  echo d_td($main_result[$i]['employeefirstname']);
  echo d_td($main_result[$i]['vacationdays'], 'decimal');
  $total += $main_result[$i]['vacationdays'];
}
echo d_tr(), d_td($total, 'decimal', 3);
echo d_table_end();

?>