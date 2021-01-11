<h2>Rapport relivraison:</h2>
<form method="post" action="reportwindow.php" target=_blank><table><?php
echo '<tr><td>Début:</td><td>';
$datename = 'invgroupstart';
require('inc/datepicker.php');
echo '</td></tr>';
echo '<tr><td>Fin:</td><td>';
$datename = 'invgroupstop';
require('inc/datepicker.php');
echo '</td></tr><tr><td>';
require('inc/selectclient.php');
echo '</td></tr><tr><td>Facture:</td><td><input type="text" STYLE="text-align:right" name="invoiceid" size=20></td></tr>';
echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="moddeliveryreport"><input type="submit" value="Valider"></td></tr></table></form>';
?>