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

$title = 'Livre de Paie '.$month.' / '.$year;
showtitle_new($title);
echo d_table('report');

echo '<p>Numéro Employeur : '.$ssn;
echo '<p>No Tahiti : '.d_output($idtahiti);
echo '<p>Periode : ' . d_trad('month2_'.$month) . ' ' . $year;

$query = 'select payslipid,employeename from payslip,employee
where payslip.employeeid=employee.employeeid
and status=1 and payslipdate=?
order by employeename';
$query_prm = array(d_builddate(1,$month,$year));
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

$rankA = array(); $rank_nameA = array();
for ($i=0; $i < $num_results_main; $i++)
{
  $payslipid = $main_result[$i]['payslipid'];

  $query = 'select * from payslip where payslipid=?';
  $query_prm = array($payslipid);
  require('inc/doquery.php');
  $base_salary[$i] = $query_result[0]['base_salary'];
  $employeeid[$i] = $query_result[0]['employeeid'];
  $net_salary[$i] = $query_result[0]['net_salary'];
  $hoursworked[$i] = $query_result[0]['hoursworked'];
  if ($_SESSION['ds_customname'] == 'Pacific Batiment' || $_SESSION['ds_customname'] == 'Espace Paysages'
  || $_SESSION['ds_customname'] == 'Jurion Protection')
  {
    if (($hoursworked[$i] - floor($hoursworked[$i])) == 0.5) { $hoursworked[$i] = floor($hoursworked[$i]); }
    $hoursworked[$i] = round($hoursworked[$i]);
  }
  $vacationdays_added[$i] = $query_result[0]['vacationdays_added'];
  $vacationdays_used[$i] = $query_result[0]['vacationdays_used'];
  
  $cot_pat[$i] = 0; $heures_sup[$i] = 0;
  $query = 'select `rank`,value,negative,payslip_line_name,payslip_line_comment,value_employer from payslip_line_net where payslipid=?';
  $query_prm = array($payslipid);
  require('inc/doquery.php');
  for ($y=0; $y < $num_results; $y++)
  {
    if ($query_result[$y]['rank'] == '10050') { $line_net[$i][$query_result[$y]['rank']] = $query_result[$y]['payslip_line_comment']; }
    else { $line_net[$i][$query_result[$y]['rank']] = $query_result[$y]['value']; }
    if ($query_result[$y]['negative']) { $line_net[$i][$query_result[$y]['rank']] = d_subtract(0,$query_result[$y]['value']); }
    
    if (array_search($query_result[$y]['rank'], $rankA) === FALSE)
    {
      $rankA[] = $query_result[$y]['rank'];
      $rank_nameA[$query_result[$y]['rank']] = $query_result[$y]['payslip_line_name'];
    }
    
    $cot_pat[$i] += $query_result[$y]['value_employer'];
    if ($query_result[$y]['rank'] == 25) { $heures_comp[$i] = strtok($query_result[$y]['payslip_line_comment'], ' heure'); }
    elseif ($query_result[$y]['rank'] >= 30 && $query_result[$y]['rank'] <= 34)
    {
      $heures_sup[$i] += d_add($query_result[$y]['payslip_line_comment'],0);
    }
    elseif ($query_result[$y]['rank'] == 35) { $absence_hours[$i] = strtok($query_result[$y]['payslip_line_comment'], ' heure'); }
  }
}

echo d_table('report'),'<thead><th>Rubrique';
for ($i=0; $i < $num_results_main; $i++)
{
  echo '<th>',d_output($employeeA[$employeeid[$i]]);
}
echo '<th>Total</thead>';

$linetotal = 0;
echo d_tr(),d_td('Salaire de base');
for ($i=0; $i < $num_results_main; $i++)
{
  echo d_td($base_salary[$i],'currency');
  $linetotal += $base_salary[$i];
  $rowtotal[$i] = $base_salary[$i];
  $subtotal[$i] = 0;
}
echo d_td($linetotal,'currency');

sort($rankA);
foreach ($rankA as $rank)
{
  if ($rank == 100)
  {
    $linetotal = 0;
    echo d_tr(1),d_td('Salaire brut soumis à cotisation');
    for ($i=0; $i < $num_results_main; $i++)
    {
      echo d_td($rowtotal[$i],'currency');
      $linetotal += $rowtotal[$i];
      $brut[$i] = $rowtotal[$i];
    }
    echo d_td($linetotal,'currency');
  }
  elseif ($rank == 10050)
  {
    $linetotal = 0;
    echo d_tr(1),d_td('Total Cotisation');
    for ($i=0; $i < $num_results_main; $i++)
    {
      echo d_td($subtotal[$i],'currency');
      $linetotal += $subtotal[$i];
    }
    echo d_td($linetotal,'currency');
  }
  $linetotal = 0; $showline = 0;
  for ($i=0; $i < $num_results_main; $i++)
  {
    if (isset($line_net[$i][$rank])) { $linetotal += $line_net[$i][$rank]; }
    if (!$showline && isset($line_net[$i][$rank]) && $line_net[$i][$rank] != 0) { $showline = 1; }
  }
  if ($showline)
  {
    echo d_tr(),d_td($rank_nameA[$rank]);
    for ($i=0; $i < $num_results_main; $i++)
    {
      if (isset($line_net[$i][$rank]))
      {
        echo d_td($line_net[$i][$rank],'currency');
        $rowtotal[$i] += $line_net[$i][$rank];
        if ($rank >=100 && $rank < 1000)
        {
          $subtotal[$i] += $line_net[$i][$rank];
        }
      }
      else { echo d_td(); }
    }
    echo d_td($linetotal,'currency');
  }
}

$grandtotal = 0;
echo d_tr(1),d_td('Salaire net');
for ($i=0; $i < $num_results_main; $i++)
{
  echo d_td($rowtotal[$i],'currency');
  $grandtotal += $rowtotal[$i];
}
echo d_td($grandtotal,'currency');

echo d_tr(),d_td('&nbsp;','',1000);

$total = 0;
echo d_tr(),d_td('Brut');
for ($i=0; $i < $num_results_main; $i++)
{
  echo d_td($brut[$i],'currency');
  $total += $brut[$i];
}
echo d_td($total,'currency');

$total = 0;
echo d_tr(),d_td('Cotisations salariales');
for ($i=0; $i < $num_results_main; $i++)
{
  $subtotal[$i] = d_abs($subtotal[$i]);
  echo d_td($subtotal[$i],'currency');
  $total += $subtotal[$i];
}
echo d_td($total,'currency');

$total = 0;
echo d_tr(),d_td('Cotisations patronales');
for ($i=0; $i < $num_results_main; $i++)
{
  echo d_td($cot_pat[$i],'currency');
  $total += $cot_pat[$i];
}
echo d_td($total,'currency');

$total = 0;
echo d_tr(),d_td('Net à payer');
for ($i=0; $i < $num_results_main; $i++)
{
  echo d_td($net_salary[$i],'currency');
  $total += $net_salary[$i];
}
echo d_td($total,'currency');

$total = 0;
echo d_tr(),d_td('Heures rémunérées');
for ($i=0; $i < $num_results_main; $i++)
{
  echo d_td($hoursworked[$i],'decimal');
  $total += $hoursworked[$i];
}
echo d_td($total,'decimal');

$total = 0;
echo d_tr(),d_td('Heures complémentaires');
for ($i=0; $i < $num_results_main; $i++)
{
  echo d_td($heures_comp[$i],'decimal');
  $total += $heures_comp[$i];
}
echo d_td($total,'decimal');

$total = 0;
echo d_tr(),d_td('Heures supplémentaires/majorées');
for ($i=0; $i < $num_results_main; $i++)
{
  echo d_td($heures_sup[$i],'decimal');
  $total += $heures_sup[$i];
}
echo d_td($total,'decimal');

$total = 0;
echo d_tr(),d_td('Absence');
for ($i=0; $i < $num_results_main; $i++)
{
  echo d_td($absence_hours[$i],'decimal');
  $total += $absence_hours[$i];
}
echo d_td($total,'decimal');

$total = 0;
echo d_tr(),d_td('CP Aquis');
for ($i=0; $i < $num_results_main; $i++)
{
  echo d_td($vacationdays_added[$i],'decimal');
  $total += $vacationdays_added[$i];
}
echo d_td($total,'decimal');

$total = 0;
echo d_tr(),d_td('CP Pris');
for ($i=0; $i < $num_results_main; $i++)
{
  echo d_td($vacationdays_used[$i],'decimal');
  $total += $vacationdays_used[$i];
}
echo d_td($total,'decimal');

$total = 0;
echo d_tr(),d_td('Coût total');
for ($i=0; $i < $num_results_main; $i++)
{
  echo d_td($brut[$i]+$cot_pat[$i],'decimal');
  $total += $brut[$i]+$cot_pat[$i];
}
echo d_td($total,'decimal');

$total = 0;
echo d_tr(1),d_td('Nombre de salariés');
for ($i=0; $i < $num_results_main; $i++)
{
  echo d_td();
}
echo d_td($num_results_main,'decimal');

echo d_table_end();

?>