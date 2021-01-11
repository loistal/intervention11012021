<?php

echo '<style>
      tr.temp_linecolor1 td {
        background-color: #ffb6c1;
      }
      tr.temp_linecolor2 td {
        background-color: #ff9900;
      }
      tr.temp_linecolor3 td {
        background-color: #add8e6;
      }
      </style>';

$month = (int) $_POST['month'];
$year = (int) $_POST['year'];
$orderby = (int) $_POST['orderby'];

echo '<TITLE>Rapport achats ' . $month . '/' . $year . '</TITLE>';
echo '</HEAD><BODY text=#000000 vLink=#003399 aLink=#ff9933 link=#003399 bgColor=#ffffff>';
echo '<h1>Rapport achats ' . $month . '/' . $year . '</h1>';

$query = 'select transitcost,noinv,shipmentcomment,shipmentid,shipmentcomment2,numberofcontainers20,numberofcontainers20cold
,numberofcontainers40,numberofcontainers40cold,vesselname,arrivaldate,customscode,shipmentstatus,numberofcontainers20dooropen
from shipment,vessel
where shipment.vesselid=vessel.vesselid
and EXTRACT(YEAR FROM arrivaldate)=' . $year . ' and EXTRACT(MONTH FROM arrivaldate)=' . $month;
if ($orderby == 1) { $query .= ' order by arrivaldate,shipmentid'; }
elseif ($orderby == 2) { $query .= ' order by vesselname,shipmentid'; }
else { $query .= ' order by shipmentid'; }
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

echo '<table class="report">';
echo '<tr><td><b>Dossier</b></td><td><b>20\'D</b></td><td><b>20\'R</b></td><td><b>40\'D</b></td><td><b>40\'R</b></td><td><b>20\'DO<td><b>Description</b></td><td><b>No Fact<td><b>Navire</b></td><td><b>ETA</b></td><td><b>Déclar.</b></td><td><b>Status</b></td><td><b>Transport<td><b>Infos</b></td></tr>'; #<td><b>Valeur</b></td>
for ($i=0; $i < $num_results_main; $i++)
{
  $row = $main_result[$i];
  
  #############
  $temp_linecolor = 3;
  
  if ($row['numberofcontainers20cold'] > 0 || $row['numberofcontainers40cold'] > 0)
  { 
    $temp_linecolor = 1;
  }
  ### new check for supplerid, hardcode for WC atm
  $query = 'select supplierid from product,purchase where purchase.productid=product.productid and shipmentid=? and supplierid=4126';
  $query_prm = array($row['shipmentid']);
  require('inc/doquery.php');
  if ($num_results)
  { 
    $temp_linecolor = 2;
  }
  #############
  $query = 'select sum(purchaseprice) as value from purchase where shipmentid=?';
  $query_prm = array($row['shipmentid']);
  require('inc/doquery.php');
  $value = $query_result[0]['value'];
  #############
  
  $c20d = $row['numberofcontainers20']; if ($c20d == "" || $c20d == 0) { $c20d = '&nbsp;'; }
  $c20r = $row['numberofcontainers20cold']; if ($c20r == "" || $c20r == 0) { $c20r = '&nbsp;'; }
  $c40d = $row['numberofcontainers40']; if ($c40d == "" || $c40d == 0) { $c40d = '&nbsp;'; }
  $c40r = $row['numberofcontainers40cold']; if ($c40r == "" || $c40r == 0) { $c40r = '&nbsp;'; }
  $c20do = $row['numberofcontainers20dooropen']; if ($c20do == "" || $c20do == 0) { $c20do = '&nbsp;'; }
  $conts = $row['shipmentcomment']; if ($conts == "") { $conts = '&nbsp;'; }
  echo '<tr class="temp_linecolor'.$temp_linecolor.'"><td align=right>' . $row['shipmentid'] . '</td>
  <td align=right>' . $c20d . '</td><td align=right>' . $c20r . '</td><td align=right>' . $c40d . '</td>
  <td align=right>' . $c40r . '</td><td align=right>' . $c20do;
  echo '<td class="breakme">' . $row['shipmentcomment2'] . '&nbsp;</td><td class="breakme">' . d_output($row['noinv']) . '</td>
  <td class="breakme">' . $row['vesselname'] . '</td><td align=right>' . datefix2($row['arrivaldate']) . '</td>
  <td align=right>&nbsp;' . $row['customscode'] . '</td><td align=right>' . $row['shipmentstatus'] . '</td>
  <td align=right>' . myfix($row['transitcost']) . '</td><td class="breakme">' . $conts . '</td></tr>';
  # <td align=right>' . myfix($value) . '</td>
}
echo '</table>';
?>