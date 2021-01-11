<h2>Rapport des Achats</h2>
<form method="post" action="reportwindow.php" target="_blank">
<table>
<tr><td>De:</td><td><?php
$datename = 'start'; $selecteddate = (substr($_SESSION['ds_curdate'],0,4)-1).'-01-01';
require('inc/datepicker.php');
?></td></tr>
<tr><td>A:</td><td><?php
$datename = 'stop'; $selecteddate = (substr($_SESSION['ds_curdate'],0,4)+1).'-12-31';
require('inc/datepicker.php');
?></td></tr>
<tr><td align=right><input type="radio" name="mycat" value="2" CHECKED></td><td>Par date d'arrivage</td></tr>
<tr><td align=right><input type="radio" name="mycat" value="0"></td><td>Par No Commande</td></tr>
<tr><td align=right><input type="radio" name="mycat" value="1"></td><td>Par Bateau</td></tr>
<tr><td align=right><input type="radio" name="mycat" value="3"></td><td>Par fournisseur</td></tr>
<tr><td>Status:</td><td><select name=status><option value=0>Non Finalized</option><option value=1>Finalized</option></select></td></tr>
<tr><td align=right><input type="checkbox" name="showproducts" value="1"></td><td>Afficher produits/fournisseurs</td></tr>
<tr><td colspan=2><input type=hidden name="report" value="purchasereport2">
<input type=hidden name="step" value="1"><input type="submit" value="Valider"></td></tr>
</table>
</form>