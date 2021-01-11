<?php

if ($_SESSION['ds_warehouseaccesstype'] == 1)
{
  echo '<h2>Générateur image code barre</h2>
  <form method="post" action="reportwindow.php" target=_blank>
  <table>
  <tr><td>Code:<td><input autofocus type=text size=12 name="barcode">
  <tr><td>Width:<td><input autofocus type=text size=12 value=600 name="width">
  <tr><td>Height:<td><input autofocus type=text size=12 value=300 name="height">
  <tr><td>Taille Police:<td><input autofocus type=text size=6 value=72 name="fontsize">
  <tr><td colspan="2" align="center"><input type="hidden" name="report" value="displaybarcode"><input type="submit" value="Valider"></td></tr>
  </table></form>';
}

?>