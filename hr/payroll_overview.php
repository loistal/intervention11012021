<?php

require('preload/employee.php');
require('preload/contract.php');
require('preload/job.php');

$PA['unconfirm'] = 'uint';
$PA['employeeid'] = 'uint';
$PA['month'] = 'uint';
$PA['year'] = 'uint';
require('inc/readpost.php');

if ($_SESSION['ds_ishrsuperuser'] && $unconfirm && $employeeid > 0 && $month > 0 && $year > 0)
{
  $payslipdate = d_builddate(1,$month,$year);
  $query = 'update payslip set status=0 where employeeid=? and payslipdate=? limit 1';
  $query_prm = array($employeeid,$payslipdate);
  require('inc/doquery.php');
}

# TODO order employees in some logical manner other than aplhabetical (by contract type? salary amount?)
# TODO maybe show total paid for each employee? with total at end

if (!isset($_SESSION['ds_current_payroll_year'])) { $_SESSION['ds_current_payroll_year'] = (int) substr($_SESSION['ds_curdate'],0,4); }
if (isset($_GET['yearmod'])) { $_SESSION['ds_current_payroll_year'] += (int) $_GET['yearmod']; }
if ($_SESSION['ds_current_payroll_year'] < 2017) { $_SESSION['ds_current_payroll_year'] = 2017; }
if ($_SESSION['ds_current_payroll_year'] < $_SESSION['ds_startyear']) { $_SESSION['ds_current_payroll_year'] = $_SESSION['ds_startyear']; }
if ($_SESSION['ds_current_payroll_year'] > $_SESSION['ds_endyear']) { $_SESSION['ds_current_payroll_year'] = $_SESSION['ds_endyear']; }
$year = $_SESSION['ds_current_payroll_year'];

$query = 'select payslipid,employeeid,month(payslipdate) as month,status from payslip where year(payslipdate)=?';
$query_prm = array($year);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $pA[$query_result[$i]['employeeid']][$query_result[$i]['month']] = $query_result[$i]['payslipid'];
  $p_statusA[$query_result[$i]['employeeid']][$query_result[$i]['month']] = $query_result[$i]['status'];
}

echo '<h2><a href="hr.php?hrmenu=payroll_overview&yearmod=-1">&#8592;</a> ',$year,' <a href="hr.php?hrmenu=payroll_overview&yearmod=1">&#8594;</a></h2>';
echo '<table class=report><thead><th>Employé<th>Emplois<th>Contrat';
for ($i = 1; $i <= 12; $i++)
{
  echo '<th>',datefix(d_builddate(1,$i,$year),'short noday noyear');
}
echo '</thead>';
if ($_SESSION['ds_ishrsuperuser'])
{
  echo d_tr();
  echo d_td('Afficher verrouillés (modèle simplifié)','',3);
  for ($i = 1; $i <= 12; $i++)
  {
    echo d_td_unfiltered('<a href="reportwindow.php?report=payroll_show_all&year='.$year.'&month='.$i.'&status=1" target=_blank>A</a>','center');
  }
  echo d_tr();
  echo d_td('Afficher non-verrouillés (modèle simplifié)','',3);
  for ($i = 1; $i <= 12; $i++)
  {
    echo d_td_unfiltered('<a href="reportwindow.php?report=payroll_show_all&year='.$year.'&month='.$i.'" target=_blank>A</a>','center');
  }
}
$query = 'select employeeid,month(hiringdate) as month,year(hiringdate) as year,jobid
,year(exitdate) as exityear,month(exitdate) as exitmonth
from employee
where deleted=0 and year(hiringdate)<=? and employeename<>""
and (exitdate is null or exitdate="0000-00-00" or year(exitdate)>=?)
order by employeename';
$query_prm = array($year,$year);
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($y=0; $y < $num_results_main; $y++)
{
  $employeeid = $main_result[$y]['employeeid'];
  $month = 0; if ($main_result[$y]['year'] == $year) { $month = $main_result[$y]['month']; }
  $exitmonth = 13; if ($main_result[$y]['exityear'] == $year) { $exitmonth = $main_result[$y]['exitmonth']; }
  $jobid = $main_result[$y]['jobid'];
  $ok = 1;
  if (isset($contract_salaried_exemptA[$employee_contractidA[$employeeid]]) && $contract_salaried_exemptA[$employee_contractidA[$employeeid]] == 1) { $ok = 0; }
  if ($ok)
  {
    #$link = '##hr.php?hrmenu=modemployee&id='.$employeeid.'&step=3';
    $link = 'reportwindow.php?report=payroll_show_all&all_employeeid='.$employeeid;
    echo d_tr();
    echo d_td_old($employeeA[$employeeid],0,0,0,$link);
    if (isset($jobA[$jobid])) { echo d_td($jobA[$jobid]); }
    else { echo d_td(); }
    if (isset($contractA[$employee_contractidA[$employeeid]])) { echo d_td($contractA[$employee_contractidA[$employeeid]]); }
    else { echo d_td(); }

    for ($i = 1; $i <= 12; $i++)
    {
      $link = '';
      if ($i >= $month && $i <= $exitmonth)
      {
        if (isset($pA[$employeeid][$i]) && $pA[$employeeid][$i] && $p_statusA[$employeeid][$i] == 1)
        {
          $link = '<a href="printwindow.php?report=showpayslip&payslipid='.$pA[$employeeid][$i].'" target=_blank>&radic;</a>';
          if($_SESSION['ds_ishrsuperuser'] && 1==0) # && $_SESSION['ds_systemaccess']
          {
            $link .= ' <a href="hr.php?hrmenu=payroll_modify&payslipid='.$pA[$employeeid][$i].'">(m)</a>';
          }
        }
        elseif (isset($pA[$employeeid][$i]) && $pA[$employeeid][$i])
        {
          $link = '<a href="hr.php?hrmenu=payroll_modify&payslipid='.$pA[$employeeid][$i].'">X</a>';
        }
        else
        {
          $link = '<a href="hr.php?hrmenu=payroll_modify&year='.$year.'&month='.$i.'&employeeid='.$employeeid.'">___</a>';
        }
      }
      echo d_td_unfiltered($link, 'center');
    }
  }
}
if ($_SESSION['ds_ishrsuperuser'])
{
  echo d_tr();
  echo d_td('Verrouiller le mois','',3);
  for ($i = 1; $i <= 12; $i++)
  {
    echo d_td_unfiltered('<a href="hr.php?hrmenu=payroll_all_confirm&year='.$year.'&month='.$i.'">V</a>','center');
  }
}
echo '</table>';

echo '<br><p>Les employés sans date d\'embauche ne figurent pas dans cette liste.</p>';

if ($_SESSION['ds_ishrsuperuser'])
{
  echo '<br><br><h2>Déverrouiller</h2>
  <form method=post method="post" action="hr.php"><table>
  <tr><td>Employé(e) :'; $dp_itemname = 'employee'; $dp_noblank = 1; require('inc/selectitem.php');
  $month = substr($_SESSION['ds_curdate'],5,2);
  $year = substr($_SESSION['ds_curdate'],0,4);
  ?><tr><td>Mois:<td><select name="month"><?php
  for ($i=1; $i <= 12; $i++)
  {
    if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="year"><?php
  for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
  {
    if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  echo '</select><tr><td colspan=2 align=center><input type=hidden name="hrmenu" value="'. $hrmenu . '">
  <input type=hidden name="unconfirm" value=1>
  <input type="submit" value="Valider">
  </form>';
}
?>