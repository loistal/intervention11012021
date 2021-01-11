<?php

require('preload/warehouse.php');

echo '<h2>Emplacement </h2>';

$query_prm = array();
$query = 'select placementname,warehousename,placementrank,placementid,creationzone,pickingzone,transportzone,deletionzone,placement.deleted';
$query .= ' from placement,warehouse';
$query .= ' where placement.warehouseid=warehouse.warehouseid'; 
$query .= ' order by deleted,warehousename,placementname,placementrank';
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

if ($num_results_main ==0) {echo '<p class=alert>Auncun Emplacement trouvé</p>';}

echo '<table class=report>';
echo '<thead><th>Entrepôts (ID)</th><th>Emplacement</th><th>Rank<th>Zone Création<th>Zone Picking<th>Zone Transport<th>Zone Destruction<th>Emplacement Supprimé</thead>';

for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo d_tr() .'<td>' . $row['warehousename'] . '<td>' . $row['placementname'] . d_td_old($row['placementrank'],1) ;
    if ($row['creationzone'])
    { 
      echo '<td align=center>&radic;</td>';
    }
    else
    { 
      echo '<td>&nbsp;</td>'; 
    } 
    if ($row['pickingzone']) 
    {
      echo '<td align=center>&radic;</td>'; 
    }
    else 
    { 
      echo '<td>&nbsp;</td>'; 
    } 
    if ($row['transportzone'])
    {
      echo '<td align=center>&radic;</td>'; 
    }
    else 
    { 
      echo '<td>&nbsp;</td>';
    } 
    if ($row['deletionzone'])
    { 
      echo '<td align=center>&radic;</td>'; 
    }
    else
    { 
      echo '<td>&nbsp;</td>'; 
    } 
    if ($row['deleted']) 
    { 
      echo '<td align=center>&radic;</td>'; 
    }
    else
    { 
      echo '<td>&nbsp;</td>';
    } 
  }
echo '<tr><td align=right><b>TOTAL' .d_td_old($i,1) .'<td colspan="6">';
echo '</table>';
?>