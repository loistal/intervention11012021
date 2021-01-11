<h2>Balance Client:</h2>
<form method="post" action="reportwindow.php" target="_blank"><table>
<tr><td>
<?php
$datename = 'ourdate'; require('inc/datepicker.php');
?>
<tr><td colspan="2" align="center">
<input type=hidden name="report" value="clientbalance">
<input type="submit" value="Valider"></td></tr></table></form>
