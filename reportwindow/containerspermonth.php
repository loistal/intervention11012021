<?php
$year = $_POST['year'];

echo '<TITLE>Containers/mois ' . $year . '</TITLE>';
echo '</HEAD><BODY text=#000000 vLink=#003399 aLink=#ff9933 link=#003399 bgColor=#ffffff>';
echo '<h1>Containers/mois ' . $year . '</h1>';

$query = 'select sum(numberofcontainers20) as c20,sum(numberofcontainers20cold) as c20c
,sum(numberofcontainers40) as c40,sum(numberofcontainers40cold) as c40c,
sum(numberofcontainers20dooropen) as c20do
,month(arrivaldate) as month from shipment where year(arrivaldate)=' . $year . ' group by month order by month';
$query_prm = array();
require('inc/doquery.php');

$tc20 = 0; $tc20c = 0; $tc40 = 0; $tc40c = 0; $tc20do = 0; $total = 0;
for ($y=0; $y < $num_results; $y++)
{
  $row = $query_result[$y];
  $c20[$y] = $row['c20'];
  $tc20 = $tc20 + $row['c20'];
  $tm[$y] = $row['c20'];
  $c20c[$y] = $row['c20c'];
  $tc20c = $tc20c + $row['c20c'];
  $tm[$y] = $tm[$y] + $row['c20c'];
  $c40[$y] = $row['c40'];
  $tc40 = $tc40 + $row['c40'];
  $tm[$y] = $tm[$y] + $row['c40'];
  $c40c[$y] = $row['c40c'];
  $tc40c = $tc40c + $row['c40c'];
  $tm[$y] = $tm[$y] + $row['c40c'];
  
  $c20do[$y] = $row['c20do'];
  $tc20do = $tc20do + $row['c20do'];
  $tm[$y] = $tm[$y] + $row['c20do'];
  
  $total = $total + $tm[$y];
}

echo '<table class="report"><tr><td><b>Container</b></td><td><b>Jan</b></td><td><b>Fev</b></td><td><b>Mars</b></td><td><b>Avril</b></td>
<td><b>Mai</b></td><td><b>Juin</b></td><td><b>Juil</b></td><td><b>Aout</b></td><td><b>Sept</b></td><td><b>Oct</b></td><td><b>Nov</b></td>
<td><b>Dec</b></td><td><b>Total</b></td></tr>';

echo '<tr><td>20\' Dry</td>';
for ($i=0; $i < 12; $i++)
{
  if ($c20[$i] == "" || $c20[$i] == 0) { $c20[$i] = '&nbsp;'; }
  echo '<td align=right>' . $c20[$i] . '</td>';
}
echo '<td align=right>' . $tc20 . '</td>';
echo '</tr>';

echo '<tr><td>20\' Reefer</td>';
for ($i=0; $i < 12; $i++)
{
  if ($c20c[$i] == "" || $c20c[$i] == 0) { $c20c[$i] = '&nbsp;'; }
  echo '<td align=right>' . $c20c[$i] . '</td>';
}
echo '<td align=right>' . $tc20c . '</td>';
echo '</tr>';

echo '<tr><td>20\' Door Open</td>';
for ($i=0; $i < 12; $i++)
{
  if ($c20do[$i] == "" || $c20do[$i] == 0) { $c20do[$i] = '&nbsp;'; }
  echo '<td align=right>' . $c20do[$i] . '</td>';
}
echo '<td align=right>' . $tc20do . '</td>';
echo '</tr>';

echo '<tr><td>40\' Dry</td>';
for ($i=0; $i < 12; $i++)
{
  if ($c40[$i] == "" || $c40[$i] == 0) { $c40[$i] = '&nbsp;'; }
  echo '<td align=right>' . $c40[$i] . '</td>';
}
echo '<td align=right>' . $tc40 . '</td>';
echo '</tr>';

echo '<tr><td>40\' Reefer</td>';
for ($i=0; $i < 12; $i++)
{
  if ($c40c[$i] == "" || $c40c[$i] == 0) { $c40c[$i] = '&nbsp;'; }
  echo '<td align=right>' . $c40c[$i] . '</td>';
}
echo '<td align=right>' . $tc40c . '</td>';
echo '</tr>';

echo '<tr><td><b>Total</b></td>';
for ($i=0; $i < 12; $i++)
{
 if ($tm[$i] == "") { $tm[$i] = 0; }
  echo '<td align=right>' . $tm[$i] . '</td>';
}
echo '<td align=right><b>' . $total . '</b></td>';
echo '</tr>';
echo '</table>';
?>