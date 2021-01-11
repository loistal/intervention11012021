<h2>Rapport logs</h2>
<p class=alert>Attention ce rapport est très lourd.</p>
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
$dp_itemname = 'user'; $dp_allowall = 1; $dp_noblank = 1; require('inc/selectitem.php');
?>
<tr><td>Query:</td><td><input autofocus STYLE="text-align:right" type=text name="querystring" size=20>
<tr><td colspan="2" align="center">
<input type=hidden name="report" value="showlogs2">
<input type="submit" value="Valider"></td></tr></table></form>