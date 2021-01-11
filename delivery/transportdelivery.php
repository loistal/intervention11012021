<h2>Afficher tournée:</h2>
<form method="post" action="reportwindow.php" target="_blank"><table>
<?php
echo '<tr><td>Date:</td><td>';
$datename = 'invoicegroupdate';
require('inc/datepicker.php');
echo '</td></tr>';
$dp_description = 'Transport'; $dp_itemname = 'companytransport';
require('inc/selectitem.php');
?>
<tr><td colspan="2" align="center"><input type="submit" value="Valider"></td></tr>
</table><input type=hidden name="report" value="transportdelivery"></form>