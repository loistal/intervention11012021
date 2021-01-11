<h2>Rapport Retards</h2>
<form method="post" action="reportwindow.php" target="_blank"><table>
<tr><td>Début:</td><td colspan=4><?php
$datename = 'startdate';
require('inc/datepicker.php');
?></td></tr>
<tr><td>Fin:</td><td colspan=4><?php
$datename = 'stopdate';
require('inc/datepicker.php');
?></td></tr>
<tr><td>Periode 1:<td><input type=time name="start1" value="07:00"> 0à <input type=time name="stop1" value="11:30">
<tr><td>Periode 1:<td><input type=time name="start2" value="12:30"> 0à <input type=time name="stop2" value="16:00">
<tr><td colspan="5" align="center">
<input type=hidden name="report" value="hr_badge_late">
<input type="submit" value="Valider"></td></tr></table></form>

<?php

# TODO default time values?