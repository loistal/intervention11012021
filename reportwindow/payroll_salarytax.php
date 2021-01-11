<?php

if (!$_SESSION['ds_ishrsuperuser']) { exit; }

require('preload/employee.php');

$PA['month'] = 'uint';
$PA['year'] = 'uint';
require('inc/readpost.php');

$query = 'select idtahiti,socialsecuritynumber as ssn from companyinfo where companyinfoid=1';
$query_prm = array();
require('inc/doquery.php');
$idtahiti = $query_result[0]['idtahiti'];
$ssn = $query_result[0]['ssn'];

$title = 'Rapport Cotisations Salariales '.$month.' / '.$year;
showtitle_new($title);
echo d_table('report');

echo '<p>Numéro Employeur : '.$ssn;
echo '<p>No Tahiti : '.d_output($idtahiti);
echo '<p>Periode : ' . d_trad('month2_'.$month) . ' ' . $year;

$query = 'select payslip.payslipid,employeename,employeemiddlename,employeefirstname
from payslip,employee
where payslip.employeeid=employee.employeeid
and status=1 and payslipdate=?
order by employeename';
$query_prm = array(d_builddate(1,$month,$year));
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
echo d_table('report');
for ($i=0; $i < $num_results_main; $i++)
{
  echo d_tr();
  echo d_td($main_result[$i]['employeename']);
  echo d_td($main_result[$i]['employeefirstname']);
  $query = 'select sum(value) as ourvalue from payslip_line_net
  where payslipid=? and `rank`>=100 and `rank`<=500';
  $query_prm = array($main_result[$i]['payslipid']);
  require('inc/doquery.php');
  echo d_td($query_result[0]['ourvalue'],'currency');
}
echo d_table_end();

?>