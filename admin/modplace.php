<?php

$placementname = $_POST['placementname'];
$placementid = $_POST['placementid']+0;

echo '<h2>Modifier EMPLACEMENT:</h2>';

if ($_POST['saveme'] == 1)
{
  $placementid = $_POST['placementid'];
  $placementname = $_POST['placementname'];
  $warehouseid = $_POST['warehouseid'];
  $placementrank = (int) $_POST['placementrank'];
  $creationzone = $_POST['creationzone'];
  $pickingzone = $_POST['pickingzone'];
  $transportzone = $_POST['transportzone'];
  $deletionzone = $_POST['deletionzone'];
  $deleted = $_POST['deleted'];
  $mapid = $_POST['mapid'];
  $map_start_x = $_POST['map_start_x'];
  $map_start_y = $_POST['map_start_y'];
  $map_stop_x = $_POST['map_stop_x'];
  $map_stop_y = $_POST['map_stop_y'];

  $query = 'update placement set placementrank="' . $placementrank . '",placementname="' . $placementname . '",warehouseid="' . $warehouseid . '",
          creationzone="' . $creationzone . '",pickingzone="' . $pickingzone . '",transportzone="' . $transportzone . '",deletionzone="' . $deletionzone . '",deleted="' . $deleted . '"
          ,mapid="' . $mapid . '",map_start_x="' . $map_start_x . '",map_start_y="' . $map_start_y  . '" ,map_stop_x="' . $map_stop_x . '",map_stop_y="' . $map_stop_y  . '" 
          where placementid="' . $placementid . '"';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<p> Emplacement ' . $placementname . ' modifié.</p>';
  $placementid = 0;
  $_POST['placementname'] = '';
  $_POST['placementrank'] = 0 ;
  $_POST['creationzone'] = 0 ;
  $_POST['pickingzone'] = 0 ;
  $_POST['transportzone'] = 0 ;
  $_POST['deletionzone'] = 0 ;
  $_POST['deleted'] = 0 ;
  $_POST['mapid'] = 0 ;
  $_POST['map_start_x'] = 0 ;
  $_POST['map_start_y'] = 0 ;
  $_POST['map_stop_x'] = 0 ;
  $_POST['map_stop_y'] = 0 ;

}

if ($placementid  == 0 )
{
  echo '<form method="post" action="admin.php">';
  echo '<table>';
  echo '<tr><td>Emplacement:<td>';
  $dp_itemname = 'placement'; $dp_showdeleted = 1; $dp_noblank = 1 ;require('inc/selectitem.php');
  echo '<tr><td colspan="2" align="center">';
  echo '<input type=hidden name="adminmenu" value="'.$adminmenu .'">';
  echo '<input type="submit" value="Valider">';
  echo '</table></form>';
}

if ($placementid  > 0 )  # Edit data
 
{ 
  echo '<form method="post" action="admin.php">';
  echo '<table>';
  $placementid = $_POST['placementid'];
  $query = 'select placementid,placementname,warehouseid,placementrank,creationzone,pickingzone,transportzone,deletionzone,deleted,mapid,map_start_x,map_start_y,map_stop_x,map_stop_y from placement where placementid=?';
  $query_prm = array($placementid);
  require('inc/doquery.php');
  
  echo '<tr><td>Emplacement:<td><input type="text" STYLE="text-align:left" name="placementname" value="' . $query_result[0]['placementname'] . '" size=30>';
  
  echo '<tr><td>Rank:<td><input type="text" STYLE="text-align:right" name="placementrank" value="' . $query_result[0]['placementrank'] . '" size=10>';
  
  echo '<tr><td rowspan="4">Type de zone:<td>';

  echo '<input type="checkbox" name="creationzone" value="1"'; if ($query_result[0]['creationzone'] == 1) { echo ' checked'; }  echo '> Création';

  echo '<tr><td><input type="checkbox" name="pickingzone" value="1"';  if ($query_result[0]['pickingzone'] == 1) { echo ' checked'; }  echo '> Picking';
  
  echo '<tr><td><input type="checkbox" name="transportzone" value="1"'; if ($query_result[0]['transportzone'] == 1) { echo ' checked'; }   echo '> Transport';
  
  echo '<tr><td><input type="checkbox" name="deletionzone" value="1"'; if ($query_result[0]['deletionzone'] == 1) { echo ' checked'; } echo '> Destruction';  
  
  echo '<tr><tr><td><b>' . d_trad('deleted') . ':<input type="checkbox" name="deleted" value="1"'; if ($query_result[0]['deleted'] == 1) { echo ' checked'; } echo '>';
  
  $mapid = $query_result[0]['mapid'];
  $map_start_x = $query_result[0]['map_start_x'];
  $map_start_y = $query_result[0]['map_start_y'];
  $map_stop_x = $query_result[0]['map_stop_x'];
  $map_stop_y = $query_result[0]['map_stop_y'];
  $warehouseid = $query_result[0]['warehouseid'];
  echo '<tr><td>Entrepôt:<td><select name="warehouseid">'; 
  $query = 'select warehouseid,warehousename from warehouse order by warehousename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $selected = '';
    if ($query_result[$i]['warehouseid'] == $warehouseid) { $selected = ' SELECTED'; }
    echo '<option value="' . $query_result[$i]['warehouseid'] . '"' .$selected .' >' . $query_result[$i]['warehousename'] . '</option>'; 
  }
  echo '</select>';
  echo '<br>';
  echo '<br><b>Coordonnées du plan';
  echo '<br><tr><tr><td>Numéro : <td><input type="number" name="mapid" value="' . $mapid . '" size=5>';
  echo '<tr><tr><td> Début horizontale X: <td><input type="number" name="map_start_x" value="' . $map_start_x . '" size=5>';
  echo '<tr><tr><td> Début verticale Y: <td><input type="number" name="map_start_y" value="' . $map_start_y . '" size=5>';
  echo '<tr><tr><td> Fin horizontale X: <td><input type="number" name="map_stop_x" value="' . $map_stop_x . '" size=5>';
  echo '<tr><tr><td> Fin verticale Y: <td><input type="number" name="map_stop_y" value="' . $map_stop_y . '" size=5>';
  echo '<input type=hidden name="saveme" value="1">';
  echo '<tr><td colspan="2" align="center">';
  echo '<input type=hidden name="step" value="2">';
  echo '<input type=hidden name="adminmenu" value="' .$adminmenu .'">';
  echo '<input type=hidden name="placementid" value="' . $_POST['placementid'] . '">'; 
  echo '<input type="submit" value="Valider">';
  echo '</table></form>';
}
?>