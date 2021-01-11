<?php
### display (and calculation) ONLY, all data should be read and displayed, NO modifying data!
?>
<style>
body {
  background-color: #DBD7D7;
  font-family: <?php echo $_SESSION['ds_user_font_print']; ?>;
}

div.payslip {
  width: 750px;
  padding: 0;
  border: 0;
  margin: 0 auto;
  vertical-align: top;
}

div.companylogo {
  position:absolute;
  top:10px;
  right:10px;
}

span.sliptitle {
  font-weight: bold;
  font-size: 200%;
}

span.sliptitle2 {
  font-size: 150%;
}

div.employee {
  border-style: solid;
  border-width: 1px;
  top: 120px;
  left: 25px;
  position: absolute;
  width: 350px;
  height: 200px;
  font-size: small;
}

div.employer {
  top: 120px;
  right: 25px;
  position: absolute;
  width: 300px;
  height: 200px;
}

div.maincalc {
  top: 330px;
  left: 25px;
  position: absolute;
  width: 744px;
  height: 200px;
}

thead {
  border-style: solid;
  border-width: 3px 1px 1px 1px;
}

tr.sum {
  border-style: solid;
  border-width: 3px 1px 3px 1px;
}

</style>
<?php

# TODO remove d_td_old

if ($_SESSION['ds_ishrsuperuser'] != 1) { exit; }

$payslipid = (int) $_GET['payslipid'];
if ($payslipid < 1) { exit; }

require('preload/employee.php');



$query = 'select * from payslip where payslipid=?';
$query_prm = array($payslipid);
require('inc/doquery.php');
$hours_text = $query_result[0]['hours_text'];
$employeeid = $query_result[0]['employeeid'];
$payslipdate = $query_result[0]['payslipdate'];
$base_salary = $query_result[0]['base_salary']+0;
$hourspermonth = $query_result[0]['hourspermonth']+0;
$hoursperday = round($hourspermonth/26,2);
$hoursworked = (double) $query_result[0]['hoursworked']+0;
$gross_salary = $query_result[0]['gross_salary']+0;
$net_salary = $query_result[0]['net_salary']+0;
$status = $query_result[0]['status']+0;
$payslipcomment = $query_result[0]['payslipcomment'];
$vacationdays_added = $query_result[0]['vacationdays_added']+0;
$vacationdays_used = $query_result[0]['vacationdays_used']+0;
$paymenttypeid = $query_result[0]['paymenttypeid'];
$bankaccountid = $query_result[0]['bankaccountid'];

$month = mb_substr($payslipdate,5,2);
$year = mb_substr($payslipdate,0,4);
$lastmonth = mb_substr($payslipdate,5,2) - 1;
$lastyear = mb_substr($payslipdate,0,4)+0;
if ($lastmonth == 0) { $lastmonth = 12; $lastyear--; }

$query = 'select exitdate,hiringdate,referencenumber,salary_account_title,salary_account,salary_bankid,hourly_pay
from employee where employeeid=?';
$query_prm = array($employeeid);
require('inc/doquery.php');
if ($num_results)
{
  $exitdate = $query_result[0]['exitdate']; if ($exitdate == '0000-00-00') { $exitdate = ''; }
  $hiringdate = $query_result[0]['hiringdate']; if ($hiringdate == '0000-00-00') { $hiringdate = ''; }
  $referencenumber = $query_result[0]['referencenumber'];
  $salary_account_title = $query_result[0]['salary_account_title'];
  $salary_account = $query_result[0]['salary_account'];
  $salary_bankid = $query_result[0]['salary_bankid'];
  $hourly_pay = $query_result[0]['hourly_pay'];
}
else
{
  $exitdate = '';
  $hiringdate = '';
  $referencenumber = '';
  $salary_account_title = '';
  $salary_account = '';
  $salary_bankid = '';
}
$duration_years = substr($payslipdate,0,4) - substr($hiringdate,0,4);
$duration_months = substr($payslipdate,5,2) - substr($hiringdate,5,2);
if ($duration_months < 0)
{
  $duration_years--;
  $duration_months += 12;
}
$duration_text = '';
if ($duration_years > 0)
{
  $duration_text = $duration_years. ' an';
  if ($duration_years > 1) { $duration_text .= 's'; }
}
if ($duration_months > 0) { $duration_text .= ' '.$duration_months.' mois'; }

$query = 'select jobname from job where jobid=?';
$query_prm = array($employee_jobidA[$employeeid]);
require('inc/doquery.php');
if ($num_results) { $jobname = $query_result[0]['jobname']; }
else { $jobname = ''; }

echo '<div class="main"><div class="payslip"><span class="sliptitle">Bulletin de Paie'; if ($status == 0) { echo ' - NON VALIDÉ'; }
echo '</span><span class="sliptitle2"><br>';

$merge_absence = 0;
if ($_SESSION['ds_payroll_startday'])
{
  $enddate = d_builddate(($_SESSION['ds_payroll_startday']-1),$month,$year);
  if ($_SESSION['ds_customname'] == 'Team ELEC' && $month == 2 && $lastyear == 2020)
  { $show_payslipdate = d_builddate(1,$month,$lastyear);; }
  else { $show_payslipdate = d_builddate($_SESSION['ds_payroll_startday'],$lastmonth,$lastyear); }
  if ($exitdate != '' && $exitdate <= $enddate) { $enddate = $exitdate; $merge_absence = 1; }
  if ($hiringdate != '' && $payslipdate <= $hiringdate) { $show_payslipdate = $hiringdate; }
  echo datefix($show_payslipdate),' à ',datefix($enddate);
}
else
{
  $enddate = d_builddate(31,substr($payslipdate,5,2),substr($payslipdate,0,4));
  if ($exitdate != '' && $exitdate <= $enddate) { $enddate = $exitdate; $merge_absence = 1; }
  if ($hiringdate != '' && $payslipdate <= $hiringdate) { $payslipdate = $hiringdate; }
  echo datefix($payslipdate) . ' à ' . datefix($enddate);
}

echo '</span>
<div class="companylogo">';
if (isset($_SESSION['ds_customname']) && $_SESSION['ds_customname'] != "")
{
  $ourlogofile = './custom_available/' . mb_strtolower($_SESSION['ds_customname']) . '.jpg';
  if (file_exists($ourlogofile)) { echo '<IMG alt="' . $_SESSION['ds_customname'] . '" src="' . $ourlogofile . '" border=0 STYLE="max-height: 100px;">'; }
  else { echo '<b>' . d_output($_SESSION['ds_customname']) . '</b>'; } 
}
echo '</div>';

$query = 'select geoaddress,postaladdress1,postaladdress2,postalcode,townid,telnumber1,dn from employeepersoinfos where employeeid=?';
$query_prm = array($employeeid);
require('inc/doquery.php');
if ($num_results) { $row = $query_result[0]; $dn = $query_result[0]['dn']; }
else { $row['geoaddress'] = $row['postaladdress1'] = $row['postaladdress2'] = $row['postalcode'] = $row['telnumber1'] = ''; $row['townid'] = 0; $dn = ''; }
echo '<div class="employee"><b>';
if (isset($employeeA[$employeeid])) { echo d_output(mb_strtoupper($employeeA[$employeeid])); }
echo '</b>';
if ($row['geoaddress'] != '') { echo '<br>',d_output($row['geoaddress']); }
if ($row['postaladdress1'] != '' || $row['postaladdress2'] != '') { echo '<br>'; }
if ($row['postaladdress1'] != '') { echo d_output($row['postaladdress1']); }
if ($row['postaladdress2'] != '')
{
  if ($row['postaladdress1'] != '') { echo ' '; }
  echo d_output($row['postaladdress2']);
}
if ($row['postalcode'] != '' || $row['townid'] > 0)
{
  require('preload/town.php');
  echo '<br>',d_output($row['postalcode']);
  if (isset($townA[$row['townid']])) { echo ' ',d_output($townA[$row['townid']]); }
}
if ($row['telnumber1'] != '' || $employee_employeeemailA[$employeeid] != '') { echo '<br>'; }
if ($row['telnumber1'] != '') { echo d_output($row['telnumber1']); }
if ($employee_employeeemailA[$employeeid] != '')
{
  if ($row['telnumber1'] != '') { echo ' '; }
  echo d_output($employee_employeeemailA[$employeeid]);
}
echo '<font size=-2><br></font>';
if ($referencenumber != '' || $dn != '') { echo '<br>'; }
if ($referencenumber != '') { echo 'Matricule : ',d_output($referencenumber); }
if ($dn != '')
{
  if ($referencenumber != '') { echo ' &nbsp; '; }
  echo 'DN : ',d_output($dn);
}
if ($jobname != '') { echo '<br>Emplois : ',d_output($jobname); }
echo '<br>Date d\'embauche : ',datefix($hiringdate,'short');
if ($exitdate != '' && $exitdate != '0000-00-00') { echo '<br>Date de sortie : ',datefix($exitdate,'short'); }
echo '<br>Ancienneté : ',$duration_text;
if ($salary_account != '')
{
  require('preload/bank.php');
  echo '<br><span style="font-size: small">';
  if (isset($bankA[$salary_bankid])) { echo d_output($bankA[$salary_bankid]),' '; }
  if ($salary_account_title != '') { echo d_output($salary_account_title); }
  else { echo $employeeA[$employeeid]; }
  echo '<br>',d_output($salary_account),'</span>';
}
echo '</div>';
# contract type? cdi cdd  $employee_employeeemailA

require('preload/collectiveagreement.php');
$query = 'select idtahiti,companyname,infophonenumber,infoaddress1,infoaddress2,infoemail,postaladdress,postalcode,infocity,socialsecuritynumber,collectiveagreementid
from companyinfo where companyinfoid=1';
$query_prm = array();
require('inc/doquery.php');
$row = $query_result[0];
echo '<div class="employer"><b>',d_output($row['companyname']),'</b>';
if ($row['infoaddress1'] != '' || $row['infoaddress2'] != '') { echo '<br>'; }
if ($row['infoaddress1'] != '') { echo d_output($row['infoaddress1']); }
if ($row['infoaddress2'] != '')
{
  if ($row['infoaddress1'] != '') { echo ' '; }
  echo d_output($row['infoaddress2']);
}
if ($row['postaladdress'] != '') { echo '<br>',d_output($row['postaladdress']); }
if ($row['postalcode'] != '' || $row['infocity'] != '') { echo '<br>',d_output($row['postalcode']),' ',d_output($row['infocity']); }
if ($row['infophonenumber'] != '') { echo '<br>',d_output($row['infophonenumber']); }
if ($row['infoemail'] != '') { echo '<br>',d_output($row['infoemail']); }
echo '<br><br>N<sup>o</sup> Tahiti : ',d_output($row['idtahiti']),
'<br>Matricule CPS : ',d_output($row['socialsecuritynumber']);
if ($row['collectiveagreementid'] > 0) { echo '<br>Convention collective : ',d_output($collectiveagreementA[$row['collectiveagreementid']]); }
echo '</div>';

echo '<div class="maincalc">';

### vacationdays
# find vacationdays left from last month
$lastpayslipdate = d_builddate(1,$lastmonth,$lastyear);
$query = 'select vacationdays from payslip where employeeid=? and payslipdate=?';
$query_prm = array($employeeid,$lastpayslipdate);
require('inc/doquery.php');
if ($num_results)
{
  $vacationdays_last = $query_result[0]['vacationdays']+0;
}
else
{
  $vacationdays_last = 0; #
}
$vacationdays = $vacationdays_last + $vacationdays_added - $vacationdays_used;
echo '<table class=report width=100%><tr>';
echo '<td style="font-size: 75%">Congés Payés acquis avant ce mois: ',$vacationdays_last;
echo '<td style="font-size: 75%">Congés Payés acquis ce mois: ',$vacationdays_added;
echo '<td style="font-size: 75%">Congés Payés utilisés ce mois: ',$vacationdays_used;
echo '<td style="font-size: 75%; font-weight: bold">Solde Congés Payés: ',$vacationdays;
echo '</table><br>';
###


echo '<table class=report width=100% style="border-collapse: collapse">
<thead><th><th>Calcul<th>Montant<th colspan=2>Explication</thead>'; # HERE TODO change 'int' to currency bold also remove all td_old
if($hourly_pay == 0)
{
  echo d_tr();
  if ($merge_absence && $_SESSION['ds_customname'] == 'Jurion Protection')
  {
    echo d_td_old("Heures de travail payables",0,2);
    $query = 'select payslip_line_comment_employer from payslip_line_net where payslipid=? and `rank`=10';
    $query .= ' order by `rank`';
    $query_prm = array($payslipid);
    require('inc/doquery.php');
    echo d_td('&nbsp;'.$query_result[0]['payslip_line_comment_employer'],'center');
  }
  else
  {
    echo d_td_old("Salaire de base",0,2),d_td('&nbsp;'.$hourspermonth.' heures par mois','center');
  }
  echo d_td($base_salary, 'int');
  if ($merge_absence) { echo d_td('','',2); }
  else {
  if ($_SESSION['ds_customname'] == 'Pacific Batiment' || $_SESSION['ds_customname'] == 'Espace Paysages'
  || $_SESSION['ds_customname'] == 'Jurion Protection')
  {
    echo d_td(((6*$hoursperday)+0).' heures par semaine','center',2);
  }
  else
  {
    echo d_td(myround($hoursperday,2).' heures par jour','center',2);
  } }
}
### hours
$query = 'select * from payslip_line_net where payslipid=? and `rank`<100';
$query .= ' order by `rank`';
$query_prm = array($payslipid);
require('inc/doquery.php');
for ($i = 0; $i < $num_results; $i++)
{
  if ($hourly_pay && $query_result[$i]['rank'] == 35) { $c = $hourspermonth - (double) $query_result[$i]['payslip_line_comment']; }
  else { $c = $query_result[$i]['payslip_line_comment']; }
  if ($query_result[$i]['rank'] >= 30 && $query_result[$i]['rank'] <= 34)
  {
    if ($c > 1) { $c = $c . ' heures'; }
    elseif ($c != 0) { $c = $c . ' heure'; }
    if ($c != '')
    {
      #add calc
      $temp = d_divide($query_result[$i]['value'],(double)$query_result[$i]['payslip_line_comment']);
      $temp = rtrim($temp, '0');
      $temp = rtrim($temp, '.');
      $c .= ' x ' . $temp;
    }
    if (isset($query_result[$i]['payslip_line_comment_employer'][0])
    && ($query_result[$i]['payslip_line_comment_employer'][0] == 'm'
    || $query_result[$i]['payslip_line_comment_employer'][0] == 'M'))
    {
      $query_result[$i]['payslip_line_name'] = 'Heures majorées';
    }
    if (isset($query_result[$i]['payslip_line_comment_employer'][0])
    && ($query_result[$i]['payslip_line_comment_employer'][0] == 's'
    || $query_result[$i]['payslip_line_comment_employer'][0] == 'S'))
    {
      $query_result[$i]['payslip_line_name'] = 'Heures supplémentaires';
    }
  }
  $c_e = '&nbsp;' . $query_result[$i]['payslip_line_comment_employer'];
  if ($merge_absence && $query_result[$i]['rank'] == 10 && $_SESSION['ds_customname'] == 'Jurion Protection') { $c_e = ''; }
  $value = $query_result[$i]['value'];
  $value_e = $query_result[$i]['value_employer'];
  if ($query_result[$i]['negative'] == 1 && $value > 0)
  {
    $value = '-'.$value;
    if (substr($c,0,1) != '(') { $c = '-'.$c; }
  }
  if ($value != 0 || $value_e !=0)
  {
    if ($hourly_pay && $query_result[$i]['rank'] == 35)
    {
      echo d_tr(),d_td('Heures travaillées','bold');
      echo d_td(ltrim($c,'-'),'center');
      echo d_td($base_salary+$value,'decimal');
      echo d_td($c_e,'center',2);
    }
    else
    {
      echo d_tr(),d_td($query_result[$i]['payslip_line_name']),d_td($c),d_td($value,'decimal'),d_td($c_e,'center',2);
    }
  }
}
###

$hoursworked_display = '';
if ($hours_text != '') { $hoursworked_display = $hours_text; }
else
{
  if ($_SESSION['ds_customname'] == 'Pacific Batiment' || $_SESSION['ds_customname'] == 'Espace Paysages'
  || $_SESSION['ds_customname'] == 'Jurion Protection')
  {
    if (($hoursworked - floor($hoursworked)) == 0.5) { $hoursworked = floor($hoursworked); }
    $hoursworked = round($hoursworked);
  }
  $hoursworked_display = $hoursworked . ' heure';
  if ($hoursworked != 1) { $hoursworked_display .= 's'; }
  $hoursworked_display .= ' rémunérée';
  if ($hoursworked != 1) { $hoursworked_display .= 's'; }
}
echo '<tr class="sum">',d_td("Salaire brut soumis à cotisation",'bold'),d_td($hoursworked_display,'center'),d_td($gross_salary,'int'),d_td('','',2);
echo d_tr(),d_td(),d_td_old('Cotisations Salariales','bold center',2),d_td(),d_td('Cotisations Patronales','bold center'),d_td('Montant','bold center');

### CPS / CST
$value_total = 0; $value_e_total = 0;
$query = 'select * from payslip_line_net where payslipid=? and `rank`>=100 and `rank`<10000 order by `rank`';
$query_prm = array($payslipid);
require('inc/doquery.php');
for ($i = 0; $i < $num_results; $i++)
{
  $c = $query_result[$i]['payslip_line_comment'];
  $c_e = $query_result[$i]['payslip_line_comment_employer'];
  $value = myfix($query_result[$i]['value']);
  $value_total += $query_result[$i]['value'];
  $value_e = myfix($query_result[$i]['value_employer']);
  $value_e_total += $query_result[$i]['value_employer'];
  if ($query_result[$i]['negative'] == 1 && $value > 0)
  {
    $value = '-'.$value;
  }
  if ($value != 0 || $value_e != 0)
  {
    echo d_tr(),d_td_old($query_result[$i]['payslip_line_name']),d_td_old($c),d_td_old($value,1),d_td_old('&nbsp;'.$c_e),d_td_old($value_e,1);
  }
}
###

echo d_tr(),d_td("Total Cotisation",'bold'),d_td($value_total,'decimal'),d_td(),d_td(),d_td($value_e_total,'decimal');

### add/deduct
$query = 'select * from payslip_line_net where payslipid=? and `rank`>=10000 order by `rank`';
$query_prm = array($payslipid);
require('inc/doquery.php');
for ($i = 0; $i < $num_results; $i++)
{
  $c = $query_result[$i]['payslip_line_comment'];
  $value = myfix($query_result[$i]['value']);
  if ($query_result[$i]['negative'] == 1 && $value > 0)
  {
    $value = '-'.$value;
  }
  if ($query_result[$i]['rank'] == 10050)
  {
    if ($value > 0)
    {
      if ($value == 1) { $value .= ' jour'; }
      elseif ($value > 1) { $value .= ' jours'; }
      echo d_tr(),d_td_old($query_result[$i]['payslip_line_name']),d_td_old($value,1),d_td($c,'decimal'),d_td_old('&nbsp;'.$c_e),d_td_old($value_e,1);
    }
  }
  elseif ($value != 0)
  {
    echo d_tr(),d_td_old($query_result[$i]['payslip_line_name']),d_td_old($c),d_td_old($value,1),d_td_old('&nbsp;'.$c_e),d_td_old($value_e,1);
  }
}
###

echo '<tr class="sum">',d_td_old("Salaire net",0,2),d_td(),d_td_old(myfix($net_salary),1,2),d_td(),d_td();
echo '</table>';
echo '<p class="netpay">SALAIRE NET A PAYER : &nbsp; ',myfix($net_salary);
if ($paymenttypeid) #  && $bankaccountid
{
  require('preload/paymenttype.php');
  #require('preload/bankaccount.php');
  echo '<br>Payé par ',$paymenttypeA[$paymenttypeid],'.';
  #$bankA[$bankaccount_bankidA[$bankaccountid]],' ',$bankaccountA[$bankaccountid];
}
echo '</p>';
echo d_output($payslipcomment);
echo '</div>';
echo '</div>';
echo '<div class="logo-tem"><img src="pics/logo.png" height="50"></div>';
echo '</div>';


?>