<h2>Rapport livraison:</h2>
<form method="post" action="reportwindow.php" target=_blank><table><?php

echo '<tr><td>Début:</td><td>';
$datename = 'invgroupstart';
require('inc/datepicker.php');
echo '</td></tr>';
echo '<tr><td>Fin:</td><td>';
$datename = 'invgroupstop';
require('inc/datepicker.php');
echo '</td></tr>';
echo '<tr><td align=right><input type=checkbox name="bynumber" value=1></td><td>Par numéro: <input type="text" STYLE="text-align:right" name="startid" size=10> à <input type="text" STYLE="text-align:right" name="stopid" size=10> (et ne pas par date)</td></tr>';
echo '<tr><td>';
require('inc/selectclient.php');
echo '</td></tr><tr><td>Facture:</td><td><input type="text" STYLE="text-align:right" name="findinvoiceid" size=20></td></tr>
<tr><td>Crée par:';
$dp_itemname = 'user'; $dp_allowall = 1; $dp_noblank = 1;
require('inc/selectitem.php');
echo '</td></tr><tr><td>Transport:';
$dp_itemname = 'companytransport'; $dp_allowall = 1; $dp_selectedid = -1;
require('inc/selectitem.php');
echo '</td></tr>';
echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="deliveryreport"><input type="submit" value="Valider"></td></tr></table></form>';


 ?>