<?php

echo '<h2>Rapport Codes Barres</h2>';
echo '<form method="post" action="reportwindow.php" target=_blank>';
echo '<table><tr><td>Du :<td>';
$datename = 'startdate';
require('inc/datepicker.php');
  
echo '<tr><td>  au:<td>';
$datename = 'stopdate';
require('inc/datepicker.php');

echo '<tr><td>Par: <td>';

$dp_itemname = 'user'; $dp_selectedid = $_SESSION['ds_userid']; $dp_allowall = 1; $dp_noblank = 1;
require('inc/selectitem.php');

echo '<tr><td colspan="2" align="center">';
echo '<input type=hidden name="report" value="barcodereport">';
echo '<input type="submit" value="Valider">';
echo '</table>';
echo '</form>';

?>
