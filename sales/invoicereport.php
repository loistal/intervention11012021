<?php
exit;
require('preload/clientcategory.php');
require('preload/clientcategory2.php');

echo '<h2>Rapport des factures:</h2><form method="post" action="reportwindow.php" target="_blank"><table>';
echo '<tr><td>Date:</td><td><select name="datefield"><option value=0>' . $_SESSION['ds_term_accountingdate'] . '</option>';
if ($_SESSION['ds_hidedeliverydate'] == 0) { echo '<option value=1>' . $_SESSION['ds_term_deliverydate'] . '</option>'; }
echo '<option value=2>Saisie</option><option value=3>Payable</option></select></td></tr>';
echo '<tr><td>De:</td><td>';
$datename = 'startdate';
require('inc/datepicker.php');
echo '</td></tr>';
echo '<tr><td>A:</td><td>';
$datename = 'stopdate';
require('inc/datepicker.php');
echo '</td></tr>';
echo '<tr><td align=right><input type=checkbox name="bynumber" value=1></td><td>Par numéro: <input type="text" STYLE="text-align:right" name="startid" size=10> à <input type="text" STYLE="text-align:right" name="stopid" size=10> (et ne pas par date)</td></tr>';

?>
<tr><td>Client:</td><td><input autofocus type="text" STYLE="text-align:right" name="client" size=20></td></tr>

<?php
$dp_itemname = 'employee'; $dp_iscashier = 1; $dp_description = 'Employé ' . $_SESSION['ds_term_clientemployee1']; $dp_allowall = 1; $dp_selectedid = -1;
require ('inc/selectitem.php');
?>

<?php
$dp_itemname = 'employee'; $dp_addtoid = '2'; $dp_iscashier = 1; $dp_description = 'Employé ' . $_SESSION['ds_term_clientemployee2']; $dp_allowall = 1; $dp_selectedid = -1;
require ('inc/selectitem.php');
?>

<tr><td>Catégorie client:</td>
<td><?php
if (isset($clientcategoryA))
{
  echo '<select name="clientcategoryid">';
  echo '<option value=-1></option>';
  foreach ($clientcategoryA as $clientcategoryidS => $clientcategoryname)
  {
    echo '<option value="' . $clientcategoryidS . '">' . $clientcategoryname . '</option>';
  }
  echo '</select>';
}
?></td></tr>
<tr><td>Catégorie client 2:</td>
<td><?php
if (isset($clientcategory2A))
{
  echo '<select name="clientcategory2id">';
  echo '<option value=-1></option>';
  foreach ($clientcategory2A as $clientcategory2idS => $clientcategory2name)
  {
    echo '<option value="' . $clientcategory2idS . '">' . $clientcategory2name . '</option>';
  }
  echo '</select>';
}
?></td></tr>

<?php
$dp_itemname = 'clientterm'; $dp_description = 'Paiement'; $dp_allowall = 1; $dp_selectedid = -1; $dp_noblank = 1; 
require('inc/selectitem.php');
?>

<tr><td>Facturier:</td>
<td><select name="userid"><?php

$query = 'select userid,name from usertable where userid<>1 and deleted=0 order by name';
$query_prm = array();
  require('inc/doquery.php');
echo '<option value="-1"> </option>';
for ($i=0; $i < $num_results; $i++)
{
  $row2 = $query_result[$i];
  echo '<option value="' . $row2['userid'] . '">' . $row2['name'] . '</option>';
}
?></select></td></tr>

<?php
$dp_itemname = 'employee'; $dp_addtoid = 'f'; $dp_issales = 1; $dp_description = 'Employé (facture)'; $dp_allowall = 1; $dp_selectedid = -1;
require ('inc/selectitem.php');
?>

<?php
echo '<tr><td>';
if ($_SESSION['ds_term_reference'] != "") { echo $_SESSION['ds_term_reference']; }
else { echo 'Référence'; }
echo ':</td><td><input type="text" STYLE="text-align:right" name="reference" size=20> <input type=checkbox name="excluderef" value=1> Exclure</td></tr>';
echo '<tr><td>';
if ($_SESSION['ds_term_extraname'] != "") { echo $_SESSION['ds_term_extraname']; }
else { echo 'Extension du nom'; }
echo ':</td><td><input type="text" STYLE="text-align:right" name="extraname" size=20></td></tr>';
if ($_SESSION['ds_term_field1'] != "")
{
  echo '<tr><td>' . $_SESSION['ds_term_field1'] . ':</td><td><input type="text" STYLE="text-align:right" name="field1" size=20></td></tr>';
}
if ($_SESSION['ds_term_field2'] != "")
{
  echo '<tr><td>' . $_SESSION['ds_term_field2'] . ':</td><td><input type="text" STYLE="text-align:right" name="field2" size=20></td></tr>';
}
if ($_SESSION['ds_useserialnumbers'])
{
  echo '<tr><td>No Serie:</td><td><input type="text" STYLE="text-align:right" name="serial" size=20></td></tr>';
}
?>
<tr><td>Status:</td>
<td><select name="mychoice"><option value=1></option><option value=3>Non confirmées</option><option value=2>Confirmées</option><option value=8>Confirmées et non lettrées</option><option value=9>Lettrées</option><option value=4>Annulées</option></select></td></tr>
<tr><td>Type:</td>
<?php
echo '<td><select name="mychoice2"><option value=1>' . d_trad('selectall') . '</option><option value=2>Factures</option><option value=5>Avoirs</option><option value=6>Proforma</option>
<option value=7>' . $_SESSION['ds_term_invoicenotice'] . '</option><option value=8>Avoir ' . $_SESSION['ds_term_invoicenotice'] . '</option></select></td></tr>';

if ($_SESSION['ds_useinvoicetag'])
{
  require('preload/invoicetag.php');
  if (isset($invoicetagA))
  {
    echo '<tr><td>' . $_SESSION['ds_term_invoicetag'] . ':</td><td><select name="invoicetagid"><option value="0"></option>';
    foreach ($invoicetagA as $invoicetagid => $invoicetagname)
    {
      if ($invoicetag_deletedA[$invoicetagid] != 1) { echo '<option value="' . $invoicetagid . '">' . d_output($invoicetagname) . '</option>'; }
    }
    echo ' <input type=checkbox name="excludetag" value=1> Exclure</td></tr>';
  }
}
?>
<tr><td align=right><input type=checkbox name="showvat" value=1></td><td>Afficher TVA</td></tr>
<tr><td>Ranger par:</td><td><select name="mychoice3"><option value=1>Numéro facture</option><option value=2>Numéro client</option>
<option value=3><?php echo $_SESSION['ds_term_reference']; ?></option>
<?php
if ($_SESSION['ds_term_field1'] != "")
{
  echo '<option value=4>' . $_SESSION['ds_term_field1'] . '</option>';
}
if ($_SESSION['ds_term_field2'] != "")
{
  echo '<option value=5>' . $_SESSION['ds_term_field2'] . '</option>';
}
?>
</select></td></tr>
<tr><td colspan="2" align="center"><input type="submit" value="Valider"></td></tr>

<?php
if ($_SESSION['ds_accountingaccess'] == 1)
{
?>
<tr><td colspan="2" align="center">&nbsp;</td></tr>
<?php
#<tr><td align=right><input type=checkbox name="csv" value=1></td><td>Format CSV &nbsp; <input type=checkbox name="csvfile" value=1> Enregistrer comme Fichier</td></tr>



#<tr><td colspan="2" align="center"><input type="submit" value="Valider"></td></tr>
}
?>


</table><input type=hidden name="report" value="invoicereport"></form>