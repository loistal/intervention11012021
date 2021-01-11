<h2>Feuille Entrepôt (Matrice) :</h2>
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
if ($_SESSION['ds_customname'] == 'Wing Chong')
{
  echo '<tr><td><td><select name="split_extraname"><option value=0></option>
  <option value=1>Fusionner Client + '.$_SESSION['ds_term_extraname']
  .'</option></select> &nbsp; <font color=red>Uniquement si necessaire</font>';
}
?>
<tr><td colspan="2" align="center">
<input type=hidden name="report" value="deliverywarehouse4">
<input type="submit" value="Valider">
</table></form>