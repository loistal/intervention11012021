<?php
$month = $_POST['month'];
$year = $_POST['year'];

if (isset($_POST['vesselid']) && $_POST['vesselid'] > 0)
{
$query = 'select vesselname from vessel where vesselid=' . $_POST['vesselid'];
$query_prm = array();
require('inc/doquery.php');
$row = $query_result[0];
}
else { $row['vesselname'] = ''; }

echo '<TITLE>Chargements mensuel ' . $row['vesselname'] . ' ' . $year . '</TITLE>';
echo '</HEAD><BODY text=#000000 vLink=#003399 aLink=#ff9933 link=#003399 bgColor=#ffffff>';
echo '<h1>Chargements mensuel ' . $row['vesselname'] . ' ' . $year . '</h1>';

$query = 'select arrivaldate,shipmentcomment,numberofcontainers20,numberofcontainers20cold,numberofcontainers40
,numberofcontainers40cold,unloadingcost from shipment
where ';
if (isset($_POST['vesselid']) && $_POST['vesselid'] > 0)
{
 $query .= 'vesselid=' . $_POST['vesselid'] . ' and ';
}
$query .= ' year(arrivaldate)=' . $year . ' order by arrivaldate';
$query_prm = array();
require('inc/doquery.php');

echo '<table class="report">';
echo '<tr><td><b>Date arrivage</b></td><td><b>Containers</b></td><td><b>Type</b></td><td><b>Dry</b></td><td><b>Frais débarquement</b></td></tr>';
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  $c20 = $row['numberofcontainers20'] + $row['numberofcontainers20cold'];
  $c40 = $row['numberofcontainers40'] + $row['numberofcontainers40cold'];
  $type = '&nbsp;';
  if ($c20) { $type = '20'; }
  if ($c40) { $type = '40'; }
  if ($c20 && $c40) { $type = '20+40'; }
  $type2 = '&nbsp;'; $cold = 0; $dry = 0;
  if ($row['numberofcontainers20cold'] || $row['numberofcontainers40cold']) { $type2 = 'Cold'; $cold = 1; }
  if ($row['numberofcontainers20'] || $row['numberofcontainers40']) { $type2 = 'Dry'; $dry = 1; }
  if ($cold && $dry) { $type2 = 'Both'; }
  echo '<tr><td align=right>' . datefix2($row['arrivaldate']) . '</td><td align=right>' . $row['shipmentcomment'] . '</td><td align=right>' . $type . '</td><td align=right>' . $type2 . '</td><td align=right>' . round($row['unloadingcost']) . '</td></tr>';
}
echo '</table>';
?>