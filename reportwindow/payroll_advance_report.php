<?php

$PA['month'] = 'uint';
$PA['year'] = 'uint';
require('inc/readpost.php');

$total = 0;

$reason = 'Avance ' . d_trad('month2_'.$month) . ' ' . $year;

$title = 'Rapport avances '.$month.' / '.$year;
showtitle_new($title);
echo d_table('report');

echo '<thead><th>Matricule<th>Nom<th>RIB<th>Montant<th>Motif</thead>';

$query = 'select payslip_advance.employeeid,employeename,employeefirstname,employeemiddlename,advance,referencenumber
,salary_account_title,salary_account
from payslip_advance,employee
where payslip_advance.employeeid=employee.employeeid
and month=? and year=?
order by employeename,employeefirstname,employeemiddlename';
$query_prm = array($month,$year);
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

for ($i=0; $i < $num_results_main; $i++)
{
  echo d_tr();
  echo d_td($query_result[$i]['referencenumber']);
  if ($query_result[$i]['salary_account_title'] != '') { echo d_td($query_result[$i]['salary_account_title']); }
  else { echo d_td($query_result[$i]['employeename'].' '.$query_result[$i]['employeefirstname']); }
  echo d_td($query_result[$i]['salary_account']);
  echo d_td($query_result[$i]['advance'], 'currency');
  echo d_td($reason);
  /*
  echo d_tr();
  echo d_td($main_result[$i]['referencenumber']);
  echo d_td($main_result[$i]['employeename']);
  echo d_td($main_result[$i]['employeefirstname']);
  echo d_td($main_result[$i]['advance'], 'currency');
  */
  $total += $main_result[$i]['advance'];
}
echo d_tr(1);
echo d_td('Total','',3);
echo d_td($total, 'currency');
echo d_td();
echo d_table_end();

?>