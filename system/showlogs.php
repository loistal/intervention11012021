<h2>Rapport accès</h2>
<form method="post" action="reportwindow.php" target="_blank"><table><tr><td>De:</td><td>
<?php
$datename = 'startdate'; require('inc/datepicker.php');
?>
</td></tr><tr><td>A:</td><td>
<?php
$datename = 'stopdate'; require('inc/datepicker.php');
?>
</td></tr>
<tr><td>Utilisateur:<?php
$dp_itemname = 'user'; $dp_allowall = 1; require('inc/selectitem.php');
?>
<tr><td colspan="2" align="center">
<input type=hidden name="report" value="showlogs">
<input type="submit" value="Valider"></td></tr></table></form>