<h2>Historique Prix:</h2>
<form method="post" action="reportwindow.php" target=_blank><table>
<tr><td>De:</td><td>
<?php
$datename = 'startdate'; require('inc/datepicker.php');
?>
</td></tr>
<tr><td>A:</td><td>
<?php
$datename = 'stopdate'; require('inc/datepicker.php');
?>
</td></tr><tr><td><?php
require('inc/selectproduct.php');
?></td></tr>
<?php
$dp_itemname = "user"; $dp_description = 'Utilisateur'; $dp_allowall = 1; $dp_noblank = 1; require('inc/selectitem.php');
$dp_description = 'Famille'; $dp_allowall = 1; $dp_noblank = 1; require('inc/selectitem_productfamilygroup.php');
?>
<tr><td>Rangé par:</td><td><select name="orderby">
<option value=1>Date</option>
<option value=2>Produit</option>
<option value=3>Utilisateur</option>
</select></td></tr>
<tr><td colspan="2" align="center"><input type=hidden name="report" value="salespricelog"><input type="submit" value="Valider"></td></tr>
</table></form>