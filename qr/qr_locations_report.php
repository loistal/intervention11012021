<?php

echo '<h2>Rapport Sites QR</h2>';

echo '<form method="post" action="reportwindow.php" target=_blank><table>';

echo '<tr><td>De:<td>';$datename = 'startdate'; require('inc/datepicker.php');
echo '<tr><td>à:<td>';$datename = 'stopdate'; require('inc/datepicker.php');

echo '<tr><td>Lieu QR:';$dp_itemname = 'qr_location'; $dp_allowall = 1; $dp_noblank = 1; require('inc/selectitem.php');

echo '<tr><td>Employé(e):';$dp_itemname = 'employee'; $dp_allowall = 1; $dp_noblank = 1; require('inc/selectitem.php');

# clientid

echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="qr_locations_report">
<input type="submit" value="' . d_trad('validate') .'"></td></tr></table></form>';


?>