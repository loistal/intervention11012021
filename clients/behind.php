<h2>Retards des paiements:</h2>
<form method="post" action="reportwindow.php" target="_blank"><table>
<?php
$dp_itemname = 'employee'; $dp_iscashier = 1; $dp_description = $_SESSION['ds_term_clientemployee1']; $dp_addtoid = 1;
require('inc/selectitem.php');
$dp_itemname = 'employee'; $dp_iscashier = 1; $dp_description = $_SESSION['ds_term_clientemployee2']; $dp_addtoid = 2;
require('inc/selectitem.php');
$dp_itemname = 'island'; $dp_description = 'Île';
require('inc/selectitem.php');
?>
<tr><td colspan="2" align="center"><input type=hidden name="report" value="behind"><input type="submit" value="Valider"></td></tr>
</table></form>