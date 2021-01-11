<h2>Déclaration Prix PPN</h2>
<form method="post" action="reportwindow.php" target="_blank">
<table>
<tr><td>De:</td><td><?php
$datename = 'startdate';
require('inc/datepicker.php');
?></td></tr>
<tr><td>A:</td><td><?php
$datename = 'stopdate';
require('inc/datepicker.php');
?></td></tr>
<tr><td colspan=2><input type=hidden name="report" value="advantagereport">
<input type="submit" value="Valider"></td></tr>
</table>
</form>