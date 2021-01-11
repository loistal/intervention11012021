<?php

$PA['month'] = 'uint';
$PA['year'] = 'uint';
$PA['bystatus'] = 'uint';
$PA['format'] = 'uint';
$PA['noentrydate'] = 'uint';
require('inc/readpost.php');

$total1 = $total2 = 0;

$query = 'select idtahiti,socialsecuritynumber as ssn from companyinfo where companyinfoid=1';
$query_prm = array();
require('inc/doquery.php');
$idtahiti = $query_result[0]['idtahiti'];
$ssn = $query_result[0]['ssn'];

$title = 'DECLARATION DE SALAIRES ET DE MAIN D\'OEUVRE';
showtitle_new($title);
echo d_table('report');

echo '<p>Numéro Employeur : '.$ssn;
echo '<p>No Tahiti : '.d_output($idtahiti);
echo '<p>Periode : ' . d_trad('month2_'.$month) . ' ' . $year;


$query = 'select payslip.employeeid,employeename,employeefirstname,employeemiddlename,basesalary,gross_salary,hoursworked,hiringdate,exitdate
from payslip,employee
where payslip.employeeid=employee.employeeid
and month(payslipdate)=? and year(payslipdate)=? and (exitdate>=? or exitdate is null or exitdate="0000-00-00")';
if ($bystatus) { $query .= ' and status=1'; }
$query .= ' order by employeename,employeefirstname,employeemiddlename';
$query_prm = array($month,$year,d_builddate(1,$month,$year));
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

for ($i=0; $i < $num_results_main; $i++)
{
  #,dn,dateofbirth employeepersoinfos
  $query = 'select dn,dateofbirth from employeepersoinfos where employeeid=?';
  $query_prm = array($main_result[$i]['employeeid']);
  require('inc/doquery.php');
  if ($num_results)
  {
    $main_result[$i]['dn'] = $query_result[0]['dn'];
    $main_result[$i]['dateofbirth'] = $query_result[0]['dateofbirth'];
  }
  else
  {
    $main_result[$i]['dn'] = '';
    $main_result[$i]['dateofbirth'] = '';
  }
  
  if ($main_result[$i]['hiringdate'] == '0000-00-00') { $main_result[$i]['hiringdate'] = ''; }
  if ($main_result[$i]['exitdate'] == '0000-00-00') { $main_result[$i]['exitdate'] = ''; }
  
  if ($_SESSION['ds_customname'] == 'Pacific Batiment' || $_SESSION['ds_customname'] == 'Espace Paysages'
  || $_SESSION['ds_customname'] == 'Jurion Protection')
  {
    if (($main_result[$i]['hoursworked'] - floor($main_result[$i]['hoursworked'])) == 0.5) { $main_result[$i]['hoursworked'] = floor($main_result[$i]['hoursworked']); }
    $main_result[$i]['hoursworked'] = round($main_result[$i]['hoursworked']);
  }
}
  
if ($format == 0)
{
  echo '<thead><th colspan=2>1<th>2<th>3<th>4<th>5<th>6<th>7</thead>';
  echo '<thead><th>DN<th>IDENTITES<th>Heures Base<th>Salaire < 150 000<th>Heures Brut<th>Salaires Brut<th>Date de sortie<th>Observations</thead>';
  for ($i=0; $i < $num_results_main; $i++)
  {
    echo d_tr();
    echo d_td($main_result[$i]['dn']);
    echo d_td($main_result[$i]['employeename'].' / '.$main_result[$i]['employeemiddlename'].' / '.$main_result[$i]['employeefirstname']);
    echo d_td(); # base minus arret travail, no heures sup   TODO deduct heures sup
    $kladd = ''; if ($main_result[$i]['basesalary'] < 150000) { $kladd = $main_result[$i]['basesalary']; }
    echo d_td($kladd, 'currency');
    echo d_td($main_result[$i]['hoursworked'],'decimal');
    $kladd = ''; if ($main_result[$i]['basesalary'] >= 150000) { $kladd = $main_result[$i]['gross_salary']; }
    echo d_td($kladd, 'currency');
    if ($noentrydate) { echo d_td(); }
    else { echo d_td($main_result[$i]['exitdate'],'date'); }
    echo d_td();
  }
}
else
{
  echo '<thead><th><th><th>NOM<th>PRENOM<th>DATE_NAISSANCE<th>DN<th>HEURE TRAVAILLE<th>SALAIRE BRUT
  <th>PERIODE TRAVAILLE<th>TYPE DE DECLARATION<th>DATE ENTREE<th>DATE SORTIE</thead>';
  for ($i=0; $i < $num_results_main; $i++)
  {
    $total1 += $main_result[$i]['hoursworked'];
    $total2 += $main_result[$i]['gross_salary'];
    echo d_tr();
    echo d_td(str_replace('-','/',$_SESSION['ds_curdate']));
    echo d_td($i+1);
    echo d_td($main_result[$i]['employeename']);
    echo d_td($main_result[$i]['employeefirstname']);
    echo d_td(str_replace('-','/',$main_result[$i]['dateofbirth']));
    echo d_td($main_result[$i]['dn']);
    echo d_td($main_result[$i]['hoursworked'],'decimal');
    echo d_td($main_result[$i]['gross_salary'], 'currency');
    echo d_td(mb_convert_case(d_trad('month'.$month),MB_CASE_UPPER,"UTF-8"));
    echo d_td("1");
    if ($noentrydate) { echo d_td(); }
    else { echo d_td(str_replace('-','/',$main_result[$i]['hiringdate'])); }
    if ($noentrydate) { echo d_td(); }
    else { echo d_td(str_replace('-','/',$main_result[$i]['exitdate'])); }
  }
  echo d_tr(1);
  echo d_td($num_results_main.' employés','',6);
  echo d_td();#$total1, 'decimal'
  echo d_td($total2, 'decimal');
  echo d_td('','',4);
}


echo d_table_end();

?>