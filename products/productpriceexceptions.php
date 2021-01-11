<h2>Exceptions des prix:</h2>
<form method="post" action="reportwindow.php" target="_blank"><table>
<tr><td>
<?php
require('inc/selectclient.php');
?>
<tr><td>
<?php
require('inc/selectproduct.php');
?>
<tr><td colspan="2" align="center">
<input type=hidden name="report" value="productpriceexceptions">
<input type="submit" value="Valider"></td></tr></table></form>