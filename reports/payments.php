<?php
require('preload/paymenttype.php');

$PAYMENT_DATE = 0;
$DEPOSIT_DATE = 1;
$ACCOUNTING_DATE = 0;
$DELIVERY_DATE = 1;
$TOBEPAIDBEFORE_DATE = 3;

$ALL = -1;
$INVOICETYPE_INVOICE = 1;
$INVOICETYPE_RETURN = 2;
$INVOICETYPE_PROFORMA = 3;
$INVOICETYPE_INVOICENOTICE = 4;
$INVOICETYPE_INVOICENOTICERETURN = 5;

$INVOICESTATUS_CONFIRMED1 = 0;
$INVOICESTATUS_CONFIRMEDANDNOTMATCHED = 1;
$INVOICESTATUS_MATCHED = 2;
$INVOICESTATUS_NOTCONFIRMED = 3;
$INVOICESTATUS_CANCELLED = 4;

$ORDERBY_BANK = 1;
$ORDERBY_DEPOSITBANK = 2;
$ORDERBY_PAYMENTDATE = 3;
$ORDERBY_DEPOSITDATE = 4;
$ORDERBY_VATTOTAL = 5;

echo '<h2>' .d_trad('payments:') .'</h2>';

echo '<form method="post" action="reportwindow.php" target=_blank><table>';

// echo '<tr><td colspan=2><b>' . d_trad('paymentinfos') . '<b></td></tr>';
echo '<tr><td>' . d_trad('date:') . '</td><td><select name="paymentdatefield">';
echo '<option value=' . $PAYMENT_DATE .'>' . d_trad('paymentdate') . '</option>';
echo '<option value=' . $DEPOSIT_DATE .'>' . d_trad('depositdate') . '</option>';
echo '<tr><td>' . d_trad('startdate:') . '</td><td>';
$datename = 'paymentstartdate'; if ($_SESSION['ds_restrict_sales_reports']) { $dp_datepicker_min = (mb_substr($_SESSION['ds_curdate'],0,4)-1).'-01-01'; }
require('inc/datepicker.php');
echo '</td></tr>';
echo '<tr><td>' . d_trad('stopdate:') . '</td><td>';
$datename = 'paymentstopdate';
require('inc/datepicker.php');
echo '</td></tr>';

#echo '<tr><td>Heure:</td><td><select name="ampm"><option value="-1"></option><option value="AM">AM</option><option value="PM">PM</option></select></td></tr>';
echo '<tr><td>Heure:<td><input type="time" STYLE="text-align:right" name="starttime" size=10> à <input type="time" STYLE="text-align:right" name="stoptime" size=10></td></tr>';
if ($_SESSION['ds_restrict_sales_reports'] == 0)
{
  echo '<tr><td align=right><input type=checkbox name="bynumber" value=1></td><td>Par numéro: <input type="text" STYLE="text-align:right" name="startid" size=10> à <input type="text" STYLE="text-align:right" name="stopid" size=10></td></tr>';
}
$dp_itemname = 'paymenttype'; $dp_description = d_trad('paymenttype'); $dp_allowall = 1; $dp_selectedid = -1; $dp_noblank = 1;
require('inc/selectitem.php');
if ($_SESSION['ds_restrict_sales_reports'] == 0)
{
$dp_itemname = 'paymentcategory'; $dp_description = d_trad('paymentcategory'); $dp_allowall = 1; $dp_selectedid = -1; $dp_noblank = 1;
require('inc/selectitem.php');
}
$dp_itemname = 'bank'; $dp_description = d_trad('bank'); $dp_allowall = 1; $dp_selectedid = -1; $dp_noblank = 1;
require('inc/selectitem.php');

$dp_itemname = 'bank'; $dp_description = d_trad('depositbank'); $dp_allowall = 1; $dp_selectedid = -1; $dp_noblank = 1; $dp_addtoid = 'deposit';
require('inc/selectitem.php');

echo '<tr><td>Chèque numéro:</td><td><input type=text size=20 name="chequeno"></td></tr>';

echo '<tr><td>' . d_trad('reimbursement:') . '</td><td><input type=checkbox name="reimbursement" value=1></td></tr>';

echo '<tr><td>';
require('inc/selectclient.php');
echo '</td></tr>';

$dp_itemname = 'employee'; $dp_issales = 1; $dp_description = d_trad('employee'); $dp_allowall = 1; $dp_selectedid = -1;
require ('inc/selectitem.php');

$dp_itemname = 'employee'; $dp_addtoid = '1'; $dp_iscashier = 1; $dp_description = d_trad('employeewithparam',$_SESSION['ds_term_clientemployee1']); $dp_allowall = 1; $dp_selectedid = -1;
require ('inc/selectitem.php');

$dp_itemname = 'employee'; $dp_addtoid = '2'; $dp_iscashier = 1; $dp_description = d_trad('employeewithparam',$_SESSION['ds_term_clientemployee2']); $dp_allowall = 1; $dp_selectedid = -1;
require ('inc/selectitem.php');

$dp_itemname = 'user'; $dp_description = d_trad('user'); $dp_allowall = 1; $dp_selectedid = -1; $dp_noblank = 1; 
require('inc/selectitem.php');
if ($_SESSION['ds_restrict_sales_reports'] == 0)
{
$dp_itemname = 'clientcategory'; $dp_description = $_SESSION['ds_term_clientcategory']; $dp_allowall = 1;$dp_noblank = 1;
require('inc/selectitem.php');

$dp_itemname = 'clientcategory2'; $dp_description = $_SESSION['ds_term_clientcategory2']; $dp_allowall = 1;$dp_noblank = 1;
require('inc/selectitem.php');
}
$dp_itemname = 'clientterm'; $dp_description = d_trad('clientterm'); $dp_allowall = 1; $dp_selectedid = -1; $dp_noblank = 1; 
require('inc/selectitem.php');

$dp_itemname = 'island'; $dp_description = d_trad('island'); $dp_allowall = 1; $dp_selectedid = -1; $dp_noblank = 1;
require('inc/selectitem.php');

echo '<tr><td colspan=2>&nbsp;</td></tr>';

echo '<tr><td>' . d_trad('orderby:') . '</td><td><select name="orderby">
<option value=' . $ORDERBY_PAYMENTDATE .'>' . d_trad('paymentdate') . '</option>
<option value=' . $ORDERBY_DEPOSITDATE .'>' . d_trad('depositdate') . '</option>
<option value=' . $ORDERBY_BANK .'>' . d_trad('bank') . '</option>
<option value=' . $ORDERBY_DEPOSITBANK .'>' . d_trad('depositbank') . '</option>
<option value=6 selected>Type de paiement</option>'; #  ???
echo '</select></td></tr>';

echo '<tr><td colspan="2" align="center"><input type=hidden name="report" value="payments"><input type="submit" value="' . d_trad('validate') .'"></td></tr></table></form>';

require('reportwindow/payments_cf.php');
require('inc/configreport.php');

?>