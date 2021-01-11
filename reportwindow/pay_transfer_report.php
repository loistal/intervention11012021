<?php

require('preload/bank.php');
require('preload/bankaccount.php');
require('preload/paymenttype.php');

$PA['all'] = 'uint';
$PA['month'] = 'uint';
$PA['year'] = 'uint';
$PA['paymenttypeid'] = 'int';
$PA['bankaccountid'] = 'int';
require('inc/readpost.php');

$line = 0; $total = 0;

$query = 'select idtahiti,socialsecuritynumber as ssn from companyinfo where companyinfoid=1';
$query_prm = array();
require('inc/doquery.php');
$idtahiti = $query_result[0]['idtahiti'];
$ssn = $query_result[0]['ssn'];

$reason = 'Salaire ' . d_trad('month2_'.$month) . ' ' . $year;

$title = 'Paiements à traiter';
if ($paymenttypeid > 0) { $title .= ' ('.$paymenttypeA[$paymenttypeid].')'; }
showtitle_new($title);
echo d_table('report');

echo '<p>Société : '.d_output($_SESSION['ds_customname']);
if ($bankaccountid > 0)
{
  echo '<p>Compte : '.d_output($bankA[$bankaccount_bankidA[$bankaccountid]].' '.$bankaccountA[$bankaccountid]);
}
echo '<p>Periode : ' . d_trad('month2_'.$month) . ' ' . $year;

echo '<thead><th>Matricule<th>Nom<th>RIB<th>Montant<th>Motif</thead>';

$query = 'select payslip.employeeid,net_salary,salary_account_title,salary_account,salary_bankid
,employeename,employeefirstname,referencenumber
from payslip,employee
where payslip.employeeid=employee.employeeid
and month(payslipdate)=? and year(payslipdate)=?';
$query_prm = array($month,$year);
if ($bankaccountid >= 0) { $query .= ' and bankaccountid=?'; array_push($query_prm,$bankaccountid); }
if ($paymenttypeid >= 0) { $query .= ' and paymenttypeid=?'; array_push($query_prm,$paymenttypeid); }
if ($all != 1) { $query .= ' and status=1'; }
$query .= ' order by employeename,employeefirstname';
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if ($query_result[$i]['net_salary'] > 0)
  {
    $line++;
    $total += $query_result[$i]['net_salary'];
    echo d_tr();
    echo d_td($query_result[$i]['referencenumber']);
    if ($query_result[$i]['salary_account_title'] != '') { echo d_td($query_result[$i]['salary_account_title']); }
    else { echo d_td($query_result[$i]['employeename'].' '.$query_result[$i]['employeefirstname']); }
    echo d_td($query_result[$i]['salary_account']);
    echo d_td($query_result[$i]['net_salary'], 'currency');
    echo d_td($reason);
  }
}
echo d_tr(1);
echo d_td($line.' employés','',3);
echo d_td($total, 'decimal');
echo d_td();
echo d_table_end();

?>