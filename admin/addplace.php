<?php
#warehouse\creatpalletbarcode
$placementname=$_POST['placementname'];

if ($placementname  != '')
{
  $placementname = $_POST['placementname'];
  $placementrank = $_POST['placementrank'];
  $warehouseid = $_POST['warehouseid'];
  
  $creationzone = $_POST['creationzone']+0;
  $pickingzone = $_POST['pickingzone']+0;
  $transportzone = $_POST['transportzone']+0;
  $deletionzone = $_POST['deletionzone']+0;
  
  $mapid = $_POST['mapid']+0;
  $map_start_x = $_POST['map_start_x']+0;
  $map_start_y = $_POST['map_start_y']+0;
  $map_stop_x = $_POST['map_stop_x']+0;
  $map_stop_y = $_POST['map_stop_y']+0;

  
  $query = 'insert into placement (placementname,warehouseid,placementrank,userid,counteddate,countedtime,creationzone,pickingzone,transportzone,deletionzone,mapid,map_start_x,map_start_y,map_stop_x,map_stop_y) 
            values (?,?,?,?,curdate(),curtime(),?,?,?,?,?,?,?,?,?)';
  $query_prm = array($placementname,$warehouseid,$placementrank,$_SESSION['ds_userid'],$creationzone,$pickingzone,$transportzone,$deletionzone,$mapid,$map_start_x,$map_start_y,$map_stop_x,$map_stop_y);
  require('inc/doquery.php');
  echo '<p>Emplacement ' . $placementname . ' ajouté.</p>';
  $placementrank = 0 ;
  $creationzone = 0 ;
  $pickingzone = 0 ;
  $transportzone = 0 ;
  $deletionzone = 0 ;
  $deleted = 0 ;
  $mapid = 0 ;
  $map_start_x = 0 ;
  $map_start_y = 0 ;
  $map_stop_x = 0 ;
  $map_stop_y = 0 ;

}

echo '<h2>Création Emplacement</h2>
  <form method="post" action="admin.php">
  <table>

  <tr><td>Nom:</td><td><input autofocus type="text" STYLE="text-align:left" name="placementname" size=30></td></tr>
  <tr><td>Rank:</td><td><input  type="num" STYLE="text-align:right" name="placementrank" size=5>

  <tr><td>Entrepôt:</td>
  <td><select name="warehouseid">';

  $query = 'select warehouseid,warehousename from warehouse order by warehousename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
    {
    $row = $query_result[$i];
    echo '<option value="' . $row['warehouseid'] . '">' . $row['warehousename'] . '</option>';
    }

  echo '<tr><td rowspan="4">Type de zone:<td>';

  echo '<input type="checkbox" name="creationzone" value="1"';
  if ($row['creationzone'] == 1) { echo ' checked'; }
  echo '>';
  echo 'Création';

  echo '<tr><td><input type="checkbox" name="pickingzone" value="1"';
  if ($row['pickingzone'] == 1) { echo ' checked'; }
  echo '>';
  echo 'Picking';

  echo '<tr><td><input type="checkbox" name="transportzone" value="1"';
  if ($row['transportzone'] == 1) { echo ' checked'; }
  echo '>';
  echo 'Transport';

  echo '<tr><td><input type="checkbox" name="deletionzone" value="1"';
  if ($row['deletionzone'] == 1) { echo ' checked'; }
  echo '>';
  echo 'Destruction';  
  
  echo '<br>';
  echo '<br><b>Coordonnées du plan';
  echo '<br><tr><tr><td>Numéro : <td><input type="number" name="mapid" value="' . $mapid . '" size=5>';
  echo '<tr><tr><td> Début horizontale X: <td><input type="number" name="map_start_x" value="' . $map_start_x . '" size=5>';
  echo '<tr><tr><td> Début verticale Y: <td><input type="number" name="map_start_y" value="' . $map_start_y . '" size=5>';
  echo '<tr><tr><td> Fin horizontale X: <td><input type="number" name="map_stop_x" value="' . $map_stop_x . '" size=5>';
  echo '<tr><tr><td> Fin verticale Y: <td><input type="number" name="map_stop_y" value="' . $map_stop_y . '" size=5>';

  echo '<tr><td colspan="2" align="center">
  <input type=hidden name="adminmenu" value="' . $adminmenu . '">
  <input type="submit" value="Valider">';

  echo '</table></form>';  
?>

