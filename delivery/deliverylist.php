<?php
if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1)
{
  echo '<h2>Afficher réception:</h2>';
}
else
{
  echo '<h2>Afficher livraison:</h2>';
}
?>
<form method="post" action="reportwindow.php" target="_blank"><table>
<tr><td>Numéro:</td>
<td><input autofocus type="number" min="0" step="1" STYLE="text-align:right" name="invoicegroupid" size=10></td></tr>
<tr><td>&nbsp;
<tr><td colspan="2" align="center"><input type=hidden name="report" value="deliverylist"><input type="submit" value="Valider"></td></tr>
</table></form>