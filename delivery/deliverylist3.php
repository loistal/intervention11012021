<h2>Feuille Entrepôt:</h2>
<form method="post" action="reportwindow.php" target="_blank"><table>
<tr><td>Numéros:<td><input autofocus type="text" name="invoicegroupids" size=80>
<tr><td>Entrepôt:
<?php
$dp_itemname = 'warehouse'; $dp_allowall = 1; $dp_noblank = 1; $dp_selectedid = -1;
require('inc/selectitem.php');
?>
<tr><td>Temperature:
<?php
$dp_itemname = 'temperature'; $dp_allowall = 1; $dp_nonempty = 1; $dp_selectedid = -1;
require('inc/selectitem.php');
?>
<tr><td colspan="2" align="center">
<input type=hidden name="report" value="deliverywarehouse3">
<input type="submit" value="Valider">
</table></form>