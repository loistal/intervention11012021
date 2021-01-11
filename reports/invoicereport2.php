<?php

echo '<h2>' .d_trad('invoicereport:') .'</h2>';
echo '<form method="post" action="reportwindow.php" target=_blank><table>';

echo '<tr><td>' . d_trad('date:') . '</td><td><select name="datefield"><option value=0>' . d_output($_SESSION['ds_term_accountingdate']) . '</option>';
if ($_SESSION['ds_hidedeliverydate'] == 0) { echo '<option value=1>' . d_output($_SESSION['ds_term_deliverydate']) . '</option>'; }
echo '<option value=2>' . d_trad('inputdate') . '</option><option value=3>' . d_trad('tobepaidbefore') . '</option></select></td></tr>';
echo '<tr><td>' . d_trad('startdate:') . '</td><td>';
$datename = 'startdate'; if ($_SESSION['ds_restrict_sales_reports']) { $dp_datepicker_min = (mb_substr($_SESSION['ds_curdate'],0,4)-1).'-01-01'; }
require('inc/datepicker.php');
echo '</td></tr>';
echo '<tr><td>' . d_trad('stopdate:') . '</td><td>';
$datename = 'stopdate';
require('inc/datepicker.php');
echo '</td></tr>';
if ($_SESSION['ds_restrict_sales_reports'] == 0)
{
  echo '<tr><td align=right><input type=checkbox name="bynumber" value=1></td><td>' . d_trad('bynumber:') . '<input type="text" STYLE="text-align:right" name="startid" size=10>&nbsp;'. d_trad('to') . '&nbsp;<input type="text" STYLE="text-align:right" name="stopid" size=10>&nbsp(' . d_trad('notbydate') . ')</td></tr>';
}
echo '<tr><td>' . d_trad('type:') . '</td><td><select name="invoicetype">
<option value=-1>' . d_trad('selectall') . '</option>
<option value=1>' . d_trad('invoice') . '</option>
<option value=2>' . d_trad('isreturn') . '</option>
<option value=3>' . d_trad('proforma') . '</option>
<option value=4>' . $_SESSION['ds_term_invoicenotice'] . '</option>
<option value=5>' . d_trad('isreturnparam',$_SESSION['ds_term_invoicenotice']) . '</option>
</select></td></tr>';

echo '<tr><td>' . d_trad('status:') . '</td><td><select name="invoicestatus">
<option value=-1>' . d_trad('selectall') . '</option>
<option value=0>' . d_trad('confirmed1') . '</option>
<option value=1>' . d_trad('confirmedandnotmatched') . '</option>
<option value=2>' . d_trad('matched') . '</option>
<option value=3>' . d_trad('notconfirmed') . '</option>
<option value=4>' . d_trad('cancelled') . '</option>
<option value=5>Archivée</option>
</select></td></tr>';

echo '<tr><td>Préparé:<td><select name="ig_boolean">
<option value=-1>' . d_trad('selectall') . '</option>
<option value=0>Non</option>
<option value=1>Préparé</option>
</select></td></tr>';

echo '<tr><td>';
require('inc/selectclient.php');
echo '</td></tr>';

$dp_itemname = 'island'; $dp_description = d_trad('island'); $dp_allowall = 1; $dp_selectedid = -1; $dp_noblank = 1;
require('inc/selectitem.php');

if ($_SESSION['ds_term_localvessel'] != '')
{
  $dp_itemname = 'localvessel'; $dp_description = $_SESSION['ds_term_localvessel']; $dp_allowall = 1; $dp_selectedid = -1;
  require('inc/selectitem.php');
}

$dp_itemname = 'user'; $dp_description = d_trad('user'); $dp_allowall = 1; $dp_selectedid = -1; $dp_noblank = 1; 
require('inc/selectitem.php');

$dp_itemname = 'employee'; $dp_issales = 1; $dp_description = d_trad('invoiceemployee'); $dp_allowall = 1; $dp_selectedid = -1;
require ('inc/selectitem.php');

$dp_itemname = 'employee'; $dp_addtoid = '1'; $dp_iscashier = 1; $dp_description = d_trad('employeewithparam',$_SESSION['ds_term_clientemployee1']); $dp_allowall = 1; $dp_selectedid = -1;
require ('inc/selectitem.php');

$dp_itemname = 'employee'; $dp_addtoid = '2'; $dp_iscashier = 1; $dp_description = d_trad('employeewithparam',$_SESSION['ds_term_clientemployee2']); $dp_allowall = 1; $dp_selectedid = -1;
require ('inc/selectitem.php');
if ($_SESSION['ds_restrict_sales_reports'] == 0)
{
  $dp_itemname = 'clientcategory'; $dp_description = $_SESSION['ds_term_clientcategory']; $dp_allowall = 1;
  require('inc/selectitem.php');

  $dp_itemname = 'clientcategory2'; $dp_description = $_SESSION['ds_term_clientcategory2']; $dp_allowall = 1;
  require('inc/selectitem.php');
  
  $dp_itemname = 'clientcategory3'; $dp_description = $_SESSION['ds_term_clientcategory3']; $dp_allowall = 1;
  require('inc/selectitem.php');

  $dp_itemname = 'clientterm'; $dp_description = d_trad('clientterm'); $dp_allowall = 1; $dp_selectedid = -1; $dp_noblank = 1; 
  require('inc/selectitem.php');
}
$dp_itemname = 'invoicetag'; $dp_description = $_SESSION['ds_term_invoicetag']; $dp_allowall = 1; $dp_selectedid = -1;
require ('inc/selectitem.php');
echo ' <input type=checkbox name="exinvoicetag" value=1> Exclure';

echo '<tr><td>' . $_SESSION['ds_term_reference'] . ':</td><td><input type="text" STYLE="text-align:right" name="reference" size=30> <input type=checkbox name="exreference" value=1> Exclure</td></tr>';
echo '<tr><td>' . $_SESSION['ds_term_extraname'] . ':</td><td><input type="text" STYLE="text-align:right" name="extraname" size=30> <input type=checkbox name="exextraname" value=1> Exclure</td></tr>';
echo '<tr><td>Commentaire:<td><input type="text" STYLE="text-align:right" name="invoicecomment" size=30>';
if ($_SESSION['ds_term_field1'] != "")
{
  echo '<tr><td>' . $_SESSION['ds_term_field1'] . ':</td><td><input type="text" STYLE="text-align:right" name="field1" size=30></td></tr>';
}
if ($_SESSION['ds_term_field2'] != "")
{
  echo '<tr><td>' . $_SESSION['ds_term_field2'] . ':</td><td><input type="text" STYLE="text-align:right" name="field2" size=30></td></tr>';
}
if ($_SESSION['ds_useserialnumbers'])
{
  echo '<tr><td>No Serie:</td><td><input type="text" STYLE="text-align:right" name="serial" size=30></td></tr>';
}

echo '<tr><td>' . d_trad('orderby:') . '</td><td><select name="orderby">
<option value=0>' . d_trad('invoicenumber') . '</option>
<option value=1>' . d_trad('clientnumber') . '</option>
<option value=5>' . d_trad('clientname') . '</option>
<option value=6>Date</option>
<option value=2>' . $_SESSION['ds_term_reference'] . '</option>';
if ($_SESSION['ds_term_field1'] != "") { echo '<option value=3>' . $_SESSION['ds_term_field1'] . '</option>'; }
if ($_SESSION['ds_term_field2'] != "") { echo '<option value=4>' . $_SESSION['ds_term_field2'] . '</option>'; }
echo '</select></td></tr>';

echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="invoicereport2"><input type="submit" value="' . d_trad('validate') .'"></td></tr></table></form>';

require('reportwindow/invoicereport_cf.php');
require('inc/configreport.php');

?>