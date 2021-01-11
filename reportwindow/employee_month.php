<?php

function showtimeworked ($timeworked) # copy from somewhere
{
  $showtimeworked = $timeworked%60; if ($showtimeworked < 10) { $showtimeworked = '0'.$showtimeworked; }
  $showtimeworked = floor($timeworked/60).':'.$showtimeworked;
  if ($showtimeworked == '0:00') { $showtimeworked = ''; }
  return $showtimeworked;
}

$PA['year'] = 'uint';
$PA['month'] = 'uint';
require('inc/readpost.php');

require('preload/employee.php');

$total = 0;
$tnw1 = $tnw2 = $tnw3 = $tnw4 = $tnw5 = $tm_a = 0;

$query = 'select employeeid,minutes_worked,minutes_to_pay,employee_monthid,nonworked1,nonworked2,nonworked3,nonworked4,nonworked5,meal_allowance
from employee_month
where year=? and month=?';
$query_prm = array($year,$month);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $employeeid = $query_result[$i]['employeeid'];
  $minutes_worked[$employeeid] = $query_result[$i]['minutes_worked'];
  $minutes_to_pay[$employeeid] = $query_result[$i]['minutes_to_pay'];
  $employee_monthid[$employeeid] = $query_result[$i]['employee_monthid'];
  $nonworked1[$employeeid] = $query_result[$i]['nonworked1']; $tnw1 += $nonworked1[$employeeid];
  $nonworked2[$employeeid] = $query_result[$i]['nonworked2']; $tnw2 += $nonworked2[$employeeid];
  $nonworked3[$employeeid] = $query_result[$i]['nonworked3']; $tnw3 += $nonworked3[$employeeid];
  $nonworked4[$employeeid] = $query_result[$i]['nonworked4']; $tnw4 += $nonworked4[$employeeid];
  $nonworked5[$employeeid] = $query_result[$i]['nonworked5']; $tnw5 += $nonworked5[$employeeid];
  $meal_allowance[$employeeid] = $query_result[$i]['meal_allowance']; $tm_a += $meal_allowance[$employeeid];
  if ($meal_allowance[$employeeid] == 0) { $meal_allowance[$employeeid] = ''; }
}

$query = 'select * from employee_month_seq where year(sequencedate)=? and month(sequencedate)=? order by employeeid';
$query_prm = array($year,$month);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $employeeid = $query_result[$i]['employeeid'];
  if ($i == 0 || $employeeid != $query_result[($i-1)]['employeeid']) { $sequencecounter = 0; }
  $text = showtimeworked($query_result[$i]['begin']) . ' à ' . showtimeworked($query_result[$i]['end']);
  $sequenceA[$employeeid][$sequencecounter] = '['.datefix($query_result[$i]['sequencedate'],'short').' '.$text.']';
  $sequencecounter++;
}

$title = 'Tableau Sommaire '.$month.' / '.$year;
showtitle($title);
echo '<h2>',$title,'</h2>';

echo d_table('report');
echo '<thead><th>Employé(e)<th>Heures travaillés<th>Congés Payé<th>Récup<th>Mal/Acc/Mat<th>Divers<th>Ajouté<th>Panier';
echo '<th>Maj 15%'; # stupid hardcode TODO dynamic
echo '<th>Maj 25%';
echo '<th>Maj 50%';
echo '<th>Maj 100%';
echo '<th>Sup 25%';
echo '<th>Sup 50%';
echo '<th>Sup 75%';
echo '<th>Sup 100%';
echo '<th>Sup 200%';
echo '<th>Sequences';
echo '</thead>';
foreach ($employeeA as $employeeid => $employeename)
{
  if ($employee_deletedA[$employeeid] == 0)
  {
    echo d_tr();
    echo d_td($employeename);
    if (isset($employee_monthid[$employeeid]))
    {
      $query = 'select type,rate,minutes from employee_month_minutes where employee_monthid=?';
      $query_prm = array($employee_monthid[$employeeid]);
      require('inc/doquery.php');
      unset ($em);
      for ($y=0; $y < $num_results; $y++)
      {
        $em[$query_result[$y]['type']][$query_result[$y]['rate']] = $query_result[$y]['minutes'];
        if (!isset($t_em[$query_result[$y]['type']][$query_result[$y]['rate']])) { $t_em[$query_result[$y]['type']][$query_result[$y]['rate']] = 0; }
        $t_em[$query_result[$y]['type']][$query_result[$y]['rate']] += $query_result[$y]['minutes'];
      }
      echo d_td(showtimeworked($minutes_worked[$employeeid]),'right');
      $total += $minutes_worked[$employeeid];
      echo d_td(showtimeworked($nonworked1[$employeeid]),'right');
      echo d_td(showtimeworked($nonworked2[$employeeid]),'right');
      echo d_td(showtimeworked($nonworked3[$employeeid]),'right');
      echo d_td(showtimeworked($nonworked4[$employeeid]),'right');
      echo d_td(showtimeworked($nonworked5[$employeeid]),'right');
      echo d_td($meal_allowance[$employeeid],'right');
      if (isset($em[0][115])) { echo d_td(showtimeworked($em[0][115]),'right'); } else { echo d_td(); }
      if (isset($em[0][125])) { echo d_td(showtimeworked($em[0][125]),'right'); } else { echo d_td(); }
      if (isset($em[0][150])) { echo d_td(showtimeworked($em[0][150]),'right'); } else { echo d_td(); }
      if (isset($em[0][200])) { echo d_td(showtimeworked($em[0][200]),'right'); } else { echo d_td(); }
      if (isset($em[1][125])) { echo d_td(showtimeworked($em[1][125]),'right'); } else { echo d_td(); }
      if (isset($em[1][150])) { echo d_td(showtimeworked($em[1][150]),'right'); } else { echo d_td(); }
      if (isset($em[1][175])) { echo d_td(showtimeworked($em[1][175]),'right'); } else { echo d_td(); }
      if (isset($em[1][200])) { echo d_td(showtimeworked($em[1][200]),'right'); } else { echo d_td(); }
      if (isset($em[1][300])) { echo d_td(showtimeworked($em[1][300]),'right'); } else { echo d_td(); }
      $text = '';
      if (isset($sequenceA[$employeeid]))
      {
        foreach ($sequenceA[$employeeid] as $addtext)
        {
          $text .= $addtext . ' ';
        }
      }
      echo d_td($text);
    }
    else
    {
      echo d_td('','','17');
    }
  }
}

echo '<thead><th>Employé(e)<th>Heures travaillés<th>Congés Payé<th>Récup<th>Mal/Acc/Mat<th>Divers<th>Ajouté<th>Panier';
echo '<th>Maj 15%';
echo '<th>Maj 25%';
echo '<th>Maj 50%';
echo '<th>Maj 100%';
echo '<th>Sup 25%';
echo '<th>Sup 50%';
echo '<th>Sup 75%';
echo '<th>Sup 100%';
echo '<th>Sup 200%';
echo '<th>Sequences';
echo '</thead>';

echo d_tr(1);
echo d_td('Totaux');
echo d_td(showtimeworked($total),'right');
echo d_td(showtimeworked($tnw1),'right');
echo d_td(showtimeworked($tnw2),'right');
echo d_td(showtimeworked($tnw3),'right');
echo d_td(showtimeworked($tnw4),'right');
echo d_td(showtimeworked($tnw5),'right');
echo d_td($tm_a,'right');
if (isset($t_em[0][115])) { echo d_td(showtimeworked($t_em[0][115]),'right'); } else { echo d_td(); }
if (isset($t_em[0][125])) { echo d_td(showtimeworked($t_em[0][125]),'right'); } else { echo d_td(); }
if (isset($t_em[0][150])) { echo d_td(showtimeworked($t_em[0][150]),'right'); } else { echo d_td(); }
if (isset($t_em[0][200])) { echo d_td(showtimeworked($t_em[0][200]),'right'); } else { echo d_td(); }
if (isset($t_em[1][125])) { echo d_td(showtimeworked($t_em[1][125]),'right'); } else { echo d_td(); }
if (isset($t_em[1][150])) { echo d_td(showtimeworked($t_em[1][150]),'right'); } else { echo d_td(); }
if (isset($t_em[1][175])) { echo d_td(showtimeworked($t_em[1][175]),'right'); } else { echo d_td(); }
if (isset($t_em[1][200])) { echo d_td(showtimeworked($t_em[1][200]),'right'); } else { echo d_td(); }
if (isset($t_em[1][300])) { echo d_td(showtimeworked($t_em[1][300]),'right'); } else { echo d_td(); }
echo d_td();
echo d_table_end();
?>