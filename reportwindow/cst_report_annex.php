<?php

$PA['month'] = 'uint';
$PA['year'] = 'uint';
$PA['bystatus'] = 'uint';
require('inc/readpost.php');

$total1 = $total2 = 0;

$query = 'select idtahiti,socialsecuritynumber as ssn from companyinfo where companyinfoid=1';
$query_prm = array();
require('inc/doquery.php');
$idtahiti = $query_result[0]['idtahiti'];
$ssn = $query_result[0]['ssn'];

$title = 'ANNEXE CST ' . $month . ' / ' . $year;
showtitle_new($title);
echo d_table('report');

#echo '<p>Numéro Employeur : '.$ssn;
echo '<p>No Tahiti : '.d_output($idtahiti);
echo '<p>Periode : ' . d_trad('month2_'.$month) . ' ' . $year;


$query = 'select payslipid,payslip.employeeid,employeename,employeefirstname,employeemiddlename,gross_salary,exitdate,payslipid
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
  $query = 'select dn,dateofbirth,placeofbirth from employeepersoinfos where employeeid=?';
  $query_prm = array($main_result[$i]['employeeid']);
  require('inc/doquery.php');
  if ($num_results)
  {
    $main_result[$i]['dateofbirth'] = $query_result[0]['dateofbirth'];
    $main_result[$i]['placeofbirth'] = $query_result[0]['placeofbirth'];
  }
  else
  {
    $main_result[$i]['dateofbirth'] = '';
    $main_result[$i]['placeofbirth'] = '';
  }
  if ($main_result[$i]['exitdate'] == '0000-00-00') { $main_result[$i]['exitdate'] = ''; }
  $query = 'select bracket0+bracket1+bracket2+bracket3+bracket4+bracket5+bracket6+bracket7+bracket8+bracket9+bracket10 as cst
  from payslip_tax_bracket where payslipid=?';
  $query_prm = array($main_result[$i]['payslipid']);
  require('inc/doquery.php');
  $main_result[$i]['cst'] = $query_result[0]['cst'];
  
  $total1 += $main_result[$i]['gross_salary'];
  $total2 += $main_result[$i]['cst'];
}

echo '
<tr><td colspan=4 align=center><b>Identification des personnes bénéficiaires des revenus soumis à la CST
<td rowspan=2 align=center><b>Fonction
<td rowspan=2 align=center><b>Nature des<br>revenus
<td rowspan=2 align=center><b>Montant brut des revenus<br>versés au titre du mois<br>concerné
<td rowspan=2 align=center><b>CST due au titre<br>su mois<br>concerné
<td rowspan=2 align=center><b>Date de la sortie<br>du régime CST
<tr><td align=center><b>Nom Patronymique<td align=center><b>Nom marital<td align=center><b>Prénoms<td align=center><b>Date et lieu de naissance
';
for ($i=0; $i < $num_results_main; $i++)
{
  echo d_tr();
  echo d_td($main_result[$i]['employeename']);
  echo d_td();
  $kladd = $main_result[$i]['employeefirstname'];
  if ($main_result[$i]['employeemiddlename'] != '') { $kladd .= ', '.$main_result[$i]['employeemiddlename']; }
  echo d_td($kladd);
  $kladd = datefix($main_result[$i]['dateofbirth'],'short').' '.d_output($main_result[$i]['placeofbirth']);
  echo d_td($kladd);
  echo d_td(3,'center'); # 1 dirigeant 2 dirigeant associé 3 salarié 4 autre
  echo d_td(3,'center'); # 1 revenus de dirigeant 2 traitment 3 salaries 4 pensions 5 rentes 6 indemnité
  echo d_td($main_result[$i]['gross_salary'],'currency'); #salaire net
  echo d_td($main_result[$i]['cst'],'currency'); #cst
  echo d_td($main_result[$i]['exitdate'],'date');
}
echo d_tr(1),d_td('Total','',6);
echo d_td($total1,'currency'),d_td($total2,'currency'),d_td();
echo d_table_end();

?>