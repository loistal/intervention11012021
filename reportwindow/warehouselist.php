<?php
#reportwindow\warehouselist



require('preload/warehouse.php');

$orderby = $_POST['orderby']; 

$warehouseid = $_POST['warehouseid']; 
$placementname=$_POST['placementname']; 
$query = 'select placementid,placementname from placement where placementname=?';
$query_prm = array($placementname);
require('inc/doquery.php');
$placementid=$query_result[0]['placementid'];

$placementrank=$_POST['placementrank'];
$creationzone = $_POST['creationzone']; 

$pickingzone = $_POST['pickingzone']; 

$transportzone = $_POST['transportzone']; 

$deletionzone = $_POST['deletionzone']; 


/*echo '<br> warehouseid = ' .$warehouseid;
echo '<br> placementname = ' .$placementname;
echo '<br> placementrank = ' .$placementrank;
echo '<br> creationzone = ' .$creationzone;
echo '<br> pickingzone = ' .$pickingzone;
echo '<br> transportzone = ' .$transportzone;
echo '<br> deletionzone = ' .$deletionzone;
echo '<br> orderby = ' .$orderby;

echo 'entrée de  reportwindow\warehouselist' .$warehouseid;

*/


$query_prm = array();
$query = 'select placementname,warehousename,placementrank,placementid,creationzone,pickingzone,transportzone,deletionzone,placement.deleted';
$query .= ' from placement,warehouse';
$query .= ' where placement.warehouseid=warehouse.warehouseid'; 



if ($warehouseid > 0 ) 
  {
  $query .= ' and warehouse.warehouseid=?'; $query_prm = array($warehouseid);
  }

if ($placementid > 0) 
    {
    echo ' <br>selection sur placementid ' .$placementid;
    
    $query .= ' and placement.placementid=?';array_push($query_prm,$placementid);
    }

if ($placementrank > 0 ) 
    {
    $query .= ' and placementrank=?';array_push($query_prm,$placementrank);
    }


    
if ($creationzone || $pickingzone || $transportzone || $deletionzone)    
    {
    $query .= ' and (creationzone=? or pickingzone=? or transportzone=? or deletionzone=?)';array_push($query_prm,$creationzone,$pickingzone,$transportzone,$deletionzone);
    }

switch ($orderby)
  {
    case "1":
      $query .= ' order by warehousename';
      break;
    case "2":
      $query .= ' order by placementname';
      break;
    default:
      $query .= ' order by warehousename,placementname';;
  }


require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

if ($num_results_main ==0) {echo '<h2>Auncun Emplacement trouvé</h2>';}

echo '<h2>Emplacement </h2>';


if ($warehouseid > 0 ) { echo '<p>Entrepôt: ' .$warehouseA[$warehouseid] ;}

if ($placementid > 0 ) { echo '<p>Emplacement: ' .$placementname;}

if ($placementrank > 0 ) { echo '<p>Rank: ' .$placementrank;}


echo '<table class=report>';
#echo '<p><b><tr><td colspan="3"><td colspan="4" align="center">TYPE de ZONE';
echo '<thead><th>Entrepôts (ID)</th><th>Emplacement</th><th>Rank</th><th>Zone Création</th><th>Zone Picking</th><th>Zone Transport</th><th>Zone Destruction<th>Emplacement Supprimé</th></thead>';


####################################################################################################
for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo d_tr() .'<td>' . $row['warehousename'] . '<td>' . $row['placementname'] . ' (' . $row['placementid'] . ')' . d_td_old($row['placementrank'],1) ;
    if ($row['creationzone']) { echo '<td align=center>&radic;</td>'; }
    else { echo '<td>&nbsp;</td>'; } 
    if ($row['pickingzone']) { echo '<td align=center>&radic;</td>'; }
    else { echo '<td>&nbsp;</td>'; } 
    if ($row['transportzone']) { echo '<td align=center>&radic;</td>'; }
    else { echo '<td>&nbsp;</td>'; } 
    if ($row['deletionzone']) { echo '<td align=center>&radic;</td>'; }
    else { echo '<td>&nbsp;</td>'; } 
    if ($row['deleted']) { echo '<td align=center>&radic;</td>'; }
    else { echo '<td>&nbsp;</td>'; } 
  }
  
  echo '<tr><td align=right><b>TOTAL' .d_td_old($i,1) .'<td colspan="5">';
echo '</table>';

?>