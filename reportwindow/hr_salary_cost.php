<?php

$PA['startdate'] = 'date';
$PA['stopdate'] = 'date';
$PA['bystatus'] = 'uint';
$PA['format'] = 'uint';
$PA['noentrydate'] = 'uint';
$PA['gross'] = 'uint';
$PA['deduct_rank_50'] = 'uint';
require('inc/readpost.php');

$total1 = $total2 = 0;

$title = 'Coût moyen des employés';
showtitle_new($title);
echo '<p>De : ' . datefix($startdate,'short')
.'<br>À : '.datefix($stopdate,'short').'</p>';

$query = 'select payslipid,payslip.employeeid,employeename,employeefirstname,employeemiddlename,net_salary,hoursworked
,hiringdate,exitdate,referencenumber,gross_salary
from payslip,employee
where payslip.employeeid=employee.employeeid
and payslipdate>=? and payslipdate<=? and (exitdate>=? or exitdate is null or exitdate="0000-00-00")';
$query .= ' order by employeename,employeefirstname,employeemiddlename';
$query_prm = array($startdate,$stopdate,$stopdate);
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

for ($i=0; $i < $num_results_main; $i++)
{
  if ($gross == 1) { $main_result[$i]['net_salary'] = $main_result[$i]['gross_salary']; }
  if ($main_result[$i]['hiringdate'] == '0000-00-00') { $main_result[$i]['hiringdate'] = ''; }
  $cot_pat[$i] = 0;
  $query = 'select `rank`,value,negative,payslip_line_name,payslip_line_comment,value_employer
  from payslip_line_net where payslipid=?';
  $query_prm = array($main_result[$i]['payslipid']);
  require('inc/doquery.php');
  for ($y=0; $y < $num_results; $y++)
  {
    $cot_pat[$i] += $query_result[$y]['value_employer'];
    if ($deduct_rank_50 && $query_result[$y]['rank'] == 50)
    {
      $temp = explode(' heure',ltrim($query_result[$y]['payslip_line_comment'],'('));
      $temp = (double) $temp[0];
      $main_result[$i]['hoursworked'] -= $temp;
    }
  }
  if ($_SESSION['ds_customname'] == 'Pacific Batiment' || $_SESSION['ds_customname'] == 'Espace Paysages'
  || $_SESSION['ds_customname'] == 'Jurion Protection')
  {
    if (($main_result[$i]['hoursworked'] - floor($main_result[$i]['hoursworked'])) == 0.5) { $main_result[$i]['hoursworked'] = floor($main_result[$i]['hoursworked']); }
    $main_result[$i]['hoursworked'] = round($main_result[$i]['hoursworked']);
  }
}

$salary = $cot = $hours = 0;
echo d_table('report');
echo '<thead><th>Matricule<th colspan=2>Nom<th>Date d\'embauce<th>';
if ($gross == 1) { echo 'Salaire Brut'; }
else  { echo 'Salaire Net'; }
echo '<th>Cotisations patronales<th>Coût<th>Heures travaillées<th>Coût moyen / heure</thead>';
for ($i=0; $i < $num_results_main; $i++)
{
  $salary += $main_result[$i]['net_salary'];
  $cot += $cot_pat[$i];
  $hours += $main_result[$i]['hoursworked'];
  if (!isset($main_result[($i+1)]['employeeid']) || $main_result[$i]['employeeid'] != $main_result[($i+1)]['employeeid'])
  {
    echo d_tr();
    echo d_td($main_result[$i]['referencenumber']);
    echo d_td($main_result[$i]['employeename']);
    echo d_td($main_result[$i]['employeefirstname']);
    echo d_td($main_result[$i]['hiringdate'],'date');
    echo d_td($salary, 'currency');
    echo d_td($cot, 'currency');
    echo d_td(d_add($salary,$cot),'currency');
    echo d_td(myround($hours,1),'decimal');
    echo d_td(d_divide(d_add($salary,$cot),$hours),'currency');
    $salary = $cot = $hours = 0;
  }
}
echo d_table_end();

?>