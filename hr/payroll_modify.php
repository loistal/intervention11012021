<?php

# 13/3 weeks per month, default 169hours means 39h per week, 6.5 hours per day (Saturday counts)!

if ($_SESSION['ds_ishrsuperuser'] != 1) { exit; }

if ($_SESSION['ds_socialsecuritysectorid'] < 1)
{ echo '<p class=alert>Veuiller definir votre <a href="system.php?systemmenu=companyinfo">Secteur CPS</a>.</p>'; exit; }

if ($_SESSION['ds_customname'] == 'TEM') { $showcalc = 1; }
else { $showcalc = 0; }

require('preload/employee.php');
require('preload/absence_reason.php');

$PA['post_hourspermonth'] = 'double';
$PA['payslipid'] = 'uint'; # TODO move all _POST variables here
$PA['month'] = 'uint';
$PA['year'] = 'uint';
$PA['employeeid'] = 'uint';
$PA['vacationdays_added'] = 'double';
$PA['vacationdays_used'] = 'double';
$PA['add_timemod'] = 'uint';
$PA['payslipcomment'] = 'payslipcomment';
$PA['override_30'] = 'double';
$PA['override_31'] = 'double';
$PA['override_32'] = 'double';
$PA['override_33'] = 'double';
$PA['override_34'] = 'double';
$PA['override_50'] = 'double';
$PA['override_60'] = 'double';
$PA['override_61'] = 'double';
$PA['reimburse_days'] = 'udouble';
$PA['reimburse_days_comment'] = 'udouble';
$PA['netadd'] = 'uint';
$PA['netdeduct'] = 'uint';
$PA['netadd_comment'] = '';
$PA['netdeduct_comment'] = '';
$PA['netadd1'] = 'uint';
$PA['netdeduct1'] = 'uint';
$PA['netdeduct2'] = 'uint';
$PA['netdeduct3'] = 'uint';
$PA['netdeduct4'] = 'uint';
$PA['netadd_comment1'] = '';
$PA['netdeduct_comment1'] = '';
$PA['netdeduct_comment2'] = '';
$PA['netdeduct_comment3'] = '';
$PA['netdeduct_comment4'] = '';
$PA['confirm1'] = 'uint';
$PA['confirm2'] = 'uint';
$PA['unconfirm'] = 'uint';
$PA['apply_hours'] = 'uint';
$PA['bankaccountid'] = 'uint';
$PA['paymenttypeid'] = 'uint';
$PA['payroll_payment_date'] = 'date';
$PA['net_modif_account10100id'] = 'uint';
$PA['net_modif_account10101id'] = 'uint';
$PA['net_modif_account10200id'] = 'uint';
$PA['net_modif_account10201id'] = 'uint';
$PA['net_modif_account10202id'] = 'uint';
$PA['net_modif_account10203id'] = 'uint';
$PA['net_modif_account10204id'] = 'uint';
$PA['hours_text'] = '';
require('inc/readpost.php');

$override_30 = str_replace(',','.',$override_30);
$override_31 = str_replace(',','.',$override_31);
$override_32 = str_replace(',','.',$override_32);
$override_33 = str_replace(',','.',$override_33);
$override_34 = str_replace(',','.',$override_34);

$advance = 0;

if ($payslipid < 1)
{
  if ($employeeid < 1 || $year < $_SESSION['ds_startyear'] || $month < 1) { exit; }

  $payslipdate = $year.'-';
  if ($month < 10) { $payslipdate .= '0'; }
  $payslipdate .= $month.'-01';

  $query = 'select payslipid from payslip where employeeid=? and payslipdate=?';
  $query_prm = array($employeeid,$payslipdate);
  require('inc/doquery.php');
  if ($num_results == 0)
  {
    $query = 'select hourspermonth,default_paymenttypeid,default_bankaccountid from employee where employeeid=?';
    $query_prm = array($employeeid);
    require('inc/doquery.php');
    $paymenttypeid = $query_result[0]['default_paymenttypeid'];
    $bankaccountid = $query_result[0]['default_bankaccountid'];
    $temp_hourspermonth = $query_result[0]['hourspermonth'];
    # adding 2.5 days by default, option???
    $query = 'insert into payslip (hourspermonth,employeeid,payslipdate,paymenttypeid,bankaccountid,vacationdays_added)
    values (?,?,?,?,?,2.5)';
    $query_prm = array($temp_hourspermonth,$employeeid,$payslipdate,$paymenttypeid,$bankaccountid);
    require('inc/doquery.php');
    $query = 'select payslipid from payslip where employeeid=? and payslipdate=?';
    $query_prm = array($employeeid,$payslipdate);
    require('inc/doquery.php');
    $payslipid = $query_result[0]['payslipid'];
  }
  else { $payslipid = $query_result[0]['payslipid']; }
}
if ($payslipid < 1) { exit; }
if ($employeeid < 1) # duplicate but just leave it
{
  $query = 'select employeeid from payslip where payslipid=?';
  $query_prm = array($payslipid);
  require('inc/doquery.php');
  $employeeid = $query_result[0]['employeeid'];
}
if (isset($_POST['post_hourspermonth']))
{
  $query = 'update payslip set hourspermonth=? where payslipid=?';
  $query_prm = array((double) $post_hourspermonth,$payslipid);
  require('inc/doquery.php');
}

$half_step = 0.5;
if ($_SESSION['ds_customname'] == 'Espace Paysages'
|| $_SESSION['ds_customname'] == 'Espace 7'
|| $_SESSION['ds_customname'] == 'Pacific Batiment'
|| $_SESSION['ds_customname'] == 'Jurion Protection') # TODO option
{ $half_step = 0.01; }

$query = 'select * from employee where employeeid=?';
$query_prm = array($employeeid);
require('inc/doquery.php');
$e_base_salary = $query_result[0]['basesalary']+0;
$e_hourspermonth = $query_result[0]['hourspermonth']+0;
$e_payslipinfo = $query_result[0]['payslipinfo'];
$jobid = $query_result[0]['jobid'];
$hiringdate = $query_result[0]['hiringdate'];
$exitdate = $query_result[0]['exitdate']; if ($exitdate == '0000-00-00') { $exitdate = ''; }
$hourly_pay = $query_result[0]['hourly_pay'];
if ($e_hourspermonth <= 0) { echo '<p class=alert>Veuiller definir l\'horaire de référence pour cet employé(e).</p>'; exit; }

$jobname = '';
$query = 'select jobname from job where jobid=?';
$query_prm = array($jobid);
require('inc/doquery.php');
if ($num_results) { $jobname = $query_result[0]['jobname']; }

$base_salary = $e_base_salary;
$hourspermonth = $e_hourspermonth;
$query = 'update payslip set base_salary=? where payslipid=?';
$query_prm = array($base_salary,$payslipid);
require('inc/doquery.php');

if (isset($_POST['payslipcomment']))
{
  $query = 'update payslip set payslipcomment=? where payslipid=?';
  $query_prm = array($payslipcomment,$payslipid);
  require('inc/doquery.php');
}

$query = 'select hours_text,bankaccountid,paymenttypeid,employeeid,payslipdate,year(payslipdate) as year,month(payslipdate) as month
,base_salary,hourspermonth,payslipcomment,vacationdays_added,vacationdays_used,status,payroll_payment_date
from payslip where payslipid=?';
$query_prm = array($payslipid);
require('inc/doquery.php');
if (!isset($hours_text)) { $hours_text = $query_result[0]['hours_text']; }
$employeeid = $query_result[0]['employeeid'];
$payslipdate = $query_result[0]['payslipdate'];
$year = $query_result[0]['year'];
$month = $query_result[0]['month'];
$base_salary = $query_result[0]['base_salary']+0;
$e_hourspermonth = $hourspermonth = $query_result[0]['hourspermonth']+0;
if (isset($_POST['post_hourspermonth']))
{
  $e_hourspermonth = $post_hourspermonth;
}
$payslipcomment = $query_result[0]['payslipcomment'];
$status = $query_result[0]['status']+0;
if (!isset($_POST['vacationdays_added']))
{
  $vacationdays_added = $query_result[0]['vacationdays_added'];
  $vacationdays_used = $query_result[0]['vacationdays_used'];
  $bankaccountid = $query_result[0]['bankaccountid']+0;
  $paymenttypeid = $query_result[0]['paymenttypeid']+0;
  $payroll_payment_date = $query_result[0]['payroll_payment_date'];
}
if ($status == 0 && $confirm1 && $confirm2)
{
  $status = 1;
  $query = 'update payslip set status=1 where payslipid=?';
  $query_prm = array($payslipid);
  require('inc/doquery.php');
}
if ($status == 1 && $unconfirm && $_SESSION['ds_ishrsuperuser'])# && $_SESSION['ds_systemaccess']
{
  $status = 0;
  $query = 'update payslip set status=0 where payslipid=?';
  $query_prm = array($payslipid);
  require('inc/doquery.php');
}

##########################
# info from tableau pointage
# see reportwindow/employee_month.php

function showtimeworked ($timeworked) # copy from somewhere
{
  $showtimeworked = $timeworked%60; if ($showtimeworked < 10) { $showtimeworked = '0'.$showtimeworked; }
  $showtimeworked = floor($timeworked/60).':'.$showtimeworked;
  if ($showtimeworked == '0:00') { $showtimeworked = ''; }
  return $showtimeworked;
}

require('preload/employee.php');

$query = 'select employeeid,minutes_worked,minutes_to_pay,employee_monthid,nonworked1,nonworked2,nonworked3,nonworked4,nonworked5,meal_allowance
from employee_month
where year=? and month=? and employeeid=?';
$query_prm = array($year,$month,$employeeid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $minutes_worked[$employeeid] = $query_result[$i]['minutes_worked'];
  $minutes_to_pay[$employeeid] = $query_result[$i]['minutes_to_pay'];
  $employee_monthid[$employeeid] = $query_result[$i]['employee_monthid'];
  $nonworked1[$employeeid] = $query_result[$i]['nonworked1'];
  $nonworked2[$employeeid] = $query_result[$i]['nonworked2'];
  $nonworked3[$employeeid] = $query_result[$i]['nonworked3'];
  $nonworked4[$employeeid] = $query_result[$i]['nonworked4'];
  $nonworked5[$employeeid] = $query_result[$i]['nonworked5'];
  $meal_allowance[$employeeid] = $query_result[$i]['meal_allowance'];
  if ($meal_allowance[$employeeid] == 0) { $meal_allowance[$employeeid] = ''; }
}

$query = 'select * from employee_month_seq where year(sequencedate)=? and month(sequencedate)=? and employeeid=?';
$query_prm = array($year,$month,$employeeid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if ($i == 0 || $employeeid != $query_result[($i-1)]['employeeid']) { $sequencecounter = 0; }
  $text = showtimeworked($query_result[$i]['begin']) . ' à ' . showtimeworked($query_result[$i]['end']);
  $sequenceA[$employeeid][$sequencecounter] = '['.datefix($query_result[$i]['sequencedate'],'short').' '.$text.']';
  $sequencecounter++;
}

if (isset($employee_monthid[$employeeid]))
{
  $query = 'select type,rate,minutes from employee_month_minutes where employee_monthid=?';
  $query_prm = array($employee_monthid[$employeeid]);
  require('inc/doquery.php');
  unset ($em);
  for ($y=0; $y < $num_results; $y++)
  {
    $em[$query_result[$y]['type']][$query_result[$y]['rate']] = $query_result[$y]['minutes'];
  }
}

if ($apply_hours)
{
  $tmc = ''; $override_30 = 0; $rate = myround($base_salary/$hourspermonth,4); $time_mod30 = 0;
  if (isset($em[0][115]) && $em[0][115] != 0) { $tmc .= 'Maj 15%: '.showtimeworked($em[0][115]); $time_mod30 += 0.15 * $em[0][115] * $rate/60; }
  if (isset($em[0][125]) && $em[0][125] != 0) { $tmc .= ' Maj 25%: '.showtimeworked($em[0][125]); $time_mod30 += 0.25 * $em[0][125] * $rate/60; }
  if (isset($em[0][150]) && $em[0][150] != 0) { $tmc .= ' Maj 50%: '.showtimeworked($em[0][150]); $time_mod30 += 0.50 * $em[0][150] * $rate/60; }
  if (isset($em[0][200]) && $em[0][200] != 0) { $tmc .= ' Maj 100%: '.showtimeworked($em[0][200]); $time_mod30 += $em[0][200] * $rate/60; }
  if (isset($em[1][125]) && $em[1][125] != 0) { $tmc .= ' Sup 25%: '.showtimeworked($em[1][125]); $time_mod30 += 0.25 * $em[1][125] * $rate/60; $override_30 += $em[1][125]; }
  if (isset($em[1][150]) && $em[1][150] != 0) { $tmc .= ' Sup 50%: '.showtimeworked($em[1][150]); $time_mod30 += 0.50 * $em[1][150] * $rate/60; $override_30 += $em[1][150]; }
  if (isset($em[1][175]) && $em[1][175] != 0) { $tmc .= ' Sup 75%: '.showtimeworked($em[1][175]); $time_mod30 += 0.75 * $em[1][175] * $rate/60; $override_30 += $em[1][175]; }
  if (isset($em[1][200]) && $em[1][200] != 0) { $tmc .= ' Sup 100%: '.showtimeworked($em[1][200]); $time_mod30 += $em[1][200] * $rate/60; $override_30 += $em[1][200]; }
  if (isset($em[1][300]) && $em[1][300] != 0) { $tmc .= ' Sup 200%: '.showtimeworked($em[1][300]); $time_mod30 += 2 * $em[1][300] * $rate/60; $override_30 += $em[1][300]; }
  $_POST['time_mod_comment30'] = $tmc;
  $override_30 /= 60;
  $_POST['time_mod30'] = $time_mod30;
}
##########################

###
# find vacationdays left from last month
$lastmonth = $month - 1;
$lastyear = $year;
if ($lastmonth == 0) { $lastmonth = 12; $lastyear--; }
$lastpayslipdate = d_builddate(1,$lastmonth,$lastyear);
$query = 'select vacationdays from payslip where employeeid=? and payslipdate=?';
$query_prm = array($employeeid,$lastpayslipdate);
require('inc/doquery.php');
if ($num_results)
{
  $vacationdays_last = $query_result[0]['vacationdays'];
}
else
{
  $vacationdays_last = 0; #
}
$vacationdays_added = myround($vacationdays_added,2); # nearest half day
$vacationdays_used = myround($vacationdays_used,2); # nearest half day
$vacationdays = $vacationdays_last + $vacationdays_added - $vacationdays_used;

if (isset($_POST['vacationdays_added']))
{
  $query = 'update payslip set vacationdays=?,vacationdays_added=?,vacationdays_used=? where payslipid=?';
  $query_prm = array($vacationdays,$vacationdays_added,$vacationdays_used,$payslipid);
  require('inc/doquery.php');
}

$merge_absence = 0;
if ($_SESSION['ds_payroll_startday'])
{
  $period_text = datefix(d_builddate($_SESSION['ds_payroll_startday'],$lastmonth,$lastyear))
  .' à '.datefix(d_builddate(($_SESSION['ds_payroll_startday']-1),$month,$year));
  $enddate = d_builddate(($_SESSION['ds_payroll_startday']-1),$month,$year);
  if ($exitdate != '' && $exitdate <= $enddate) { $enddate = $exitdate; $merge_absence = 1; }
}
else
{
  $period_text = datefix($payslipdate) . ' à ' . datefix(d_builddate(31,$month,$year));
  $enddate = d_builddate(31,$month,$year);
  if ($exitdate != '' && $exitdate <= $enddate) { $enddate = $exitdate; $merge_absence = 1; }
}

###


$gross_salary = $base_salary;

###### time mods
$hoursworked = $hourspermonth;
$time_modA = array(10,20,22,25,30,31,32,33,34,35,36,37,40,50,60,61,70,80);
$seniority_bonus = 0; $bonus = 0; $seniority_bonus_percent = 0;
foreach ($time_modA as $rank)
{
  $name = ''; $comment = ''; $comment_e = '';
  $value = 0; $value_e = 0; $negative = 0; $updateme = 1;
  switch ($rank)
  {
    case 10:
      $time_mod_nameA[$rank] = $name = 'Prime ancienneté';
      $time_mod_denomA[$rank] = 'XPF';
      $rate = 1;
      if ($_SESSION['seniority_bonus_calc'] == 0 || $merge_absence == 1) # TODO set $merge_absence
      {
        if (isset($_POST['time_mod'.$rank]))
        {
          $time_mod_valueA[$rank] = $value = (int) $_POST['time_mod'.$rank];
          $time_mod_commentA[$rank] = $comment_e = $_POST['time_mod_comment'.$rank];
          $seniority_bonus = $value;
          
          ### need to mod value of absences NOTE assuming default rate!
          $seniority_bonus_percent = 0;
          $duration_years = substr($payslipdate,0,4) - substr($hiringdate,0,4);
          $duration_months = substr($payslipdate,5,2) - substr($hiringdate,5,2);
          if ($duration_months < 0)
          {
            $duration_years--;
            $duration_months += 12;
          }
          if ($duration_years >= 3)
          {
            $seniority_bonus_percent = $duration_years;
          }
          if ($seniority_bonus_percent > 25) { $seniority_bonus_percent = 25; }
          ###
        }
        else { $updateme = 0; $time_mod_valueA[$rank] = 0; }
      }
      else
      {
        $value = 0;
        $duration_years = substr($payslipdate,0,4) - substr($hiringdate,0,4);
        $duration_months = substr($payslipdate,5,2) - substr($hiringdate,5,2);
        if ($duration_months < 0)
        {
          $duration_years--;
          $duration_months += 12;
        }
        if ($duration_years >= 3)
        {
          $value = $duration_years;
        }
        if ($_SESSION['seniority_bonus_calc'] == 2 && $duration_years >= 10)
        {
          $value += ($duration_years-10)*0.5;
        }
        if ($value > 25) { $value = 25; }
        $seniority_bonus_percent = $value; # need to mod value of absences

        #echo '<br>$duration_years=',$duration_years;
        #echo '<br>$duration_months=',$duration_months;
        #echo '<br>$value=',$value;
        #echo '<br>',($duration_years-10)*0.5;
        
        $value = ($value * $base_salary)/100;
        $value = myround($value);
        
        #echo '<br>$value=',$value;
        
        $time_mod_valueA[$rank] = $value;
        if (isset($_POST['time_mod_comment'.$rank])) { $time_mod_commentA[$rank] = $comment_e = $_POST['time_mod_comment'.$rank]; }
        else
        {
          $time_mod_commentA[$rank] = $comment_e = '';
          $query = 'select payslip_line_comment_employer from payslip_line_net where `rank`=? and payslipid=?';
          $query_prm = array($rank,$payslipid);
          require('inc/doquery.php');
          if ($num_results) { $time_mod_commentA[$rank] = $comment_e = $query_result[0]['payslip_line_comment_employer']; }
        }
        $seniority_bonus = $value;
      }
      if ($hourly_pay) { $seniority_bonus_percent = 0; }
    break;
    case 20:
      $time_mod_nameA[$rank] = $name = 'Prime exceptionnelle';
      $time_mod_denomA[$rank] = 'XPF';
      $rate = 1;
      if (isset($_POST['time_mod'.$rank]))
      {
        $time_mod_valueA[$rank] = $value = (int) $_POST['time_mod'.$rank];
        $time_mod_commentA[$rank] = $comment_e = $_POST['time_mod_comment'.$rank];
        $bonus = $value;
      }
      else { $updateme = 0; $time_mod_valueA[$rank] = 0; }
    break;
    case 22:
      $time_mod_nameA[$rank] = $name = 'Prime repas';
      $time_mod_denomA[$rank] = 'XPF';
      $rate = 1;
      if (isset($_POST['time_mod'.$rank]))
      {
        $time_mod_valueA[$rank] = $value = (int) $_POST['time_mod'.$rank];
        $time_mod_commentA[$rank] = $comment_e = $_POST['time_mod_comment'.$rank];
        $bonus = $value;
      }
      else { $updateme = 0; $time_mod_valueA[$rank] = 0; }
    break;
    case 25:
      $time_mod_nameA[$rank] = $name = 'Heures complémentaires';
      $time_mod_denomA[$rank] = 'heures';
      $rate = myround(($base_salary+$seniority_bonus)/$hourspermonth,4);
      if (isset($_POST['time_mod'.$rank]))
      {
        $time_mod_valueA[$rank] = $value = (double) $_POST['time_mod'.$rank];
        if ($value != 0) { $comment = $value . ' heures x ' . myround($rate,2); $hoursworked += $value; }
        $value = myround($value * $rate);
        $time_mod_commentA[$rank] = $comment_e = $_POST['time_mod_comment'.$rank];
      }
      else
      {
        $updateme = 0; $time_mod_valueA[$rank] = 0;
        $query = 'select payslip_line_netid,value from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array($rank,$payslipid);
        require('inc/doquery.php');
        if ($num_results) { $hoursworked += myround($query_result[0]['value'] / $rate); }
      }
    break;
    case 30:
      if (!isset($_POST['override_30']))
      {
        $query = 'select payslip_line_netid,payslip_line_comment from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array($rank,$payslipid);
        require('inc/doquery.php');
        if ($num_results) { $override_30 = $query_result[0]['payslip_line_comment']; }
      }
      $time_mod_nameA[$rank] = $name = 'Heures supplémentaires/majorées';
      $time_mod_denomA[$rank] = 'XPF';
      $rate = 1;
      $hoursworked = d_add($hoursworked, $override_30);
      if (isset($_POST['time_mod'.$rank]))
      {
        $time_mod_valueA[$rank] = $value = (int) $_POST['time_mod'.$rank];
        $time_mod_commentA[$rank] = $comment_e = $_POST['time_mod_comment'.$rank];
        $comment = $override_30;
      }
      else { $updateme = 0; $time_mod_valueA[$rank] = 0; }
    break;
    case 31:
      if (!isset($_POST['override_31']))
      {
        $query = 'select payslip_line_netid,payslip_line_comment from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array($rank,$payslipid);
        require('inc/doquery.php');
        if ($num_results) { $override_31 = $query_result[0]['payslip_line_comment']; }
      }
      $time_mod_nameA[$rank] = $name = 'Heures supplémentaires/majorées';
      $time_mod_denomA[$rank] = 'XPF';
      $rate = 1;
      $hoursworked = d_add($hoursworked, $override_31);
      if (isset($_POST['time_mod'.$rank]))
      {
        $time_mod_valueA[$rank] = $value = (int) $_POST['time_mod'.$rank];
        $time_mod_commentA[$rank] = $comment_e = $_POST['time_mod_comment'.$rank];
        $comment = $override_31;
      }
      else { $updateme = 0; $time_mod_valueA[$rank] = 0; }
    break;
    case 32:
      if (!isset($_POST['override_32']))
      {
        $query = 'select payslip_line_netid,payslip_line_comment from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array($rank,$payslipid);
        require('inc/doquery.php');
        if ($num_results) { $override_32 = $query_result[0]['payslip_line_comment']; }
      }
      $time_mod_nameA[$rank] = $name = 'Heures supplémentaires/majorées';
      $time_mod_denomA[$rank] = 'XPF';
      $rate = 1;
      $hoursworked = d_add($hoursworked, $override_32);
      if (isset($_POST['time_mod'.$rank]))
      {
        $time_mod_valueA[$rank] = $value = (int) $_POST['time_mod'.$rank];
        $time_mod_commentA[$rank] = $comment_e = $_POST['time_mod_comment'.$rank];
        $comment = $override_32;
      }
      else { $updateme = 0; $time_mod_valueA[$rank] = 0; }
    break;
    case 33:
      if (!isset($_POST['override_33']))
      {
        $query = 'select payslip_line_netid,payslip_line_comment from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array($rank,$payslipid);
        require('inc/doquery.php');
        if ($num_results) { $override_33 = $query_result[0]['payslip_line_comment']; }
      }
      $time_mod_nameA[$rank] = $name = 'Heures supplémentaires/majorées';
      $time_mod_denomA[$rank] = 'XPF';
      $rate = 1;
      $hoursworked = d_add($hoursworked, $override_33);
      if (isset($_POST['time_mod'.$rank]))
      {
        $time_mod_valueA[$rank] = $value = (int) $_POST['time_mod'.$rank];
        $time_mod_commentA[$rank] = $comment_e = $_POST['time_mod_comment'.$rank];
        $comment = $override_33;
      }
      else { $updateme = 0; $time_mod_valueA[$rank] = 0; }
    break;
    case 34:
      if (!isset($_POST['override_34']))
      {
        $query = 'select payslip_line_netid,payslip_line_comment from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array($rank,$payslipid);
        require('inc/doquery.php');
        if ($num_results) { $override_34 = $query_result[0]['payslip_line_comment']; }
      }
      $time_mod_nameA[$rank] = $name = 'Heures supplémentaires/majorées';
      $time_mod_denomA[$rank] = 'XPF';
      $rate = 1;
      $hoursworked = d_add($hoursworked, $override_34);
      if (isset($_POST['time_mod'.$rank]))
      {
        $time_mod_valueA[$rank] = $value = (int) $_POST['time_mod'.$rank];
        $time_mod_commentA[$rank] = $comment_e = $_POST['time_mod_comment'.$rank];
        $comment = $override_34;
      }
      else { $updateme = 0; $time_mod_valueA[$rank] = 0; }
    break;
    case 35:
      $negative = 1;
      $time_mod_nameA[$rank] = $name = 'Absences';
      $time_mod_denomA[$rank] = 'heures';
      $rate = myround($base_salary/$hourspermonth,4);
      if ($seniority_bonus_percent) { $rate += $rate * $seniority_bonus_percent/100; }
      $rate = round($rate,4);
      if (isset($_POST['time_mod'.$rank]))
      {
        $time_mod_valueA[$rank] = $value = (double) $_POST['time_mod'.$rank];
        if ($value != 0) { $comment = $value . ' heures x ' . myround($rate,2); $hoursworked -= $value; }
        $value = myround($value * $rate);
        $time_mod_commentA[$rank] = $comment_e = $_POST['time_mod_comment'.$rank];
      }
      else
      {
        $updateme = 0; $time_mod_valueA[$rank] = 0;
        $query = 'select payslip_line_netid,value from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array($rank,$payslipid);
        require('inc/doquery.php');
        if ($num_results) { $hoursworked -= myround($query_result[0]['value'] / $rate); }
      }
    break;
    case 36:
      $negative = 1;
      $time_mod_nameA[$rank] = $name = 'Accident de travail';
      $time_mod_denomA[$rank] = 'heures';
      $rate = myround($base_salary/$hourspermonth,4);
      if ($seniority_bonus_percent) { $rate += $rate * $seniority_bonus_percent/100; }
      if (isset($_POST['time_mod'.$rank]))
      {
        $time_mod_valueA[$rank] = $value = (double) $_POST['time_mod'.$rank];
        if ($value != 0) { $comment = $value . ' heures x ' . myround($rate,2); $hoursworked -= $value; }
        $value = myround($value * $rate);
        $time_mod_commentA[$rank] = $comment_e = $_POST['time_mod_comment'.$rank];
      }
      else
      {
        $updateme = 0; $time_mod_valueA[$rank] = 0;
        $query = 'select payslip_line_netid,value from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array($rank,$payslipid);
        require('inc/doquery.php');
        if ($num_results) { $hoursworked -= myround($query_result[0]['value'] / $rate); }
      }
    break;
    case 37:
      $negative = 1;
      $time_mod_nameA[$rank] = $name = 'Mise à pied';
      $time_mod_denomA[$rank] = 'heures';
      $rate = myround($base_salary/$hourspermonth,4);
      if ($seniority_bonus_percent) { $rate += $rate * $seniority_bonus_percent/100; }
      if (isset($_POST['time_mod'.$rank]))
      {
        $time_mod_valueA[$rank] = $value = (double) $_POST['time_mod'.$rank];
        if ($value != 0) { $comment = $value . ' heures x ' . myround($rate,2); $hoursworked -= $value; }
        $value = myround($value * $rate);
        $time_mod_commentA[$rank] = $comment_e = $_POST['time_mod_comment'.$rank];
      }
      else
      {
        $updateme = 0; $time_mod_valueA[$rank] = 0;
        $query = 'select payslip_line_netid,value from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array($rank,$payslipid);
        require('inc/doquery.php');
        if ($num_results) { $hoursworked -= myround($query_result[0]['value'] / $rate); }
      }
    break;
    case 40:
      $negative = 1;
      $time_mod_nameA[$rank] = $name = 'Congé sans solde';
      $time_mod_denomA[$rank] = 'heures';
      $rate = myround($base_salary/$hourspermonth,4);
      if ($seniority_bonus_percent) { $rate += $rate * $seniority_bonus_percent/100; } # added 2019 02 06, needs verification
      if (isset($_POST['time_mod'.$rank]))
      {
        $time_mod_valueA[$rank] = $value = (double) $_POST['time_mod'.$rank];
        if ($value != 0) { $comment = $value . ' heures x ' . myround($rate,2); $hoursworked -= $value; }
        $value = myround($value * $rate);
        $time_mod_commentA[$rank] = $comment_e = $_POST['time_mod_comment'.$rank];
      }
      else
      {
        $updateme = 0; $time_mod_valueA[$rank] = 0;
        $query = 'select payslip_line_netid,value from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array($rank,$payslipid);
        require('inc/doquery.php');
        if ($num_results) { $hoursworked -= myround($query_result[0]['value'] / $rate); }
      }
    break;
    case 50:
      $negative = 1;
      $time_mod_nameA[$rank] = $name = 'Congé payé';
      $time_mod_denomA[$rank] = 'heures';

      $rate = myround(($base_salary+$seniority_bonus)/$hourspermonth,4);
      
      if ($showcalc)
      {
        echo '<p><b>Explications Taux calculés:</b><br>
        Maintien =
        (salaire de base + ancienneté) / heures_par_mois=<br>
        ('.$base_salary.' + '.$seniority_bonus.') / '.$hourspermonth.' =<br>
        '.$rate.'<br><br>';
      }
      
      ### methode 10ieme : ((brut - prime exceptionel over past 12 months) / 10) / hourspermonth
      $endmonth = $month - 1; $endyear = $year;
      $startmonth = $month; $startyear = $year - 1;
      if ($endmonth < 1) { $endmonth = 12; $endyear = $startyear; }
      $startdate = d_builddate(1,$startmonth,$startyear);
      $stopdate = d_builddate(1,$endmonth,$endyear);
      $query = 'select sum(calc_salary) as rate from payslip where employeeid=? and payslipdate>=? and payslipdate<=?';
      $query_prm = array($employeeid,$startdate,$stopdate);
      require('inc/doquery.php');
      if ($num_results)
      {
        $rate_alt = myround(($query_result[0]['rate'] / 10) / $hourspermonth,4);
        if ($showcalc)
        {
          echo '10ème =
          (total salaire / 10) / $heures_par_mois=<br>
          ('.$query_result[0]['rate'].' / 10) / '.$hourspermonth.' =<br>
          '.$rate_alt.'<br><br>';
        }
      }
      else { $rate_alt = 0; }
      ##########################
      if ($_SESSION['ds_customname'] == 'Espace Paysages'
      || $_SESSION['ds_customname'] == 'Espace 7'
      || $_SESSION['ds_customname'] == 'Pacific Batiment'
      || $_SESSION['ds_customname'] == 'Jurion Protection') # whatever
      {
        #Nous aimerions que vous paramétré le calcul du maintien du salaire de cette manière pour toute les sociétés :
        #(SALAIRE DE BASE + ANCIENNETE)/30*26
        # 2019 02 04 changed to
        #(SALAIRE DE BASE + ANCIENNETE)/26 * par le nombre de jours de congés pris.
        #$custom_rate = myround(($base_salary+$seniority_bonus)/30*26,2);

        $query = 'select value from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array(40,$payslipid);
        require('inc/doquery.php');
        $kladd40 = $query_result[0]['value'];
      
        $query = 'select value from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array(40,$payslipid);
        require('inc/doquery.php');
        $kladd50 = $query_result[0]['value'];
        
        $custom_rate = myround(($kladd40+$kladd50) / myround($e_hourspermonth/30,2),2);
        if ($custom_rate != 0)
        {
          $custom_rate = myround(($base_salary+$seniority_bonus)/$custom_rate*26,2);
        }
        
        $rate_string = '<tr><td>Taux calculés : Maintien / 10ème<td align=right>'.$custom_rate.' / '.$rate_alt;
      }
      ##########################
      else { $rate_string = '<tr><td>Taux calculés : Maintien / 10ème<td align=right>'.$rate.' / '.$rate_alt; }

      #if ($rate_alt > $rate) { $rate = $rate_alt; } # 2020 10 05 TODO verify
      #if ($rate_alt > $rate) { $override_50 = $rate_alt; } modifies hours???

      if ($override_50 != 0) { $rate_50 = $rate = $override_50; }
      else { $rate_50 = $rate; }
      if (isset($_POST['time_mod'.$rank]))
      {#echo '***',$_POST['time_mod'.$rank],'***';
        $time_mod_valueA[$rank] = $value = (double) $_POST['time_mod'.$rank];
        if ($value != 0) { $comment = '('.$value . ' heures x ' . myround($rate,2).')'; } #  $hoursworked -= $value;
        $value = myround($value * $rate);#echo $value;
        $time_mod_commentA[$rank] = $comment_e = $_POST['time_mod_comment'.$rank];
      }
      else
      {
        $updateme = 0; $time_mod_valueA[$rank] = 0;/*
        $query = 'select payslip_line_netid,value,override from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array($rank,$payslipid);
        require('inc/doquery.php');
        if ($num_results)
        {
          if ($query_result[0]['override'] > 0) { $hoursworked -= myround($query_result[0]['value'] / $query_result[0]['override']); }
          else { $hoursworked -= myround($query_result[0]['value'] / $rate); }
        }*/
      }
    break;
    case 60:
      $time_mod_nameA[$rank] = $name = 'Indemnités congé payé';
      $time_mod_denomA[$rank] = 'heures';
      $rate = $rate_50;
      if ($override_60 != 0) { $rate = $override_60; }
      if (isset($_POST['time_mod'.$rank]))
      {
        $time_mod_valueA[$rank] = $value = (double) $_POST['time_mod'.$rank];
        if ($value != 0) { $comment = '(' . $value . ' heures x ' . myround($rate,2) . ')'; }
        $value = myround($value * $rate);
        $time_mod_commentA[$rank] = $comment_e = $_POST['time_mod_comment'.$rank];
      }
      else { $updateme = 0; $time_mod_valueA[$rank] = 0; }
    break;
    case 61:
      $time_mod_nameA[$rank] = $name = 'Indemnités congé payé';
      $time_mod_denomA[$rank] = 'heures';
      $rate = $rate_50;
      if ($override_61 != 0) { $rate = $override_61; }
      if (isset($_POST['time_mod'.$rank]))
      {
        $time_mod_valueA[$rank] = $value = (double) $_POST['time_mod'.$rank];
        if ($value != 0) { $comment = '(' . $value . ' heures x ' . myround($rate,2) . ')'; }
        $value = myround($value * $rate);
        $time_mod_commentA[$rank] = $comment_e = $_POST['time_mod_comment'.$rank];
      }
      else { $updateme = 0; $time_mod_valueA[$rank] = 0; }
    break;
    case 70:
      $negative = 1;
      $time_mod_nameA[$rank] = $name = 'Arrêt maladie';
      $time_mod_denomA[$rank] = 'jours';
      $rate = myround(($base_salary+$seniority_bonus)/30,2); # hardcode 30 days per month
      if (isset($_POST['time_mod'.$rank]))
      {
        $time_mod_valueA[$rank] = $value = (int) $_POST['time_mod'.$rank];
        if ($value != 0) { $comment = $value . ' jours x ' . $rate; $hoursworked -= $value * ($hourspermonth/26); }
        $value = myround($value * $rate);
        $time_mod_commentA[$rank] = $comment_e = $_POST['time_mod_comment'.$rank];
      }
      else
      {
        $updateme = 0; $time_mod_valueA[$rank] = 0;
        $query = 'select payslip_line_netid,value from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array($rank,$payslipid);
        require('inc/doquery.php');
        if ($num_results)
        {
          $hoursworked -= round($query_result[0]['value']/$rate,1) * ($hourspermonth/26);
        }
      }
    break;
    case 80:
      $time_mod_nameA[$rank] = $name = 'Prise en charge patronale';
      $time_mod_denomA[$rank] = 'jours';
      $rate = myround(($base_salary+$seniority_bonus)/30,2); # hardcode 30 days per month
      if (isset($_POST['time_mod'.$rank]))
      {
        $time_mod_valueA[$rank] = $value = (int) $_POST['time_mod'.$rank];
        if ($value != 0) { $comment = '(' . $value . ' jours x ' . $rate . ')'; }
        $value = myround($value * $rate);
        $time_mod_commentA[$rank] = $comment_e = $_POST['time_mod_comment'.$rank];
      }
      else { $updateme = 0; $time_mod_valueA[$rank] = 0; }
    break;
  }
  $query = 'select payslip_line_netid from payslip_line_net where `rank`=? and payslipid=?';
  $query_prm = array($rank,$payslipid);
  require('inc/doquery.php');
  $override = 0;
  if ($rank == 50) { $override = $override_50; }
  if ($rank == 60) { $override = $override_60; }
  if ($rank == 61) { $override = $override_61; }
  if ($num_results == 0)
  {
    $query = 'insert into payslip_line_net (negative,value,override,value_employer,payslip_line_name,payslip_line_comment,payslip_line_comment_employer,`rank`,payslipid) values (?,?,?,?,?,?,?,?,?)';
  }
  else
  {
    $query = 'update payslip_line_net set negative=?,value=?,override=?,value_employer=?,payslip_line_name=?,payslip_line_comment=?,payslip_line_comment_employer=? where `rank`=? and payslipid=?';
  }
  $query_prm = array($negative,$value,$override,$value_e,$name,$comment,$comment_e,$rank,$payslipid);
  if ($updateme || $num_results == 0) { require('inc/doquery.php'); }
  $query = 'select value,override,payslip_line_comment_employer from payslip_line_net where `rank`=? and payslipid=?';
  $query_prm = array($rank,$payslipid);
  require('inc/doquery.php');
  $time_mod_commentA[$rank] = $query_result[0]['payslip_line_comment_employer'];
  if ($rank == 50) { $override_50 = myround($query_result[0]['override'],2); if ($override_50 != 0) { $rate = $override_50; } }
  if ($rank == 60) { $override_60 = myround($query_result[0]['override'],2); if ($override_60 != 0) { $rate = $override_60; } }
  if ($rank == 61) { $override_61 = myround($query_result[0]['override'],2); if ($override_61 != 0) { $rate = $override_61; } }
  if ($rate == 0) { $rate = 1; } # 2018 08 22
  if ($rank == 30 || $rank == 31 || $rank == 32 || $rank == 33 || $rank == 34) # round to nearest half
  {
    $time_mod_valueA[$rank] = myround(2 * $query_result[0]['value'] / $rate) / 2;
  }
  elseif ($rank == 25 || $rank == 35 || $rank == 36 || $rank == 37 || $rank == 40 || $rank == 50 || $rank == 60 || $rank == 61)
  {
    $time_mod_valueA[$rank] = myround(100 * $query_result[0]['value'] / $rate) / 100; # should allow... two decimals?
    #if ($rank == 50) { echo '100*',$query_result[0]['value'],'/',$rate,'= ',$time_mod_valueA[$rank]; } # HERE
  }
  else { $time_mod_valueA[$rank] = myround($query_result[0]['value'] / $rate); }
  if ($negative == 1) { $gross_salary -= $query_result[0]['value']; }
  else { $gross_salary += $query_result[0]['value']; }
}
if($gross_salary < 0) { $gross_salary = 0; }
$query = 'update payslip set bankaccountid=?,paymenttypeid=?,payroll_payment_date=?,hoursworked=? where payslipid=?';
$query_prm = array($bankaccountid,$paymenttypeid,$payroll_payment_date,$hoursworked,$payslipid);
require('inc/doquery.php');
######

$calc_salary = $gross_salary - $bonus;
$net_salary = $gross_salary;

### used for AVANCE INDEMNITES JOURNALIERES CPS
$cps_base = $base_salary + $seniority_bonus; # 2018 11 02 added seniority, may need average of last 3 months commission???

###### CPS
for ($i = 0; $i < 2; $i++)
{
  $paysliprankA = array(100,110,120,130,140,150,160,170,180,190);
  foreach ($paysliprankA as $rank)
  {
    $name = ''; $comment = ''; $comment_e = '';
    $value = 0; $value_e = 0;
    switch ($rank)
    {
      case '100':
        $name = 'Fonds Spécial Retraite Exceptionnel';
        if ($_SESSION['ds_socialsecuritysectorid'] != 4)
        {
          $comment_e = '1% entre 100k et 486k';
          $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
          if ($val > 486000) { $val = 486000; }
          $value_e = ($val - 100000) * 1 / 100;
        }
      break;
      case '110':
        $name = 'Prestations Familiales';
        if ($_SESSION['ds_socialsecuritysectorid'] != 1 && $_SESSION['ds_socialsecuritysectorid'] != 2 && $_SESSION['ds_socialsecuritysectorid'] != 12)
        {
          if ($year >= 2020)
          {
            $comment_e = '3,33% jusqu\'à 750k';
            $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
            if ($val > 750000) { $val = 750000; }
            $value_e = $val * 3.33 / 100;
          }
          elseif ($year >= 2019)
          {
            $comment_e = '3,24% jusqu\'à 750k';
            $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
            if ($val > 750000) { $val = 750000; }
            $value_e = $val * 3.24 / 100;
          }
          else
          {
            $comment_e = '4,04% jusqu\'à 750k';
            $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
            if ($val > 750000) { $val = 750000; }
            $value_e = $val * 4.04 / 100;
          }
        }
      break;
      case '120':
        $name = 'A.V.T.S.';
        if ($_SESSION['ds_socialsecuritysectorid'] != 4)
        {
          $comment_e = '0,02% jusqu\'à 195k';
          $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
          if ($val > 195000) { $val = 195000; }
          $value_e = $val * 0.02 / 100;
        }
      break;
      case '130':
        $name = 'Accidents du travail';
        if ($_SESSION['ds_socialsecuritysectorid'] != 4)
        {
          $comment_e = '0,77% jusqu\'à 3000k';
          $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
          if ($val > 3000000) { $val = 3000000; }
          $value_e = $val * 0.77 / 100;
        }
      break;
      case '140':
        $name = 'Retraite Tranche A';
        if ($_SESSION['ds_socialsecuritysectorid'] != 4)
        {
          if ($year >= 2020)
          {
            $comment = '7,33% jusqu\'à 264k';
            $comment_e = '14,67% jusqu\'à 264k';
            $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
            if ($val > 264000) { $val = 264000; }
            $value = $val * 7.33 / 100;
            $value_e = $val * 14.67 / 100;
          }
          elseif ($year >= 2019)
          {
            $comment = '7,12% jusqu\'à 259k';
            $comment_e = '14,24% jusqu\'à 259k';
            $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
            if ($val > 259000) { $val = 259000; }
            $value = $val * 7.12 / 100;
            $value_e = $val * 14.24 / 100;
          }
          elseif ($year >= 2018)
          {
            $comment = '6,95% jusqu\'à 258k';
            $comment_e = '13,9% jusqu\'à 258k';
            $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
            if ($val > 258000) { $val = 258000; }
            $value = $val * 6.95 / 100;
            $value_e = $val * 13.9 / 100;
          }
          else
          {
            $comment = '6,78% jusqu\'à 257k';
            $comment_e = '13,56% jusqu\'à 257k';
            $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
            if ($val > 257000) { $val = 257000; }
            $value = $val * 6.78 / 100;
            $value_e = $val * 13.56 / 100;
          }
        }
      break;
      case '150':
        $name = 'Retraite Tranche B';
        if ($_SESSION['ds_socialsecuritysectorid'] != 4)
        {
          if ($year >= 2020)
          {
            $comment = '5,81% entre 264k et 520k';
            $comment_e = '11,62% entre 264k et 520k';
            $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
            if ($val > 264000)
            {
              if ($val > 520000) { $val = 520000; }
              $val -= 264000;
              $value = $val * 5.81 / 100;
              $value_e = $val * 11.62 / 100;
            }
          }
          elseif ($year >= 2019)
          {
            $comment = '5,81% entre 259k et 518k';
            $comment_e = '11,62% entre 259k et 518k';
            $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
            if ($val > 259000)
            {
              if ($val > 518000) { $val = 518000; }
              $val -= 259000;
              $value = $val * 5.81 / 100;
              $value_e = $val * 11.62 / 100;
            }
          }
          elseif ($year >= 2018)
          {
            $comment = '5,81% entre 258k et 516k';
            $comment_e = '11,62% entre 258k et 516k';
            $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
            if ($val > 258000)
            {
              if ($val > 516000) { $val = 516000; }
              $val -= 258000;
              $value = $val * 5.81 / 100;
              $value_e = $val * 11.62 / 100;
            }
          }
          else
          {
            $comment = '5,81% entre 257k et 514k';
            $comment_e = '11,62% entre 257k et 514k';
            $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
            if ($val > 257000)
            {
              if ($val > 514000) { $val = 514000; }
              $val -= 257000;
              $value = $val * 5.81 / 100;
              $value_e = $val * 11.62 / 100;
            }
          }
        }
      break;
      case '160':
        $name = 'Fonds Social Retraite';
        if ($_SESSION['ds_socialsecuritysectorid'] != 4)
        {
          if ($year >= 2020)
          {
            $comment = '0,18% jusqu`\'à 264k';
            $comment_e = '0,36% jusqu\'à 264k';
            $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
            if ($val > 264000) { $val = 264000; }
            $value = $val * 0.18 / 100;
            $value_e = $val * 0.36 / 100;
          }
          elseif ($year >= 2019)
          {
            $comment = '0,17% jusqu`\'à 259k';
            $comment_e = '0,34% jusqu\'à 259k';
            $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
            if ($val > 259000) { $val = 259000; }
            $value = $val * 0.17 / 100;
            $value_e = $val * 0.34 / 100;
          }
          elseif ($year >= 2018)
          {
            $comment = '0,17% jusqu`\'à 258k';
            $comment_e = '0,34% jusqu\'à 258k';
            $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
            if ($val > 258000) { $val = 258000; }
            $value = $val * 0.17 / 100;
            $value_e = $val * 0.34 / 100;
          }
          else
          {
            $comment = '0,17% jusqu`\'à 257k';
            $comment_e = '0,34% jusqu\'à 257k';
            $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
            if ($val > 257000) { $val = 257000; }
            $value = $val * 0.17 / 100;
            $value_e = $val * 0.34 / 100;
          }
        }
      break;
      case '170':
        $name = 'Assurance maladie';
        if ($_SESSION['ds_socialsecuritysectorid'] != 4)
        {
          if ($year >= 2020)
          {
            $comment = '5,65% jusqu\'à 5000k';
            $comment_e = '11,30% jusqu\'à 5000k';
            $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
            if ($val > 5000000) { $val = 5000000; }
            $value = $val * 5.65 / 100;
            $value_e = $val * 11.30 / 100;
          }
          else
          {
            $comment = '5,43% jusqu\'à 5000k';
            $comment_e = '10,86% jusqu\'à 5000k';
            $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
            if ($val > 5000000) { $val = 5000000; }
            $value = $val * 5.43 / 100;
            $value_e = $val * 10.86 / 100;
          }
        }
      break;
      case '180':
        $name = 'Formation professionnelle';
        $comment = '';
        $comment_e = '0,5%';
        $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
        $value = 0;
        $value_e = $val * 0.5 / 100;
      break;
      case '190':
        if ($year > 2019 || $year == 2019 && $month > 2)
        {
          $name = 'Contribution exceptionnelle AM';
          $comment = '';
          $comment_e = '0,75% jusqu\'à 5000k';
          $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
          if ($val > 5000000) { $val = 5000000; }
          $value = 0;
          $value_e = $val * 0.75 / 100;
        }
        elseif ($year == 2019 && $month >= 2)
        {
          $name = 'Contribution exceptionnelle AM';
          $comment = '';
          $comment_e = '0,75% jusqu\'à 5000k (12 Fév)';
          $val = $gross_salary; if ($i == 1) { $val = $cps_base; }
          if ($val > 5000000) { $val = 5000000; }
          $value = 0;
          $value_e = $val * 0.75 / 100;
          $value_e = $value_e * (28-11)/28; # a partir du 12 Fév
        }
      break;
    }
    $value = myround($value);
    $value_e = myround($value_e);
    if ($i == 0)
    {
      $query = 'select payslip_line_netid from payslip_line_net where `rank`=? and payslipid=?';
      $query_prm = array($rank,$payslipid);
      require('inc/doquery.php');
      if ($num_results == 0)
      {
        $query = 'insert into payslip_line_net (negative,value,value_employer,payslip_line_name,payslip_line_comment,payslip_line_comment_employer,`rank`,payslipid) values (?,?,?,?,?,?,?,?)';
      }
      else
      {
        $query = 'update payslip_line_net set negative=?,value=?,value_employer=?,payslip_line_name=?,payslip_line_comment=?,payslip_line_comment_employer=? where `rank`=? and payslipid=?';
      }
      $query_prm = array(1,$value,$value_e,$name,$comment,$comment_e,$rank,$payslipid);
      require('inc/doquery.php');
      $net_salary -= $value;
    }
    else
    {
      $cps_base -= $value;
    }
  }
}
######

###### CST
$cst_bracket = array(); $cst_bracket_base = array();
for ($i=0;$i <= 10;$i++) { $cst_bracket[$i] = 0; $cst_bracket_base[$i] = 0; }
$cst_salary = $gross_salary;
if ($_SESSION['ds_customname'] == 'Espace Paysages'
|| $_SESSION['ds_customname'] == 'Espace 7'
|| $_SESSION['ds_customname'] == 'Pacific Batiment'
|| $_SESSION['ds_customname'] == 'Jurion Protection')
{  } # no rounding
else
{
  $cst_salary = floor($cst_salary / 100) * 100; # round to nearest 100  2018 09 05 floor, according to Séverine
}
$cst = 0;
if ($cst_salary >= 150000)
{
  $cst += 150000 * 0.5 / 100;
  $cst_bracket[0] = 150000 * 0.5 / 100;
  $cst_bracket_base[0] = 150000;
  if ($cst_salary > 150000)
  {
    $temp = $cst_salary - 150000; if ($temp > (250000 - 150000)) { $temp = 250000 - 150000; }
    $cst += $temp * 3 / 100;
    $cst_bracket[1] = $temp * 3 / 100;
    $cst_bracket_base[1] = $temp;
  }
  if ($cst_salary > 250000)
  {
    $temp = $cst_salary - 250000; if ($temp > (400000 - 250000)) { $temp = 400000 - 250000; }
    $cst += $temp * 5 / 100;
    $cst_bracket[2] = $temp * 5 / 100;
    $cst_bracket_base[2] = $temp;
  }
  if ($cst_salary > 400000)
  {
    $temp = $cst_salary - 400000; if ($temp > (700000 - 400000)) { $temp = 700000 - 400000; }
    $cst += $temp * 7 / 100;
    $cst_bracket[3] = $temp * 7 / 100;
    $cst_bracket_base[3] = $temp;
  }
  if ($cst_salary > 700000)
  {
    $temp = $cst_salary - 700000; if ($temp > (1000000 - 700000)) { $temp = 1000000 - 700000; }
    $cst += $temp * 9 / 100;
    $cst_bracket[4] = $temp * 9 / 100;
    $cst_bracket_base[4] = $temp;
  }
  if ($cst_salary > 1000000)
  {
    $temp = $cst_salary - 1000000; if ($temp > (1250000 - 1000000)) { $temp = 1250000 - 1000000; }
    $cst += $temp * 12 / 100;
    $cst_bracket[5] = $temp * 12 / 100;
    $cst_bracket_base[5] = $temp;
  }
  if ($cst_salary > 1250000)
  {
    $temp = $cst_salary - 1250000; if ($temp > (1500000 - 1250000)) { $temp = 1500000 - 1250000; }
    $cst += $temp * 15 / 100;
    $cst_bracket[6] = $temp * 15 / 100;
    $cst_bracket_base[6] = $temp;
  }
  if ($cst_salary > 1500000)
  {
    $temp = $cst_salary - 1500000; if ($temp > (1750000 - 1500000)) { $temp = 1750000 - 1500000; }
    $cst += $temp * 18 / 100;
    $cst_bracket[7] = $temp * 18 / 100;
    $cst_bracket_base[7] = $temp;
  }
  if ($cst_salary > 1750000)
  {
    $temp = $cst_salary - 1750000; if ($temp > (2000000 - 1750000)) { $temp = 2000000 - 1750000; }
    $cst += $temp * 21 / 100;
    $cst_bracket[8] = $temp * 21 / 100;
    $cst_bracket_base[8] = $temp;
  }
  if ($cst_salary > 2000000)
  {
    $temp = $cst_salary - 2000000; if ($temp > (2500000 - 2000000)) { $temp = 2500000 - 2000000; }
    $cst += $temp * 23 / 100;
    $cst_bracket[9] = $temp * 23 / 100;
    $cst_bracket_base[9] = $temp;
  }
  if ($cst_salary >  2500000)
  {
    $cst += ($cst_salary - 2500000) * 25 / 100;
    $cst_bracket[10] = ($cst_salary - 2500000) * 25 / 100;
    $cst_bracket_base[10] = $temp;
  }
}
#### 2018 08 31 saving info for each
$query = 'select payslip_tax_bracketid from payslip_tax_bracket where payslipid=?';
$query_prm = array($payslipid);
require('inc/doquery.php');
if ($num_results)
{
  $payslip_tax_bracketid = $query_result[0]['payslip_tax_bracketid'];
  $query = 'update payslip_tax_bracket set bracket0=?,bracket1=?,bracket2=?,bracket3=?,bracket4=?,bracket5=?,bracket6=?,bracket7=?,bracket8=?,bracket9=?,bracket10=?
  ,bracket_base0=?,bracket_base1=?,bracket_base2=?,bracket_base3=?,bracket_base4=?,bracket_base5=?,bracket_base6=?,bracket_base7=?,bracket_base8=?,bracket_base9=?,bracket_base10=?
  where payslip_tax_bracketid=?';
  $query_prm = array($cst_bracket[0],$cst_bracket[1],$cst_bracket[2],$cst_bracket[3],$cst_bracket[4],$cst_bracket[5],
  $cst_bracket[6],$cst_bracket[7],$cst_bracket[8],$cst_bracket[9],$cst_bracket[10],
  $cst_bracket_base[0],$cst_bracket_base[1],$cst_bracket_base[2],$cst_bracket_base[3],$cst_bracket_base[4],$cst_bracket_base[5],
  $cst_bracket_base[6],$cst_bracket_base[7],$cst_bracket_base[8],$cst_bracket_base[9],$cst_bracket_base[10],
  $payslip_tax_bracketid);
  require('inc/doquery.php');
}
else
{
  $query = 'insert into payslip_tax_bracket (bracket0,bracket1,bracket2,bracket3,bracket4,bracket5,bracket6,bracket7,bracket8,bracket9,bracket10
  ,bracket_base0,bracket_base1,bracket_base2,bracket_base3,bracket_base4,bracket_base5,bracket_base6,bracket_base7,bracket_base8,bracket_base9,bracket_base10
  ,payslipid)
  values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
  $query_prm = array($cst_bracket[0],$cst_bracket[1],$cst_bracket[2],$cst_bracket[3],$cst_bracket[4],$cst_bracket[5],
  $cst_bracket[6],$cst_bracket[7],$cst_bracket[8],$cst_bracket[9],$cst_bracket[10],
  $cst_bracket_base[0],$cst_bracket_base[1],$cst_bracket_base[2],$cst_bracket_base[3],$cst_bracket_base[4],$cst_bracket_base[5],
  $cst_bracket_base[6],$cst_bracket_base[7],$cst_bracket_base[8],$cst_bracket_base[9],$cst_bracket_base[10],$payslipid);
  require('inc/doquery.php');
}
###
$cst = myround($cst);
$net_salary -= $cst;
$query = 'select payslip_line_netid from payslip_line_net where `rank`=? and payslipid=?'; # hardcode rank 500
$query_prm = array(500,$payslipid);
require('inc/doquery.php');
if ($num_results == 0)
{
  $query = 'insert into payslip_line_net (payslipid,`rank`,negative,value,payslip_line_name) values (?,?,?,?,?)'; # hardcode rank 500
  $query_prm = array($payslipid,500,1,$cst,"C.S.T.");
  require('inc/doquery.php');
}
else
{
  $query = 'update payslip_line_net set negative=?,value=?,payslip_line_name=? where `rank`=500 and payslipid=?'; # hardcode rank 500
  $query_prm = array(1,$cst,"C.S.T.",$payslipid);
  require('inc/doquery.php');
}
######

##### remboursement des avances d'indemnités journalières
$reimburse_days_suggested = myround(d_multiply($cps_base,$reimburse_days) / 30);
$query = 'select payslip_line_netid,value,payslip_line_comment from payslip_line_net where `rank`=? and payslipid=?'; # hardcode rank 10050
$query_prm = array(10050,$payslipid);
require('inc/doquery.php');
if ($num_results == 0)
{
  $reimburse_days_comment = 0;
  $query = 'insert into payslip_line_net (payslipid,`rank`,negative,value,payslip_line_name,payslip_line_comment) values (?,?,?,?,?,?)'; # hardcode rank 10050
  $query_prm = array($payslipid,10050,0,$reimburse_days,"Remboursement des avances d'indemnités journalières",$reimburse_days_comment);
  require('inc/doquery.php');
}
else
{
  if (!isset($_POST['reimburse_days']))
  {
    $reimburse_days = $query_result[0]['value']+0;
    $reimburse_days_suggested = myround(d_multiply($cps_base,$reimburse_days) / 30);
    $reimburse_days_comment = $query_result[0]['payslip_line_comment']+0;
  }
  elseif (!isset($_POST['reimburse_days_comment']))
  {
    $reimburse_days_comment = $reimburse_days_suggested;
  }
  $query = 'update payslip_line_net set negative=?,value=?,payslip_line_name=?,payslip_line_comment=? where `rank`=10050 and payslipid=?'; # hardcode rank 10050
  $query_prm = array(0,$reimburse_days,"Remboursement des avances d'indemnités journalières",$reimburse_days_comment,$payslipid);
  require('inc/doquery.php');
}
$net_salary += (double) $reimburse_days_comment;
#####

##### net modif
$query = 'select override,payslip_line_netid,value,payslip_line_comment from payslip_line_net where `rank`=? and payslipid=?'; # hardcode rank 10100
$query_prm = array(10100,$payslipid);
require('inc/doquery.php');
if ($num_results == 0)
{
  $query = 'insert into payslip_line_net (payslipid,`rank`,negative,value,payslip_line_name,payslip_line_comment,override)
  values (?,?,?,?,?,?,?)'; # hardcode rank 10100
  $query_prm = array($payslipid,10100,0,$netadd,"Ajout net",$netadd_comment,$net_modif_account10100id);
  require('inc/doquery.php');
}
else
{
  if (!isset($_POST['netadd']))
  {
    $net_modif_account10100id = $query_result[0]['override']+0;
    $netadd = $query_result[0]['value']+0;
    $netadd_comment = $query_result[0]['payslip_line_comment'];
  }
  $query = 'update payslip_line_net set negative=?,value=?,payslip_line_name=?,payslip_line_comment=?,override=?
  where `rank`=10100 and payslipid=?'; # hardcode rank 10100
  $query_prm = array(0,$netadd,"Ajout net",$netadd_comment,$net_modif_account10100id,$payslipid);
  require('inc/doquery.php');
}
$net_salary += $netadd;
#
$query = 'select override,payslip_line_netid,value,payslip_line_comment from payslip_line_net where `rank`=? and payslipid=?'; # hardcode rank 10101
$query_prm = array(10101,$payslipid);
require('inc/doquery.php');
if ($num_results == 0)
{
  $query = 'insert into payslip_line_net (payslipid,`rank`,negative,value,payslip_line_name,payslip_line_comment,override)
  values (?,?,?,?,?,?,?)'; # hardcode rank 10101
  $query_prm = array($payslipid,10101,0,$netadd1,"Ajout net",$netadd_comment1,$net_modif_account10101id);
  require('inc/doquery.php');
}
else
{
  if (!isset($_POST['netadd1']))
  {
    $net_modif_account10101id = $query_result[0]['override']+0;
    $netadd1 = $query_result[0]['value']+0;
    $netadd_comment1 = $query_result[0]['payslip_line_comment'];
  }
  $query = 'update payslip_line_net set negative=?,value=?,payslip_line_name=?,payslip_line_comment=?,override=?
  where `rank`=10101 and payslipid=?'; # hardcode rank 10101
  $query_prm = array(0,$netadd1,"Ajout net",$netadd_comment1,$net_modif_account10101id,$payslipid);
  require('inc/doquery.php');
}
$net_salary += $netadd1;

$query = 'select override,payslip_line_netid,value,payslip_line_comment from payslip_line_net where `rank`=? and payslipid=?'; # hardcode rank 10200
$query_prm = array(10200,$payslipid);
require('inc/doquery.php');
if ($num_results == 0)
{
  $query = 'insert into payslip_line_net (payslipid,`rank`,negative,value,payslip_line_name,payslip_line_comment,override)
  values (?,?,?,?,?,?,?)'; # hardcode rank 10200
  $query_prm = array($payslipid,10200,1,$netdeduct,"Déduction nette",$netdeduct_comment,$net_modif_account10200id);
  require('inc/doquery.php');
}
else
{
  if (!isset($_POST['netdeduct']))
  {
    $net_modif_account10200id = $query_result[0]['override']+0;
    $netdeduct = $query_result[0]['value']+0;
    $netdeduct_comment = $query_result[0]['payslip_line_comment'];
  }
  $query = 'update payslip_line_net set negative=?,value=?,payslip_line_name=?,payslip_line_comment=?,override=?
  where `rank`=10200 and payslipid=?'; # hardcode rank 10200
  $query_prm = array(1,$netdeduct,"Déduction nette",$netdeduct_comment,$net_modif_account10200id,$payslipid);
  require('inc/doquery.php');
}
$net_salary -= $netdeduct;
#
$query = 'select override,payslip_line_netid,value,payslip_line_comment from payslip_line_net where `rank`=? and payslipid=?'; # hardcode rank 10201
$query_prm = array(10201,$payslipid);
require('inc/doquery.php');
if ($num_results == 0)
{
  $query = 'insert into payslip_line_net (payslipid,`rank`,negative,value,payslip_line_name,payslip_line_comment,override)
  values (?,?,?,?,?,?,?)'; # hardcode rank 10201
  $query_prm = array($payslipid,10201,1,$netdeduct1,"Déduction nette",$netdeduct_comment1,$net_modif_account10201id);
  require('inc/doquery.php');
}
else
{
  if (!isset($_POST['netdeduct1']))
  {
    $net_modif_account10201id = $query_result[0]['override']+0;
    $netdeduct1 = $query_result[0]['value']+0;
    $netdeduct_comment1 = $query_result[0]['payslip_line_comment'];
  }
  $query = 'update payslip_line_net set negative=?,value=?,payslip_line_name=?,payslip_line_comment=?,override=?
  where `rank`=10201 and payslipid=?'; # hardcode rank 10201
  $query_prm = array(1,$netdeduct1,"Déduction nette",$netdeduct_comment1,$net_modif_account10201id,$payslipid);
  require('inc/doquery.php');
}
$net_salary -= $netdeduct1;
#
$query = 'select override,payslip_line_netid,value,payslip_line_comment from payslip_line_net
where `rank`=? and payslipid=?'; # hardcode rank 10202
$query_prm = array(10202,$payslipid);
require('inc/doquery.php');
if ($num_results == 0)
{
  $query = 'insert into payslip_line_net (payslipid,`rank`,negative,value,payslip_line_name,payslip_line_comment,override)
  values (?,?,?,?,?,?,?)'; # hardcode rank 10202
  $query_prm = array($payslipid,10202,1,$netdeduct2,"Déduction nette",$netdeduct_comment2,$net_modif_account10202id);
  require('inc/doquery.php');
}
else
{
  if (!isset($_POST['netdeduct2']))
  {
    $net_modif_account10202id = $query_result[0]['override']+0;
    $netdeduct2 = $query_result[0]['value']+0;
    $netdeduct_comment2 = $query_result[0]['payslip_line_comment'];
  }
  $query = 'update payslip_line_net set negative=?,value=?,payslip_line_name=?,payslip_line_comment=?,override=?
  where `rank`=10202 and payslipid=?'; # hardcode rank 10202
  $query_prm = array(1,$netdeduct2,"Déduction nette",$netdeduct_comment2,$net_modif_account10202id,$payslipid);
  require('inc/doquery.php');
}
$net_salary -= $netdeduct2;
#
$query = 'select override,payslip_line_netid,value,payslip_line_comment from payslip_line_net
where `rank`=? and payslipid=?'; # hardcode rank 10203
$query_prm = array(10203,$payslipid);
require('inc/doquery.php');
if ($num_results == 0)
{
  $query = 'insert into payslip_line_net (payslipid,`rank`,negative,value,payslip_line_name,payslip_line_comment,override)
  values (?,?,?,?,?,?,?)'; # hardcode rank 10203
  $query_prm = array($payslipid,10203,1,$netdeduct3,"Déduction nette",$netdeduct_comment3,$net_modif_account10203id);
  require('inc/doquery.php');
}
else
{
  if (!isset($_POST['netdeduct3']))
  {
    $net_modif_account10203id = $query_result[0]['override']+0;
    $netdeduct3 = $query_result[0]['value']+0;
    $netdeduct_comment3 = $query_result[0]['payslip_line_comment'];
  }
  $query = 'update payslip_line_net set negative=?,value=?,payslip_line_name=?,payslip_line_comment=?,override=?
  where `rank`=10203 and payslipid=?'; # hardcode rank 10203
  $query_prm = array(1,$netdeduct3,"Déduction nette",$netdeduct_comment3,$net_modif_account10203id,$payslipid);
  require('inc/doquery.php');
}
$net_salary -= $netdeduct3;
#
$query = 'select override,payslip_line_netid,value,payslip_line_comment from payslip_line_net
where `rank`=? and payslipid=?'; # hardcode rank 10204
$query_prm = array(10204,$payslipid);
require('inc/doquery.php');
if ($num_results == 0)
{
  $query = 'insert into payslip_line_net (payslipid,`rank`,negative,value,payslip_line_name,payslip_line_comment,override)
  values (?,?,?,?,?,?,?)'; # hardcode rank 10204
  $query_prm = array($payslipid,10204,1,$netdeduct4,"Déduction nette",$netdeduct_comment4,$net_modif_account10204id);
  require('inc/doquery.php');
}
else
{
  if (!isset($_POST['netdeduct4']))
  {
    $net_modif_account10204id = $query_result[0]['override']+0;
    $netdeduct4 = $query_result[0]['value']+0;
    $netdeduct_comment4 = $query_result[0]['payslip_line_comment'];
  }
  $query = 'update payslip_line_net set negative=?,value=?,payslip_line_name=?,payslip_line_comment=?,override=?
  where `rank`=10204 and payslipid=?'; # hardcode rank 10204
  $query_prm = array(1,$netdeduct4,"Déduction nette",$netdeduct_comment4,$net_modif_account10204id,$payslipid);
  require('inc/doquery.php');
}
$net_salary -= $netdeduct4;

$query = 'select advance from payslip_advance where employeeid=? and month=? and year=?';
$query_prm = array($employeeid,$month,$year);
require('inc/doquery.php');
if ($num_results)
{
  $advance = $query_result[0]['advance'];
  $query = 'select payslip_line_netid,value from payslip_line_net where `rank`=? and payslipid=?'; # hardcode rank 11000
  $query_prm = array(11000,$payslipid);
  require('inc/doquery.php');
  if ($num_results == 0)
  {
    $query = 'insert into payslip_line_net (payslipid,`rank`,negative,value,payslip_line_name,payslip_line_comment) values (?,?,?,?,?,?)'; # hardcode rank 11000
    $query_prm = array($payslipid,11000,1,$advance,"Avance sur salaire",'');
    require('inc/doquery.php');
  }
  else
  {
    $query = 'update payslip_line_net set negative=?,value=?,payslip_line_name=?,payslip_line_comment=? where `rank`=11000 and payslipid=?'; # hardcode rank 11000
    $query_prm = array(1,$advance,"Avance sur salaire",'',$payslipid);
    require('inc/doquery.php');
  }
  $net_salary -= $advance;
}
#####

$query = 'update payslip set hours_text=?,calc_salary=?,gross_salary=?,net_salary=? where payslipid=?';
$query_prm = array($hours_text,$calc_salary,$gross_salary,$net_salary,$payslipid);
require('inc/doquery.php');
if ($num_results) { echo '<p>Bulletin de paie modifié.</p>'; }

echo '<h2>';
### need to remove from employeelist those employees hired after current month/year and those who exit before current month/year
$employeelistA = array();
$query = 'select employeeid,month(hiringdate) as month,year(hiringdate) as year,jobid
,year(exitdate) as exityear,month(exitdate) as exitmonth
from employee
where deleted=0 and hiringdate<=? and employeename<>"" and (exitdate>=? or exitdate is null or exitdate="0000-00-00")
order by employeename';
$temp_day = mb_substr($payslipdate,8,2);
$temp_month = mb_substr($payslipdate,5,2)+1;
$temp_year = mb_substr($payslipdate,0,4);
if ($temp_month == 13) { $temp_month = 1; $temp_year++; }
$temp_payslipdate = d_builddate($temp_day,$temp_month,$temp_year);
$query_prm = array($temp_payslipdate,$payslipdate);
require('inc/doquery.php');
for ($y=0; $y < $num_results; $y++)
{
  array_push($employeelistA, $query_result[$y]['employeeid']);
}
###

$previous_eii = array_search($employeeid, $employeelistA)-1;
$next_eii = $previous_eii + 2;
if ($previous_eii == -1) { $previous_eii = max(array_keys($employeelistA)); }
if ($next_eii > max(array_keys($employeelistA))) { $next_eii = 0; }
echo '<a href="hr.php?hrmenu=payroll_modify&year='.$year.'&month='.$month.'&employeeid='.$employeelistA[$previous_eii].'">&#8592;</a>';
echo ' ','<a href="hr.php?hrmenu=payroll_modify&year='.$year.'&month='.$month.'&employeeid='.$employeelistA[$next_eii].'">&#8594;</a>';
echo ' <a href="printwindow.php?report=showpayslip&payslipid='.$payslipid.'" target=_blank>Bulletin de paie</a> &nbsp; - &nbsp; ' . $employeeA[$employeeid] . ' &nbsp; - &nbsp; ';
echo $period_text;

###
if ($status == 1) { echo ' &nbsp; - &nbsp; Verrouillé'; }

echo '</h2>';
if ($e_payslipinfo != '')
{
  echo '<br><div class="myblock" style="width:90%;margin:auto;">';
  echo d_output($e_payslipinfo, TRUE);
  echo '</div><br>';
}
echo '<form method="post" action="hr.php">';
echo '<table class="report"><tr><td>Emplois:<td align=right>' .d_output($jobname);

##########################
echo '<td rowspan=8> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;';
$ok = 1;
if ($_SESSION['ds_customname'] == 'Espace Paysages'
|| $_SESSION['ds_customname'] == 'Espace 7'
|| $_SESSION['ds_customname'] == 'Pacific Batiment'
|| $_SESSION['ds_customname'] == 'Jurion Protection') # TODO change calcul heures sup/maj
{ $ok = 1; }
if ($ok && isset($employee_monthid[$employeeid]))
{
  echo '<td rowspan=8 valign=top>';
  echo '<b>Infos pointage:</b>';
  if ($minutes_worked[$employeeid] != 0) { echo '<br>Heures travaillées: ',showtimeworked($minutes_worked[$employeeid]); }
  if ($nonworked1[$employeeid] != 0) { echo '<br>Congés Payé: ',showtimeworked($nonworked1[$employeeid]); }
  if ($nonworked2[$employeeid] != 0) { echo '<br>Récup: ',showtimeworked($nonworked2[$employeeid]); }
  if ($nonworked3[$employeeid] != 0) { echo '<br>Mal/Acc/Mat: ',showtimeworked($nonworked3[$employeeid]); }
  if ($nonworked4[$employeeid] != 0) { echo '<br>Divers: ',showtimeworked($nonworked4[$employeeid]); }
  if ($nonworked5[$employeeid] != 0) { echo '<br>Ajouté: ',showtimeworked($nonworked5[$employeeid]); }
  if ($meal_allowance[$employeeid] != 0) { echo '<br>Panier: ',$meal_allowance[$employeeid]; }
  $text = '';
  if (isset($sequenceA[$employeeid]))
  {
    foreach ($sequenceA[$employeeid] as $addtext)
    {
      $text .= $addtext . ' ';
    }
  }
  if ($text != '') { echo '<br>Sequences: ',$text; }
  echo '<br>';
  if (isset($em[0][115]) && $em[0][115] != 0) { echo '<br>Maj 15%: ',showtimeworked($em[0][115]); }
  if (isset($em[0][125]) && $em[0][125] != 0) { echo '<br>Maj 25%: ',showtimeworked($em[0][125]); }
  if (isset($em[0][150]) && $em[0][150] != 0) { echo '<br>Maj 50%: ',showtimeworked($em[0][150]); }
  if (isset($em[0][200]) && $em[0][200] != 0) { echo '<br>Maj 100%: ',showtimeworked($em[0][200]); }
  if (isset($em[1][125]) && $em[1][125] != 0) { echo '<br>Sup 25%: ',showtimeworked($em[1][125]); }
  if (isset($em[1][150]) && $em[1][150] != 0) { echo '<br>Sup 50%: ',showtimeworked($em[1][150]); }
  if (isset($em[1][165]) && $em[1][165] != 0) { echo '<br>Sup 65%: ',showtimeworked($em[1][165]); }
  if (isset($em[1][175]) && $em[1][175] != 0) { echo '<br>Sup 75%: ',showtimeworked($em[1][175]); }
  if (isset($em[1][200]) && $em[1][200] != 0) { echo '<br>Sup 100%: ',showtimeworked($em[1][200]); }
  if (isset($em[1][300]) && $em[1][300] != 0) { echo '<br>Sup 200%: ',showtimeworked($em[1][300]); }
  echo '<br><br><input type=checkbox name="apply_hours" value=1> Appliquer';
}
$query = 'select * from absence_request
where accepted=1 and employeeid=? and ((month(startdate)=? and year(startdate)=?) || (month(stopdate)=? and year(stopdate)=?))
order by startdate';
$query_prm = array($employeeid,$month,$year,$month,$year);
require('inc/doquery.php');
if ($num_results)
{
  echo '<td rowspan=8 valign=top><b>Absences:</b>';
  for ($i=0; $i < $num_results; $i++)
  {
    $startdate = $query_result[$i]['startdate'];
    $stopdate = $query_result[$i]['stopdate'];
    $absence_reasonid = $query_result[$i]['absence_reasonid'];
    $absence_request_comment = $query_result[$i]['absence_request_comment'];
    echo '<br>De ', datefix2($startdate), ' à ', datefix2($stopdate);
    if (isset($absence_reasonA[$absence_reasonid])) { echo ', ',d_output($absence_reasonA[$absence_reasonid]); }
    if ($absence_request_comment != '') { echo ', ', d_output($absence_request_comment); }
  }
}
##########################

echo '<tr><td>Salaire de base:<td align=right>' .myfix($e_base_salary);

if (1==1)# 2020 03 27 testing
{
  echo '<tr><td>Horaire de référence:<td align=right>';
  echo '<input type=text STYLE="text-align:right" name="post_hourspermonth" value="'.d_input($e_hourspermonth).'">';
}
else { echo '<tr><td>Horaire de référence:<td align=right>',$e_hourspermonth; }
echo '<tr><td>Congés Payés acquis avant ce mois:<td align=right>',  d_output($vacationdays_last);
echo '<tr><td>Congés Payés acquis ce mois:<td align=right><input type=text STYLE="text-align:right" name="vacationdays_added" value="' . d_input($vacationdays_added) . '">';
echo '<tr><td>Congés Payés utilisés ce mois:<td align=right><input type=text STYLE="text-align:right" name="vacationdays_used" value="' . d_input($vacationdays_used) . '">';
echo '<tr><td>Solde Congés Payés:<td align=right>';
echo d_output($vacationdays);
echo $rate_string;
echo '</table>';
/*
echo '<p>Lien utiles : &nbsp; <a href="https://www.service-public.pf/trav/les-conges/" target=_blank>Les congés</a>
&nbsp; <a href="https://www.service-public.pf/trav/les-heures-supplementaires/" target=_blank>Les heures sup</a>
&nbsp; <a href="http://www.cps.pf/files/etat_remb_ij.pdf" target=_blank>Caisse de Prévoyance Sociale</a></p>';
*/
echo '<br><table class=report>';

foreach ($time_modA as $rank)
{
  echo '<tr><td>',$time_mod_nameA[$rank],':';
  echo '<td align=right>';
  if ($rank == 10 && $_SESSION['seniority_bonus_calc'] > 0 && $merge_absence == 0) { echo $time_mod_valueA[$rank]; }
  else
  {
    echo '<input type="number" STYLE="text-align:right" name="time_mod'.$rank.'" value="'.$time_mod_valueA[$rank].'" size=12';
    if ($rank == 50 || $rank == 60 || $rank == 61) { echo ' step='.$half_step; }
    elseif ($rank == 25 || $rank == 35 || $rank == 36 || $rank == 37 || $rank == 40) { echo ' step=0.01'; }
    echo '>';
  }
  echo '<td>&nbsp;',$time_mod_denomA[$rank],'&nbsp;
  <td><input type=text STYLE="text-align:right" name="time_mod_comment'.$rank.'" value="' . d_input($time_mod_commentA[$rank]) . '" size=40>';
  if ($rank == 30)
  {
    if ($override_30 == 0) { $override_30 = ''; }
    echo ' Heures : <input type="text" STYLE="text-align:right" name="override_30" value="'.$override_30.'">';
  }
  elseif ($rank == 31)
  {
    if ($override_31 == 0) { $override_31 = ''; }
    echo ' Heures : <input type="text" STYLE="text-align:right" name="override_31" value="'.$override_31.'">';
  }
  elseif ($rank == 32)
  {
    if ($override_32 == 0) { $override_32 = ''; }
    echo ' Heures : <input type="text" STYLE="text-align:right" name="override_32" value="'.$override_32.'">';
  }
  elseif ($rank == 33)
  {
    if ($override_33 == 0) { $override_33 = ''; }
    echo ' Heures : <input type="text" STYLE="text-align:right" name="override_33" value="'.$override_33.'">';
  }
  elseif ($rank == 34)
  {
    if ($override_34 == 0) { $override_34 = ''; }
    echo ' Heures : <input type="text" STYLE="text-align:right" name="override_34" value="'.$override_34.'">';
  }
  elseif ($rank == 40)
  {
    echo ' ',myround($e_hourspermonth/30,2),' heures par jour';
  }
  elseif ($rank == 50)
  {
    if ($override_50 == 0) { $override_50 = ''; }
    echo ' Taux manuel : <input type="text" STYLE="text-align:right" name="override_50" value="'.$override_50.'">';
  }
  elseif ($rank == 60)
  {
    if ($override_60 == 0) { $override_60 = ''; }
    echo ' Taux manuel : <input type="text" STYLE="text-align:right" name="override_60" value="'.$override_60.'">';
  }
  elseif ($rank == 61)
  {
    if ($override_61 == 0) { $override_61 = ''; }
    echo ' Taux manuel : <input type="text" STYLE="text-align:right" name="override_61" value="'.$override_61.'">';
  }
}
echo '<tr><td>Remboursement des avances d\'indemnités journalières:<td align=right>
<input type="number" STYLE="text-align:right" name="reimburse_days" value="'.$reimburse_days.'" size=12 step=0.5>
<td>&nbsp;jours&nbsp;
<td><input type="text" STYLE="text-align:right" name="reimburse_days_comment" value="'.$reimburse_days_comment.'"> XPF';
if ($reimburse_days_suggested > 0) { echo ' &nbsp; &nbsp; Suggéré: '.myfix($reimburse_days_suggested); }
echo '<tr><td>Ajout net: ';
$dp_itemname = 'net_modif_account'; $dp_notable = 1; $dp_addtoid = '10100'; $dp_selectedid = $net_modif_account10100id; require('inc/selectitem.php');
echo '<td align=right><input type="number" STYLE="text-align:right" name="netadd" value="'.$netadd.'" size=12><td>&nbsp;XPF&nbsp;
<td><input type=text STYLE="text-align:right" name="netadd_comment" value="' . d_input($netadd_comment) . '" size=40>';
echo '<tr><td>Ajout net: ';
$dp_itemname = 'net_modif_account'; $dp_notable = 1; $dp_addtoid = '10101'; $dp_selectedid = $net_modif_account10101id; require('inc/selectitem.php');
echo '<td align=right><input type="number" STYLE="text-align:right" name="netadd1" value="'.$netadd1.'" size=12><td>&nbsp;XPF&nbsp;
<td><input type=text STYLE="text-align:right" name="netadd_comment1" value="' . d_input($netadd_comment1) . '" size=40>';
echo '<tr><td>Déduction nette: ';
$dp_itemname = 'net_modif_account'; $dp_notable = 1; $dp_addtoid = '10200'; $dp_selectedid = $net_modif_account10200id; require('inc/selectitem.php');
echo '<td align=right><input type="number" STYLE="text-align:right" name="netdeduct" value="'.$netdeduct.'" size=12><td>&nbsp;XPF&nbsp;
<td><input type=text STYLE="text-align:right" name="netdeduct_comment" value="' . d_input($netdeduct_comment) . '" size=40>';
echo '<tr><td>Déduction nette: ';
$dp_itemname = 'net_modif_account'; $dp_notable = 1; $dp_addtoid = '10201'; $dp_selectedid = $net_modif_account10201id; require('inc/selectitem.php');
echo '<td align=right><input type="number" STYLE="text-align:right" name="netdeduct1" value="'.$netdeduct1.'" size=12><td>&nbsp;XPF&nbsp;
<td><input type=text STYLE="text-align:right" name="netdeduct_comment1" value="' . d_input($netdeduct_comment1) . '" size=40>';
echo '<tr><td>Déduction nette: ';
$dp_itemname = 'net_modif_account'; $dp_notable = 1; $dp_addtoid = '10202'; $dp_selectedid = $net_modif_account10202id; require('inc/selectitem.php');
echo '<td align=right><input type="number" STYLE="text-align:right" name="netdeduct2" value="'.$netdeduct2.'" size=12><td>&nbsp;XPF&nbsp;
<td><input type=text STYLE="text-align:right" name="netdeduct_comment2" value="' . d_input($netdeduct_comment2) . '" size=40>';
echo '<tr><td>Déduction nette: ';
$dp_itemname = 'net_modif_account'; $dp_notable = 1; $dp_addtoid = '10203'; $dp_selectedid = $net_modif_account10203id; require('inc/selectitem.php');
echo '<td align=right><input type="number" STYLE="text-align:right" name="netdeduct3" value="'.$netdeduct3.'" size=12><td>&nbsp;XPF&nbsp;
<td><input type=text STYLE="text-align:right" name="netdeduct_comment3" value="' . d_input($netdeduct_comment3) . '" size=40>';
echo '<tr><td>Déduction nette: ';
$dp_itemname = 'net_modif_account'; $dp_notable = 1; $dp_addtoid = '10204'; $dp_selectedid = $net_modif_account10204id; require('inc/selectitem.php');
echo '<td align=right><input type="number" STYLE="text-align:right" name="netdeduct4" value="'.$netdeduct4.'" size=12><td>&nbsp;XPF&nbsp;
<td><input type=text STYLE="text-align:right" name="netdeduct_comment4" value="' . d_input($netdeduct_comment4) . '" size=40>';
if ($advance)
{
  echo '<tr><td>Avance sur salaire:<td align=right>'.myfix($advance).'<td>&nbsp;XPF&nbsp;<td>';
}
echo '<tr><td>Paiement:<td colspan=3><select name="paymenttypeid">
<option value=0></option>
<option value=3'; if ($paymenttypeid==3) { echo ' selected'; } echo '>Virement</option>
<option value=2'; if ($paymenttypeid==2) { echo ' selected'; } echo '>Cheque</option>
<option value=1'; if ($paymenttypeid==1) { echo ' selected'; } echo '>Espèces</option>
</select>
 &nbsp; ';
$dp_itemname = 'bankaccount'; $dp_notable=1; $dp_selectedid=$bankaccountid;
require('inc/selectitem.php');
$datename = 'payroll_payment_date';
if (isset($payroll_payment_date)) { $selecteddate = $payroll_payment_date; }
else { $dp_setempty = 1; }
require('inc/datepicker.php');
echo '<tr><td>Remplacer "X heures rémunérées":<td colspan=4>
<input type="text" STYLE="text-align:right" name="hours_text" value="'.$hours_text.'" size=40>';
echo '</table>';

echo '<br><center><textarea name="payslipcomment" rows="5" cols="120">',$payslipcomment,'</textarea><br>';
if ($status == 0 || ($_SESSION['ds_ishrsuperuser']))# && $_SESSION['ds_systemaccess']
{
  if ($status == 0)
  {
    echo '<br><input type=checkbox name=confirm1 value=1> <i>Verrouiller</i> <input type=checkbox name=confirm2 value=1><br><br>';
  }
  else
  {
    echo '<br><input type=checkbox name=unconfirm value=1> <i>Dé-Verrouiller</i><br><br>';
  }
  echo '<input type=hidden name="hrmenu" value="'. $hrmenu . '"><input type=hidden name="payslipid" value="'. $payslipid . '">
  <input type="submit" value="Valider">';
}
echo '</center></form>';

?>