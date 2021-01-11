<?php

$warehouseid = $_POST['warehouseid']+0;
$warehousename = $_POST['warehousename'];
$deleted = $_POST['deleted'];
echo '<h2>Modifier entrepôt:</h2>';

if ($_POST['saveme'] == 1) # Save data
{
  $warehouseid = $_POST['warehouseid']+0;
  $warehousename = $_POST['warehousename'];
  $deleted = $_POST['deleted'];
  $query = 'update warehouse set warehousename="' . $warehousename . '",deleted="' . $deleted . '" where warehouseid="' . $_POST['warehouseid'] . '"';
  $$query_prm = array();
  require('inc/doquery.php');
  echo '<p class=alert> Entrepôt ' . $warehousename . ' modifié.</p>';
  $warehouseid = 0;
}

if ($warehouseid  == 0 ) # Enter data
{
  
  echo '<form method="post" action="admin.php">';
  echo '<table>';
  echo '<tr><td>Entrepôt: <td>';
  $dp_itemname = 'warehouse'; $dp_showdeleted = 1; $dp_noblank = 1 ;require('inc/selectitem.php');
  echo '<tr><td colspan="2" align="center">';
  echo '<input type=hidden name="adminmenu" value="' .$adminmenu .'">';
  echo '<input type=hidden name="warehousename" value="' .$warehousename .'">';
  echo '<input type="submit" value="Valider">';
  echo '</table></form>';
}

if ($warehouseid  > 0 )   # Edit data
{
  $query = 'select warehousename, deleted from warehouse where warehouseid="' . $_POST['warehouseid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<form method="post" action="admin.php">';
  echo '<table><tr>';
  echo '<td>Entrepôt: <td><input type="text" STYLE="text-align:left" name="warehousename" value="' . $query_result[0]['warehousename'] . '" size=30>';
  echo '<tr><tr><td><b>' . d_trad('deleted') . ':<input type="checkbox" name="deleted" value="1"'; if ($query_result[0]['deleted'] == 1) { echo ' checked'; } echo '>';
  echo '<input type=hidden name="saveme" value="1">';
  echo '<tr><td colspan="2" align="center">';
  echo '<input type=hidden name="adminmenu" value="' .$adminmenu .'">';
  echo '<input type=hidden name="warehouseid" value="' . $_POST['warehouseid'] . '">';
  echo '<input type="submit" value="Valider">';
  echo '</table></form>';
} 
?>