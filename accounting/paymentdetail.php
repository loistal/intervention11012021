<h2>Afficher paiement:</h2>
<form method="post" action="reportwindow.php" target=_blank><table>
<tr><td>Numéro:</td><td><input autofocus type="text" name="paymentid" size=20></td></tr>
<tr><td colspan="2" align="center">
<input type=hidden name="step" value="1"><input type=hidden name="report" value="<?php echo $accountingmenu; ?>">
<input type="submit" value="Valider"></td></tr>
</table></form>